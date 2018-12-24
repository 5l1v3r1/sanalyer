<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseWrEtForumPost');

/**
 * forum post write class
 */
Class MbqWrEtForumPost extends MbqBaseWrEtForumPost {

    public function __construct() {
    }
    /**
     * add forum post
     */
    public function addMbqEtForumPost($oMbqEtForumPost) {

        $topic_id   = $oMbqEtForumPost->topicId->oriValue;
        $subject    = $oMbqEtForumPost->postTitle->oriValue;
        $text_body  = $oMbqEtForumPost->postContent->oriValue;
        $groupId = $oMbqEtForumPost->groupId->oriValue;

        $bridge = Tapatalk_Bridge::getInstance();
        $visitor = XenForo_Visitor::getInstance();



        $forumModel = $bridge->getForumModel();
        $threadModel = $bridge->getThreadModel();
        $postModel = $bridge->getPostModel();
        $spamModel = $bridge->getSpamPreventionModel();

        $ftpHelper = $bridge->getHelper('ForumThreadPost');
        $threadFetchOptions = array('readUserId' => $visitor['user_id']);
        $forumFetchOptions = array('readUserId' => $visitor['user_id']);
        list($thread, $forum) = $ftpHelper->assertThreadValidAndViewable($topic_id, $threadFetchOptions, $forumFetchOptions);

        if (!$threadModel->canReplyToThread($thread, $forum, $errorPhraseKey))
        {
            return TT_GetPhraseString($errorPhraseKey);
        }

        $text_body = XenForo_Helper_String::autoLinkBbCode($text_body);

        $writer = XenForo_DataWriter::create('XenForo_DataWriter_DiscussionMessage_Post');
        $writer->set('user_id', $visitor['user_id']);
        $writer->set('username', $visitor['username']);
        $writer->set('message', $text_body);
        $writer->set('message_state', $bridge->getPostModel()->getPostInsertMessageState($thread, $forum));
        $writer->set('thread_id', $thread['thread_id']);
        if (version_compare(XenForo_Application::$version, '1.2.0') >= 0){
            $writer->setOption(XenForo_DataWriter_DiscussionMessage_Post::OPTION_MAX_TAGGED_USERS, $visitor->hasPermission('general', 'maxTaggedUsers'));
        }

        if(!empty($groupId))
        {
            $writer->setExtraData(XenForo_DataWriter_DiscussionMessage::DATA_ATTACHMENT_HASH, $groupId);
        }

        if (!$writer->hasErrors()
           && $writer->get('message_state') == 'visible'
           && !empty($spamModel)
           && $spamModel->visitorRequiresSpamCheck())
        {
			$spamExtraParams = array(
				'permalink' => XenForo_Link::buildPublicLink('canonical:threads', $thread)
			);
            $byoLinkPattern = "/\\[url=https:\\/\\/siteowners.tapatalk.com\\/byo\\/displayAndDownloadByoApp\\?rid=\\d+\\].*\\[\\/url\\]/mi";
            $byoSpamFreeBody = preg_replace($byoLinkPattern,'',$text_body);
            switch ($spamModel->checkMessageSpam($subject . "\n" . $byoSpamFreeBody, $spamExtraParams, $bridge->_request))
			{
				case XenForo_Model_SpamPrevention::RESULT_MODERATED:
				case XenForo_Model_SpamPrevention::RESULT_DENIED;
					$spamModel->logSpamTrigger('post', null);
					$writer->error(new XenForo_Phrase('your_content_cannot_be_submitted_try_later'));
					return TT_GetPhraseString('your_content_cannot_be_submitted_try_later');
					break;
			}
        }
        $writer->preSave();

        if (!$writer->hasErrors())
        {
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
        $writer->save();
        $post = $writer->getMergedData();

        $oTapatalkPush = new TapatalkPush();
        $oTapatalkPush->processPush('AddReply', $post, $thread);

        $data['watch_thread_state'] = false;
        $bridge->getThreadWatchModel()->setVisitorThreadWatchStateFromInput($thread['thread_id'], $data);

        if (version_compare(XenForo_Application::$version, '1.0.4', '>'))
        {
            $threadModel->markThreadRead($thread, $forum, XenForo_Application::$time);
        }
        else
        {
            $threadModel->markThreadRead($thread, $forum, XenForo_Application::$time, $visitor['user_id']);
        }
        $oMbqEtForumPost->state->setOriValue($threadModel->canViewThread($thread, $forum) ? 0 : 1);
        $oMbqEtForumPost->postId->setOriValue($post['post_id']);
        return $oMbqEtForumPost;
    }

    /**
     * modify forum post
     */
    public function mdfMbqEtForumPost($oMbqEtForumPost, $mbqOpt) {

        $bridge = Tapatalk_Bridge::getInstance();
        $visitor = XenForo_Visitor::getInstance();

        $data = array(
                'post_id'       => $oMbqEtForumPost->postId->oriValue,
                'post_title'    => $oMbqEtForumPost->postTitle->oriValue,
                'post_content'  => $oMbqEtForumPost->postContent->oriValue,
                'attachment_id_array' => $oMbqEtForumPost->attachmentIdArray->oriValue,
                'group_id'      => $oMbqEtForumPost->groupId->oriValue,
                'reason'        => $mbqOpt['in']->reason,
        );

        $postModel = $bridge->getPostModel();
        $threadModel = $bridge->getThreadModel();

        $ftpHelper = $bridge->getHelper('ForumThreadPost');
        list($post, $thread, $forum) = $ftpHelper->assertPostValidAndViewable($data['post_id']);

        if(!$postModel->canEditPost($post, $thread, $forum, $errorPhraseKey)){
            $bridge->getErrorOrNoPermissionResponseException($errorPhraseKey);
            return;
        }

        $data['post_content'] = XenForo_Helper_String::autoLinkBbCode($data['post_content']);

        $dw = XenForo_DataWriter::create('XenForo_DataWriter_DiscussionMessage_Post');
        $dw->setExistingData($data['post_id']);
        $dw->set('message', $data['post_content']);
        $dw->setExtraData(XenForo_DataWriter_DiscussionMessage::DATA_ATTACHMENT_HASH,$data['group_id']);
        $dw->setExtraData(XenForo_DataWriter_DiscussionMessage_Post::DATA_FORUM, $forum);
        $dw->preSave();

        if (!$dw->hasErrors())
        {
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
            $errors = $dw->getErrors();
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
        $dw->save();
        $post = $dw->getMergedData();
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

        return $oMbqEtForumPost;
    }

    /**
     * m_delete_post
     */
    public function mDeletePost($oMbqEtForumPost, $mode, $reason) {
        $bridge = Tapatalk_Bridge::getInstance();
        $inlineModPostModel = $bridge->getInlineModPostModel();

        $postId = $oMbqEtForumPost->postId->oriValue;


        $postIds = array_unique(array_map('intval', explode(',', $postId)));

        $options = array(
            'deleteType' => ($mode == 2 ? 'hard' : 'soft'),
            'reason' => $reason,
        );

        $result = $inlineModPostModel->deletePosts($postIds, $options, $errorPhraseKey);

        if($result)
        {
            return true;
        }
        return TT_GetPhraseString($errorPhraseKey);
    }

    /**
     * m_undelete_post
     */
    public function mUndeletePost($oMbqEtForumPost) {
        $bridge = Tapatalk_Bridge::getInstance();
        $inlineModPostModel = $bridge->getInlineModPostModel();

        $postId = $oMbqEtForumPost->postId->oriValue;

        $postIds = array_unique(array_map('intval', explode(',', $postId)));
        $result = $inlineModPostModel->undeletePosts($postIds, array(), $errorPhraseKey);

        if($result)
        {
            return true;
        }
        return TT_GetPhraseString($errorPhraseKey);
    }

    /**
     * m_move_post
     */
    public function mMovePost($oMbqEtForumPosts, $oMbqEtForum, $oMbqEtForumTopic, $topicTitle) {
       $bridge = Tapatalk_Bridge::getInstance();
        $inlineModPostModel = $bridge->getInlineModPostModel();
        $nodeModel = $bridge->getNodeModel();
        $postIds = array();
        foreach($oMbqEtForumPosts as $oMbqEtForumPost)
        {
            $postIds[] = $oMbqEtForumPost->postId->oriValue;
        }

        if(isset($oMbqEtForum))
        {
            $viewableNodes = $nodeModel->getViewableNodeList();
            if (!isset($viewableNodes[$oMbqEtForum->forumId->oriValue])) {
                return TT_GetPhraseString('requested_forum_not_found');
            }
            $options = array(
               'threadNodeId' => $oMbqEtForum->forumId->oriValue,
               'threadTitle' => $topicTitle
           );

            $newThread = $inlineModPostModel->movePosts($postIds, $options, $errorPhraseKey);

            if($newThread)
            {
                return true;
            }
            return TT_GetPhraseString($errorPhraseKey);
        }
        else if(isset($oMbqEtForumTopic))
        {
            $options = array(
                  'threadType' => 'existing',
                  'threadId' => $oMbqEtForumTopic->topicId->oriValue,
              );

            $newThread = $inlineModPostModel->movePosts($postIds, $options, $errorPhraseKey);

            if($newThread)
            {
                return true;
            }
            return TT_GetPhraseString($errorPhraseKey);
        }


    }
    /**
     * report post
     */
    public function reportPost($oMbqEtForumPost, $reason) {
        $bridge = Tapatalk_Bridge::getInstance();
    	$reportModel = $bridge->getReportModel();
	    $reportModel->reportContent('post', $oMbqEtForumPost->mbqBind, $reason);
        return true;
    }


    /**
     * m_approve_post
     */
    public function mApprovePost($oMbqEtForumPost, $mode) {
        $bridge = Tapatalk_Bridge::getInstance();
        $inlineModPostModel = $bridge->getInlineModPostModel();

        $postId = $oMbqEtForumPost->postId->oriValue;

        $postIds = array_unique(array_map('intval', explode(',', $postId)));
        if ($mode == 1)
        {
            $result = $inlineModPostModel->approvePosts($postIds, array(), $errorPhraseKey);
        }
        else
        {
            $result = $inlineModPostModel->unapprovePosts($postIds, array(), $errorPhraseKey);
        }

        if($result)
        {
            return true;
        }
        return TT_GetPhraseString($errorPhraseKey);
    }

    /**
     * m_close_report
     */
    public function mCloseReport($oMbqEtForumPost) {
        $bridge = Tapatalk_Bridge::getInstance();
        $reportModel = $bridge->getReportModel();
        $visitor = XenForo_Visitor::getInstance();
        $postId = $oMbqEtForumPost->postId->oriValue;
        $report=$reportModel->getReportByContent("post",$postId);
        $report = $reportModel->getVisibleReportById($report['report_id']);

        if(!$report){
            return get_error('requested_report_not_found');
        }
        if (!$reportModel->canUpdateReport($report))
        {
            return get_error('you_can_no_longer_update_this_report');
        }
        $dw = XenForo_DataWriter::create('XenForo_DataWriter_ReportComment');
        $dw->bulkSet(array(
                'report_id' => $report['report_id'],
                'user_id' => $visitor['user_id'],
                'username' => $visitor['username'],
                'message' => '',
                'state_change' => 'resolved'
        ));
        $dw->save();

        $dw = XenForo_DataWriter::create('XenForo_DataWriter_Report');
        $dw->setExistingData($report, true);
        $dw->set('report_state', 'resolved');
        $dw->save();
        return true;
    }

    /**
     * thank post
     */
    public function likePost($oMbqEtForumPost) {
        $bridge = Tapatalk_Bridge::getInstance();
        $visitor = XenForo_Visitor::getInstance();


        $postId = $oMbqEtForumPost->postId->oriValue;

        $ftpHelper = $bridge->getHelper('ForumThreadPost');
        list($post, $thread, $forum) = $ftpHelper->assertPostValidAndViewable($postId);

        $likeModel = $bridge->getLikeModel();

        $existingLike = $likeModel->getContentLikeByLikeUser('post', $postId, XenForo_Visitor::getUserId());

        if ($existingLike)
        {
            // It's a mobile app - let's do the sensible thing and stay silent here.
        }
        else
        {
            $latestUsers = $likeModel->likeContent('post', $postId, $post['user_id']);
            //require_once SCRIPT_ROOT.'library/Tapatalk/Push/Push.php';
            //Tapatalk_Push_Push::tapatalk_push_reply('Like', $post, $thread);
        }

        return true;
    }

    /**
     * thank post
     */
    public function unlikePost($oMbqEtForumPost) {
        $bridge = Tapatalk_Bridge::getInstance();
        $visitor = XenForo_Visitor::getInstance();


        $postId = $oMbqEtForumPost->postId->oriValue;

        $ftpHelper = $bridge->getHelper('ForumThreadPost');
        list($post, $thread, $forum) = $ftpHelper->assertPostValidAndViewable($postId);

        $likeModel = $bridge->getLikeModel();

        $existingLike = $likeModel->getContentLikeByLikeUser('post', $postId, XenForo_Visitor::getUserId());

        if ($existingLike)
        {
            $latestUsers = $likeModel->unlikeContent($existingLike);
        }
        else
        {
            // It's a mobile app - let's do the sensible thing and stay silent here.
        }

        return true;
    }
}