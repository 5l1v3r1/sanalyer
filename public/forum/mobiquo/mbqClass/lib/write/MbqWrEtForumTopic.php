<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseWrEtForumTopic');

/**
 * forum topic write class
 */
Class MbqWrEtForumTopic extends MbqBaseWrEtForumTopic {

    public function __construct() {
    }
    /**
     * add forum topic view num
     */
    public function addForumTopicViewNum($oMbqEtForumTopic) {

    }
    /**
     * add forum topic
     */
    public function addMbqEtForumTopic($oMbqEtForumTopic) {
        $bridge = Tapatalk_Bridge::getInstance();
        $visitor = XenForo_Visitor::getInstance();

        $forum = $oMbqEtForumTopic->oMbqEtForum->mbqBind;

        $forumModel = $bridge->getForumModel();
        $threadModel = $bridge->getThreadModel();
        $prefixModel = $bridge->_getPrefixModel();
        $spamModel = $bridge->getSpamPreventionModel();

        $forumId = $forum['node_id'];

        // glitchy
        if (!$forumModel->canPostThreadInForum($forum, $errorPhraseKey))
        {
            $bridge->getErrorOrNoPermissionResponseException($errorPhraseKey);
            return;
        }

        $message = XenForo_Helper_String::autoLinkBbCode($oMbqEtForumTopic->topicContent->oriValue);

        if (!$prefixModel->verifyPrefixIsUsable($oMbqEtForumTopic->prefixId->oriValue, $forumId))
        {
            $oMbqEtForumTopic->prefixId->setOriValue(0); // not usable, just blank it out
        }

        $writer = XenForo_DataWriter::create('XenForo_DataWriter_Discussion_Thread');
        $writer->set('user_id', $visitor['user_id']);
        $writer->set('username', $visitor['username']);
        $writer->set('title',  $oMbqEtForumTopic->topicTitle->oriValue);
        $writer->set('node_id', $forumId);
        $writer->set('prefix_id', $oMbqEtForumTopic->prefixId->oriValue);

        $writer->set('discussion_state', $bridge->getModelFromCache('XenForo_Model_Post')->getPostInsertMessageState(array(), $forum));

        if (!$writer->hasErrors()
          && $writer->get('discussion_state') == 'visible'
          && !empty($spamModel)
          && $spamModel->visitorRequiresSpamCheck())
        {
            $byoLinkPattern = "/\\[url=https:\\/\\/siteowners.tapatalk.com\\/byo\\/displayAndDownloadByoApp\\?rid=\\d+\\].*\\[\\/url\\]/mi";
            $byoSpamFreeBody = preg_replace($byoLinkPattern,'',$message);
            switch ($spamModel->checkMessageSpam($oMbqEtForumTopic->topicTitle->oriValue . "\n" . $byoSpamFreeBody, array(), $bridge->_request))
            {
                case XenForo_Model_SpamPrevention::RESULT_MODERATED:
                    $writer->set('discussion_state', 'moderated');
                    break;

                case XenForo_Model_SpamPrevention::RESULT_DENIED;
                    $spamModel->logSpamTrigger('thread', null);
                    $writer->error(new XenForo_Phrase('your_content_cannot_be_submitted_try_later'));
                    return TT_GetPhraseString('your_content_cannot_be_submitted_try_later');
                    break;
            }
        }

        $postWriter = $writer->getFirstMessageDw();
        if(!empty($oMbqEtForumTopic->groupId->oriValue))
        {
            $postWriter->setExtraData(XenForo_DataWriter_DiscussionMessage::DATA_ATTACHMENT_HASH, $oMbqEtForumTopic->groupId->oriValue);
        }
        $postWriter->set('message', $message);

        $writer->preSave();
        if (!$writer->hasErrors()){
            try{
                $bridge->assertNotFlooding('post');
            }
            catch(Exception $ex)
            {
                return $bridge->error;
            }
        }
        else
        {
            $errors = $writer->getErrors();
            if(isset($errors['message']))
            {
                return TT_GetPhraseString($errors['message']);
            }
            if ($errors)
            {
                $error = reset($errors);
                if(is_a($error,'Xenforo_Phrase'))
                {
                    return TT_GetXenforoPhraseString($error);
                }
                return $bridge->responseError($error);
            }
        }
        // glitchy
        $writer->save();

        $thread = $writer->getMergedData();
        $post = $bridge->getPostModel()->getLastPostInThread($thread['thread_id']);
        if (version_compare(XenForo_Application::$version, '1.0.4', '>'))
        {
            $threadModel->markThreadRead($thread, $forum, XenForo_Application::$time);
        }
        else
        {
            $threadModel->markThreadRead($thread, $forum, XenForo_Application::$time, $visitor['user_id']);
        }
        $data['watch_thread_state'] = false;
        $bridge->getThreadWatchModel()->setVisitorThreadWatchStateFromInput($thread['thread_id'], $data);
        $oMbqEtForumTopic->state->setOriValue($threadModel->canViewThread($thread, $forum) ? 0 : 1);
        $oMbqEtForumTopic->topicId->setOriValue($thread['thread_id']);
        return $oMbqEtForumTopic;
    }
    /**
     * mark forum topic read
     */
    public function markForumTopicRead($oMbqEtForumTopic) {

        if (preg_match('/^tpann_\d+$/', $oMbqEtForumTopic->topicId->oriValue))
        {
            return true;
        }
        $thread = $oMbqEtForumTopic->mbqBind;
        $forum = $oMbqEtForumTopic->oMbqEtForum->mbqBind;
        $bridge = Tapatalk_Bridge::getInstance();
        $threadModel = $bridge->getThreadModel();
        $visitor = XenForo_Visitor::getInstance();

        if (version_compare(XenForo_Application::$version, '1.0.4', '>'))
        {
            $result = $threadModel->markThreadRead($thread, $forum, XenForo_Application::$time);
        }
        else
        {
            $result = $threadModel->markThreadRead($thread, $forum, XenForo_Application::$time, $visitor['user_id']);
        }
        return $result;
    }

    public function subscribeTopic($oMbqEtForumTopic, $receiveEmail) {
        $topic_id = $oMbqEtForumTopic->topicId->oriValue;
        $bridge = Tapatalk_Bridge::getInstance();

        list($thread, $forum) = $bridge->getHelper('ForumThreadPost')->assertThreadValidAndViewable($topic_id);
        if (!$bridge->getThreadModel()->canWatchThread($thread, $forum))
        {
            return $bridge->responseNoPermission();
        }
        $mode = 'watch_no_email';
        if($receiveEmail)
        {
            $mode = 'watch_email';
        }
        $bridge->getThreadWatchModel()->setThreadWatchState(XenForo_Visitor::getUserId(), $thread['thread_id'], $mode);
        return true;
    }

    public function unsubscribeTopic($oMbqEtForumTopic)
    {
        $topic_id = $oMbqEtForumTopic->topicId->oriValue;
        $bridge = Tapatalk_Bridge::getInstance();


        if($topic_id != 'ALL')
        {
            list($thread, $forum) = $bridge->getHelper('ForumThreadPost')->assertThreadValidAndViewable($topic_id);

            if (!$bridge->getThreadModel()->canWatchThread($thread, $forum))
            {
                return $bridge->responseNoPermission();
            }

            $bridge->getThreadWatchModel()->setThreadWatchState(XenForo_Visitor::getUserId(), $thread['thread_id'], '');

        }
        else
        {
            $visitor = XenForo_Visitor::getInstance();
            $threadWatchModel = $bridge->getThreadWatchModel();
            $threadModel = $bridge->getThreadModel();
            $fetchOptions = array(
                'join' => XenForo_Model_Thread::FETCH_FORUM | XenForo_Model_Thread::FETCH_USER,
                'readUserId' => $visitor['user_id'],
                'postCountUserId' => $visitor['user_id'],
                'permissionCombinationId' => $visitor['permission_combination_id'],
            );
            $threads = $threadWatchModel->getThreadsWatchedByUser($visitor['user_id'], false,$fetchOptions);
            $threads = $threadWatchModel->unserializePermissionsInList($threads, 'node_permission_cache');
            $threads = $threadWatchModel->getViewableThreadsFromList($threads);
            // see XenForo_ControllerPublic_Watched::_prepareWatchedThreads
            $threadids = array();
            foreach ($threads AS &$thread)
            {
                if (!$visitor->hasNodePermissionsCached($thread['node_id']))
                {
                    $visitor->setNodePermissions($thread['node_id'], $thread['permissions']);
                }

                $thread = $threadModel->prepareThread($thread, $thread);
                $threadids[] = $thread['thread_id'];
            }
            if(!empty($threadids))
            {
                $watch = $threadWatchModel->getUserThreadWatchByThreadIds(XenForo_Visitor::getUserId(), $threadids);
                foreach ($watch AS $threadWatch)
                {
                    $dw = XenForo_DataWriter::create('XenForo_DataWriter_ThreadWatch');
                    $dw->setExistingData($threadWatch, true);
                    $dw->delete();
                }
             }
        }
        return true;
    }
    /**
     * reset forum topic subscription
     */
    public function resetForumTopicSubscription($oMbqEtForumTopic) {

    }

    /**
     * m_stick_topic
     */
    public function mStickTopic($oMbqEtForumTopic, $mode) {

        $bridge = Tapatalk_Bridge::getInstance();
        $inlineModThreadModel = $bridge->getInlineModThreadModel();

        $threadId = $oMbqEtForumTopic->topicId->oriValue;

        $threadIds = array_unique(array_map('intval', explode(',', $threadId)));
        if($mode == 1)
        {
            $result = $inlineModThreadModel->stickThreads($threadIds, array(), $errorPhraseKey);
        }
        else
        {
            $result = $inlineModThreadModel->unstickThreads($threadIds, array(), $errorPhraseKey);
        }
        if($result)
        {
            return true;
        }
        return TT_GetPhraseString($errorPhraseKey);
    }

    /**
     * m_close_topic
     */
    public function mCloseTopic($oMbqEtForumTopic, $mode) {
        $bridge = Tapatalk_Bridge::getInstance();
        $inlineModThreadModel = $bridge->getInlineModThreadModel();

        $threadId = $oMbqEtForumTopic->topicId->oriValue;

        $threadIds = array_unique(array_map('intval', explode(',', $threadId)));
        if ($mode == 2)
        {
            $result = $inlineModThreadModel->lockThreads($threadIds, array(), $errorPhraseKey);
        }
        else
        {
            $result = $inlineModThreadModel->unlockThreads($threadIds, array(), $errorPhraseKey);
        }
        if($result)
        {
            return true;
        }
        return TT_GetPhraseString($errorPhraseKey);
    }

    /**
     * m_delete_topic
     */
    public function mDeleteTopic($oMbqEtForumTopic, $mode, $reason) {
        $bridge = Tapatalk_Bridge::getInstance();
        $inlineModThreadModel = $bridge->getInlineModThreadModel();

        $threadId = $oMbqEtForumTopic->topicId->oriValue;


        $threadIds = array_unique(array_map('intval', explode(',', $threadId)));

        $options = array(
            'deleteType' => ($mode == 2 ? 'hard' : 'soft'),
            'reason' => $reason,
        );

        $result = $inlineModThreadModel->deleteThreads($threadIds, $options, $errorPhraseKey);

        if($result)
        {
            return true;
        }
        return TT_GetPhraseString($errorPhraseKey);
    }

    /**
     * m_undelete_topic
     */
    public function mUndeleteTopic($oMbqEtForumTopic) {
        $bridge = Tapatalk_Bridge::getInstance();
        $inlineModThreadModel = $bridge->getInlineModThreadModel();

        $threadId = $oMbqEtForumTopic->topicId->oriValue;

        $threadIds = array_unique(array_map('intval', explode(',', $threadId)));
        $result = $inlineModThreadModel->undeleteThreads($threadIds, array(), $errorPhraseKey);

        if($result)
        {
            return true;
        }
        return TT_GetPhraseString($errorPhraseKey);
    }

    /**
     * m_rename_topic
     */
    public function mRenameTopic($oMbqEtForumTopic, $title) {
        $bridge = Tapatalk_Bridge::getInstance();
        $threadId = $oMbqEtForumTopic->topicId->oriValue;

        $visitor = XenForo_Visitor::getInstance();
        $threadModel = $bridge->getThreadModel();
        $ftpHelper = $bridge->getHelper('ForumThreadPost');
        list($thread, $forum) = $ftpHelper->assertThreadValidAndViewable($threadId);
        $permissions = $visitor->getNodePermissions($forum['node_id']);

        if (!$threadModel->canEditThreadTitle($thread, $forum, $errorPhraseKey))
        {
            return empty($errorPhraseKey) ? 'You have no permissions to perform this action' : $errorPhraseKey;
        }
        $input['title'] =  $title;
        if($oMbqEtForumTopic->prefixId->hasSetOriValue() && !empty($oMbqEtForumTopic->prefixId->oriValue))
        {
            $input['prefix_id'] = $oMbqEtForumTopic->prefixId->oriValue;
            $prefix_valid = true;
            $prefixModel = $bridge->getPrefixModel();
            if(!$prefixModel->verifyPrefixIsUsable($input['prefix_id'], $forum['node_id']))
            {
                $prefix_valid = $input['prefix_id'] = 0;
                if(isset($thread['prefix_id']) && !empty($thread['prefix_id']))
                    $input['prefix_id'] = $thread['prefix_id'];
            }
        }
        else
        {
            $input['prefix_id'] = $thread['prefix_id'];
        }

        // TODO: check prefix requirements?

        $dw = XenForo_DataWriter::create('XenForo_DataWriter_Discussion_Thread');
        $dw->setExistingData($threadId);
        $dw->bulkSet($input);
        $dw->setExtraData(XenForo_DataWriter_Discussion_Thread::DATA_FORUM, $forum);
        $dw->save();

        //Update thread moderation log.
        $newData = $dw->getMergedNewData();
        if ($newData && empty($errorPhraseKey))
        {
            $oldData = $dw->getMergedExistingData();
            $basicLog = array();

            foreach ($newData AS $key => $value)
            {
                $oldValue = (isset($oldData[$key]) ? $oldData[$key] : '-');
                switch ($key)
                {
                    case 'title':
                        XenForo_Model_Log::logModeratorAction(
                            'thread', $thread, 'title', array('old' => $oldValue)
                        );
                        break;

                    case 'prefix_id':
                        if ($oldValue)
                        {
                            $phrase = new XenForo_Phrase('thread_prefix_' . $oldValue);
                            $oldValue = $phrase->render();
                        }
                        else
                        {
                            $oldValue = '-';
                        }
                        XenForo_Model_Log::logModeratorAction(
                            'thread', $thread, 'prefix', array('old' => $oldValue)
                        );
                        break;

                    default:
                        if (!in_array($key, $skip))
                        {
                            $basicLog[$key] = $oldValue;
                        }
                }
            }

            if ($basicLog)
            {
                XenForo_Model_Log::logModeratorAction('thread', $thread, 'edit', $basicLog);
            }
        }
        if(empty($errorPhraseKey))
        {
            return true;
        }
        return empty($errorPhraseKey) ? (!isset($prefix_valid)? '' : ($prefix_valid ? '': 'Changes have been saved while prefix is not valid')): TT_GetPhraseString($errorPhraseKey);
    }

    /**
     * m_move_topic
     */
    public function mMoveTopic($oMbqEtForumTopic, $oMbqEtForum, $redirect) {
        $bridge = Tapatalk_Bridge::getInstance();
        $inlineModThreadModel = $bridge->getInlineModThreadModel();
        $nodeModel = $bridge->getNodeModel();

        $threadId = $oMbqEtForumTopic->topicId->oriValue;


        $threadIds = array_unique(array_map('intval', explode(',', $threadId)));

        $viewableNodes = $nodeModel->getViewableNodeList();
        if (!isset($viewableNodes[$oMbqEtForum->forumId->oriValue])) {
            return get_error('requested_forum_not_found');
        }
        $options = array('redirect' => 1, 'redirectExpiry' => time() + 86400);

        $result = $inlineModThreadModel->moveThreads($threadIds, $oMbqEtForum->forumId->oriValue, $options, $errorPhraseKey);

        if($result)
        {
            return true;
        }
        return TT_GetPhraseString($errorPhraseKey);
    }

    /**
     * m_merge_topic
     */
    public function mMergeTopic($oMbqEtForumTopicFrom, $oMbqEtForumTopicTo ,$redirect) {
        $bridge = Tapatalk_Bridge::getInstance();
        $inlineModThreadModel = $bridge->getInlineModThreadModel();
        $nodeModel = $bridge->getNodeModel();

        $threadId = $oMbqEtForumTopicFrom->topicId->oriValue;

        $threadId = array_unique(array_map('intval', explode(',', $threadId)));
        $threadIds = array($threadId,  $oMbqEtForumTopicTo->topicId->oriValue);
        $options = array('redirect' => false);

        if ($redirect){
            $redirect_ttl_value=1;
            $redirect_ttl_unit='days';
            $expiryDate = strtotime('+' . $redirect_ttl_value . ' ' . $redirect_ttl_unit);
            $options = array('redirect' => true , 'redirectExpiry' => $expiryDate);
        }

        $targetThread = $inlineModThreadModel->mergeThreads($threadIds, $oMbqEtForumTopicTo->topicId->oriValue, $options, $errorPhraseKey);

        if($targetThread)
        {
            return true;
        }
        return TT_GetPhraseString($errorPhraseKey);
    }

    /**
     * m_approve_topic
     */
    public function mApproveTopic($oMbqEtForumTopic, $mode) {
        $bridge = Tapatalk_Bridge::getInstance();
        $inlineModThreadModel = $bridge->getInlineModThreadModel();

        $threadId = $oMbqEtForumTopic->topicId->oriValue;

        $threadIds = array_unique(array_map('intval', explode(',', $threadId)));
        if ($mode == 1)
        {
            $result = $inlineModThreadModel->approveThreads($threadIds, array(), $errorPhraseKey);
        }
        else
        {
            $result = $inlineModThreadModel->unapproveThreads($threadIds, array(), $errorPhraseKey);
        }

        if($result)
        {
            return true;
        }
        return TT_GetPhraseString($errorPhraseKey);
    }
}
