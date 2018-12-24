<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseRdEtForumTopic');

/**
 * forum topic read class
 */
Class MbqRdEtForumTopic extends MbqBaseRdEtForumTopic {

    public function __construct() {
    }

    public function makeProperty(&$oMbqEtForumTopic, $pName, $mbqOpt = array()) {
        switch ($pName) {
            default:
                MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_PNAME . ':' . $pName . '.');
                break;
        }
    }
    /**
     * get forum topic objs
     *
     * @param  Mixed  $var
     * @param  Array  $mbqOpt
     * $mbqOpt['case'] = 'byForum' means get data by forum obj.$var is the forum obj.
     * $mbqOpt['case'] = 'subscribed' means get subscribed data.$var is the user id.
     * $mbqOpt['case'] = 'byTopicIds' means get data by topic ids.$var is the ids.
     * $mbqOpt['case'] = 'byAuthor' means get data by author.$var is the MbqEtUser obj.
     * $mbqOpt['top'] = true means get sticky data.
     * $mbqOpt['notIncludeTop'] = true means get not sticky data.
     * $mbqOpt['oMbqDataPage'] = pagination class info.
     * $mbqOpt['ann'] = true means get anouncement data.
     * $mbqOpt['oFirstMbqEtForumPost'] = true means load oFirstMbqEtForumPost property of topic,default is true.This param used to prevent infinite recursion call for get oMbqEtForumTopic and oFirstMbqEtForumPost and make memory depleted
     * @return  Mixed
     */
    public function getObjsMbqEtForumTopic($var, $mbqOpt) {

        switch($mbqOpt['case'])
        {
            case 'byForum':
                {
                    $oMbqEtForum = $var;
                    $oMbqDataPage = $mbqOpt['oMbqDataPage'];
                    $ann = isset($mbqOpt['ann']) && $mbqOpt['ann'];
                    $top = isset($mbqOpt['top']) && $mbqOpt['top'];
                    $topic_num = 0;
                    $objsMbqEtForumTopic = array();

                    $forumId = $oMbqEtForum->forumId->oriValue;
                    $bridge = Tapatalk_Bridge::getInstance();
                    $visitor = XenForo_Visitor::getInstance();
                    if(!$bridge->isXenRenresourceForumId($forumId))
                    {
                        $ftpHelper = $bridge->getHelper('ForumThreadPost');
                        $forumFetchOptions = array('readUserId' => $visitor['user_id']);
                        $forum = $ftpHelper->assertForumValidAndViewable($forumId, $forumFetchOptions);
                        $permissions = $visitor->getNodePermissions($oMbqEtForum->mbqBind['node_id']);
                        //get announcement
                        if($ann)
                        {
                                $notices = array();
                                $nodeModel = $bridge->getNodeModel();
                                $node = $nodeModel->getNodeById($forumId);
                                $ann_author = $bridge->getUserModel()->getUserById(1);
                                if(!empty($node))
                                {
                                        if (XenForo_Application::get('options')->enableNotices)
                                        {
                                                if (XenForo_Application::isRegistered('notices'))
                                                {
                                                        $user = XenForo_Visitor::getInstance()->toArray();

                                                        $noticeTokens = array(
                                                            '{name}' => $user['username'] !== '' ? $user['username'] : new XenForo_Phrase('guest'),
                                                            '{user_id}' => $user['user_id'],
                                                        );

                                                        $noticeModel = XenForo_Model::create("XenForo_Model_Notice");
                                                        $dismissedNoticeIds = $noticeModel->getDismissedNoticeIdsForUser($visitor['user_id']);

                                                        foreach (XenForo_Application::get('notices') AS $noticeId => $notice)
                                                        {
                                                                if (in_array($noticeId, $dismissedNoticeIds)
                                                                    ){
                                                                        continue;
                                                                    }

                                                                if (XenForo_Helper_Criteria::userMatchesCriteria($notice['user_criteria'], true, $user)
                                                                    && TT_pageMatchesCriteria($notice['page_criteria'],$node, $nodeModel))
                                                                {
                                                                    $notices[$noticeId] = array(
                                                                        'node_id' => $forumId,
                                                                        'thread_id' => 'tpann_'.$noticeId,
                                                                        'user_id' => isset($ann_author['user_id']) ? $ann_author['user_id'] : 1,
                                                                        'title' => $notice['title'],
                                                                        'message' => str_replace(array_keys($noticeTokens), $noticeTokens, $notice['message']),
                                                                        'wrap' => $notice['wrap'],
                                                                        'dismissible' => ($notice['dismissible'] && XenForo_Visitor::getUserId())
                                                                    );
                                                                }
                                                            }
                                                    }
                                            }
                                    }
                                if(!empty($notices))
                                {
                                    foreach($notices as $thread)
                                    {
                                        $objsMbqEtForumTopic[] = $this->initOMbqEtForumTopic($thread, array('case' => 'byRow','oMbqEtForum' => $oMbqEtForum, 'oMbqEtUser' => true));
                                    }
                                    if ($mbqOpt['oMbqDataPage']) {
                                        $oMbqDataPage = $mbqOpt['oMbqDataPage'];
                                        $oMbqDataPage->totalNum = sizeof($notices);
                                        $oMbqDataPage->datas = $objsMbqEtForumTopic;
                                        return $oMbqDataPage;
                                    } else {
                                        return $objsMbqEtForumTopic;
                                    }
                                }
                                else
                                {
                                    if ($mbqOpt['oMbqDataPage']) {
                                        $oMbqDataPage = $mbqOpt['oMbqDataPage'];
                                        $oMbqDataPage->totalNum = 0;
                                        $oMbqDataPage->datas = array();
                                        return $oMbqDataPage;
                                    } else {
                                        return $objsMbqEtForumTopic;
                                    }
                                }
                            }
                        else
                        {
                            $threadModel = $bridge->getThreadModel();
                            $forumModel = $bridge->getForumModel();
                            $prefixModel = $bridge->_getPrefixModel();
                            $userModel = $bridge->getUserModel();
                            $xenResourceCategoryModel = $bridge->getXenResourceCategoryModel();

                            $start = $oMbqDataPage->startNum;
                            $limit = $oMbqDataPage->numPerPage;

                            $threadFetchConditions = $threadModel->getPermissionBasedThreadFetchConditions($forum) + array(
                                'sticky' => 1
                            );

                            $unreadSticky = 0;
                            $threads = $threadModel->getStickyThreadsInForum($forum['node_id'], $threadFetchConditions, array(
                                'readUserId' => $visitor['user_id'],
                                'watchUserId' => $visitor['user_id'],
                                'postCountUserId' => $visitor['user_id']
                            ));
                            foreach ($threads AS &$thread)
                            {
                                $thread = $threadModel->prepareThread($thread, $forum, $permissions);
                                if($thread['isNew'])
                                    $unreadSticky ++;
                            }
                            unset($thread);

                            $threadFetchConditions['sticky'] = 0;
                            $totalThreads = $threadModel->countThreadsInForum($forum['node_id'], $threadFetchConditions);
                            $threadFetchOptions = array(
                                'limit' => $limit,
                                'offset' => $start,

                                'join' => XenForo_Model_Thread::FETCH_USER | XenForo_Model_Thread::FETCH_FIRSTPOST,
                                'readUserId' => $visitor['user_id'],
                                'watchUserId' => $visitor['user_id'],
                                'postCountUserId' => $visitor['user_id'],

                                'order' => 'last_post_date',
                                'orderDirection' => 'desc'
                            );


                            if($top){
                                $threads = $threadModel->getStickyThreadsInForum($forum['node_id'], $threadFetchConditions, $threadFetchOptions);
                                $totalThreads = count($threads);
                            }else {
                                $threads = $threadModel->getThreadsInForum($forum['node_id'], $threadFetchConditions, $threadFetchOptions);
                            }

                            $inlineModOptions = array();

                            foreach ($threads AS &$thread)
                            {
                                $thread = $threadModel->prepareThread($thread, $forum, $permissions);
                            }
                            unset($thread);


                            foreach($threads as $thread)
                            {
                                $objsMbqEtForumTopic[] = $this->initOMbqEtForumTopic($thread, array('case' => 'byRow','oMbqEtForum' => $oMbqEtForum, 'oMbqEtUser' => true));
                            }
                            if ($mbqOpt['oMbqDataPage']) {
                                $oMbqDataPage = $mbqOpt['oMbqDataPage'];
                                $oMbqDataPage->totalNum = $totalThreads;
                                $oMbqDataPage->datas = $objsMbqEtForumTopic;
                                return $oMbqDataPage;
                            } else {
                                return $objsMbqEtForumTopic;
                            }
                        }
                    }
                    else
                    {
                        $threadModel = $bridge->getThreadModel();
                        $forumModel = $bridge->getForumModel();
                        $prefixModel = $bridge->_getPrefixModel();
                        $userModel = $bridge->getUserModel();

                        $start = $oMbqDataPage->startNum;
                        $limit = $oMbqDataPage->numPerPage;
                        $ftpHelper = $bridge->getHelper('ForumThreadPost');
                        $forumFetchOptions = array('readUserId' => $visitor['user_id']);
                        $forum = $ftpHelper->assertForumValidAndViewable($forumId, $forumFetchOptions);

                        $threadFetchConditions = $threadModel->getPermissionBasedThreadFetchConditions($forum) + array(
                            'sticky' => 1
                        );

                        $unreadSticky = 0;
                        $threads = $threadModel->getStickyThreadsInForum($forum['node_id'], $threadFetchConditions, array(
                            'readUserId' => $visitor['user_id'],
                            'watchUserId' => $visitor['user_id'],
                            'postCountUserId' => $visitor['user_id']
                        ));
                        foreach ($threads AS &$thread)
                        {
                            $thread = $threadModel->prepareThread($thread, $forum, $permissions);
                            if($thread['isNew'])
                                $unreadSticky ++;
                        }
                        unset($thread);

                        $threadFetchConditions['sticky'] = 0;
                        $totalThreads = $threadModel->countThreadsInForum($forum['node_id'], $threadFetchConditions);
                        $threadFetchOptions = array(
                            'limit' => $limit,
                            'offset' => $start,

                            'join' => XenForo_Model_Thread::FETCH_USER | XenForo_Model_Thread::FETCH_FIRSTPOST,
                            'readUserId' => $visitor['user_id'],
                            'watchUserId' => $visitor['user_id'],
                            'postCountUserId' => $visitor['user_id'],

                            'order' => 'last_post_date',
                            'orderDirection' => 'desc'
                        );


                        if($top){
                            $threads = $threadModel->getStickyThreadsInForum($forum['node_id'], $threadFetchConditions, $threadFetchOptions);
                            $totalThreads = count($threads);
                        }else {
                            $threads = $threadModel->getThreadsInForum($forum['node_id'], $threadFetchConditions, $threadFetchOptions);
                        }

                        $inlineModOptions = array();

                        foreach ($threads AS &$thread)
                        {
                            $thread = $threadModel->prepareThread($thread, $forum, $permissions);
                        }
                        unset($thread);


                        foreach($threads as $thread)
                        {
                            $objsMbqEtForumTopic[] = $this->initOMbqEtForumTopic($thread, array('case' => 'byRow','oMbqEtForum' => $oMbqEtForum, 'oMbqEtUser' => true));
                        }
                        if ($mbqOpt['oMbqDataPage']) {
                            $oMbqDataPage = $mbqOpt['oMbqDataPage'];
                            $oMbqDataPage->totalNum = $totalThreads;
                            $oMbqDataPage->datas = $objsMbqEtForumTopic;
                            return $oMbqDataPage;
                        } else {
                            return $objsMbqEtForumTopic;
                        }
                    }

                    break;
                }
            case 'subscribed':
                {
                    $oMbqDataPage = $mbqOpt['oMbqDataPage'];

                    $bridge = Tapatalk_Bridge::getInstance();
                    $visitor = XenForo_Visitor::getInstance();
                    $threadWatchModel = $bridge->getThreadWatchModel();
                    $threadModel = $bridge->getThreadModel();

                    $start = $oMbqDataPage->startNum;
                    $limit = $oMbqDataPage->numPerPage;

                    $fetchOptions = array(
                        'join' => XenForo_Model_Thread::FETCH_FORUM | XenForo_Model_Thread::FETCH_USER,
                        'readUserId' => $visitor['user_id'],
                        'postCountUserId' => $visitor['user_id'],
                        'permissionCombinationId' => $visitor['permission_combination_id'],
                        'limit' => $limit,
                        'offset' => $start,
                    );
                    $threads = $threadWatchModel->getThreadsWatchedByUser($visitor['user_id'], false,$fetchOptions);
                    $totalThreads = $threadWatchModel->countThreadsWatchedByUser($visitor['user_id']);
                    $threads = $threadWatchModel->unserializePermissionsInList($threads, 'node_permission_cache');
                    $threads = $threadWatchModel->getViewableThreadsFromList($threads);

                    // see XenForo_ControllerPublic_Watched::_prepareWatchedThreads
                    foreach ($threads AS &$thread)
                    {
                            if (!$visitor->hasNodePermissionsCached($thread['node_id']))
                            {
                                    $visitor->setNodePermissions($thread['node_id'], $thread['permissions']);
                                }
                            $thread = $threadModel->prepareThread($thread, $thread);
                        }

                    $forums = $bridge->getForumModel()->getForums();
                    $userModel = $bridge->getUserModel();
                    foreach($threads as &$thread)
                    {
                        // filtering hideForums
                        $options = XenForo_Application::get('options');
                        $hideForums = $options->hideForums;
                        if (in_array($thread['node_id'], $hideForums)){
                            $totalThreads -= 1;
                            continue;
                        }

                        $threadModel->standardizeViewingUserReferenceForNode($thread['node_id'], $viewingUser, $nodePermissions);
                        $tmpforum  = isset($forums[$thread['node_id']]) ? $forums[$thread['node_id']] : null;
                        $thread = $threadModel->prepareThread($thread, $tmpforum, $nodePermissions, $viewingUser);
                        $oMbqDataPage->datas[] = $this->initOMbqEtForumTopic($thread, array('case' => 'byRow', 'oMbqEtForum' => true, 'oMbqEtUser' => true));
                    }
                    //they do not return count, only num of pages so we need to play with it
                    $oMbqDataPage->totalNum = $totalThreads;
                    return $oMbqDataPage;
                }

            case 'awaitingModeration':
                {
                    $oMbqEtUser = $var;
                    $oMbqDataPage = $mbqOpt['oMbqDataPage'];

                    $bridge = Tapatalk_Bridge::getInstance();
                    $moderationQueueModel = $bridge->getModerationQueueModel();

                    $queue = $moderationQueueModel->getModerationQueueEntries();
                    foreach($queue as $key => $value)
                        if ($value['content_type'] != 'thread')
                            unset($queue[$key]);

                    $datas = $moderationQueueModel->getVisibleModerationQueueEntriesForUser($queue);

                    $total_topic_num = count($datas);
                    $objsMbqEtForumTopic = array();
                    $threadModel = $bridge->getThreadModel();
                    $datas = array_slice($datas, $oMbqDataPage->startNum, $oMbqDataPage->numPerPage);
                    foreach($datas as $data)
                    {
                            $objsMbqEtForumTopic[] = $this->initOMbqEtForumTopic($data['content_id'], array('case' => 'byTopicId', 'oMbqEtUser' => true));
                        }
                    if ($mbqOpt['oMbqDataPage']) {
                        $oMbqDataPage = $mbqOpt['oMbqDataPage'];
                        $oMbqDataPage->totalNum = $total_topic_num;
                        $oMbqDataPage->datas = $objsMbqEtForumTopic;
                        return $oMbqDataPage;
                    } else {
                        return $objsMbqEtForumTopic;
                    }
                }
            case 'deleted':
                {
                    $oMbqEtUser = $var;
                    $oMbqDataPage = $mbqOpt['oMbqDataPage'];

                    $objsMbqEtForumTopic = array();
                    //foreach($rows as $row)
                    //{
                    //    $objsMbqEtForumTopic[] = $this->initOMbqEtForumTopic($row, array('case' => 'byRow', 'oMbqEtUser' => true));
                    //}
                    if ($mbqOpt['oMbqDataPage']) {
                        $oMbqDataPage = $mbqOpt['oMbqDataPage'];
                        $oMbqDataPage->totalNum = 0;
                        $oMbqDataPage->datas = $objsMbqEtForumTopic;
                        return $oMbqDataPage;
                    } else {
                        return $objsMbqEtForumTopic;
                    }
                }
            case 'byTopicIds':
                {
                    $oMbqDataPage = $mbqOpt['oMbqDataPage'];
                    $arrTids = explode(',', $var);
                    $arrTids = is_array($arrTids) ? $arrTids : array($arrTids);
                    $objsMbqEtForumTopic = array();
                    $oMbqRdEtForumTopic = MbqMain::$oClk->newObj('MbqRdEtForumTopic');
                    $ix = 0;
                    foreach ($arrTids
                        as $tid) {
                            $oMbqEtForumTopic = $oMbqRdEtForumTopic->initOMbqEtForumTopic($tid, array('case' => 'byTopicId'));
                            if($oMbqEtForumTopic != null)
                            {
                                    $objsMbqEtForumTopic[] = $oMbqEtForumTopic;
                                    $ix++;
                                }
                            if($ix >= 50)
                            {
                                break;
                            }
                        }
                    $oMbqDataPage->totalNum = sizeof($objsMbqEtForumTopic);
                    $oMbqDataPage->datas = $objsMbqEtForumTopic;
                    return $oMbqDataPage;
                }

        }
    }
    /**
     * init one forum topic by condition
     *
     * @return  Mixed
     */
    public function initOMbqEtForumTopic($var, $mbqOpt) {
        global $db, $auth, $user, $config;
        if ($mbqOpt['case'] == 'byRow')
        {
            $thread = $var;
            $isMoved=false;
            $isMerged=false;
            $canMerge=true;
            $threadId=$thread['thread_id'];

            $bridge = Tapatalk_Bridge::getInstance();
            $threadModel = $bridge->getThreadModel();
            $postModel = $bridge->getPostModel();
            $userModel = $bridge->getUserModel();
            $visitor = XenForo_Visitor::getInstance();
            if (isset($thread['discussion_type']) && $threadModel->isRedirect($thread))
            {
                $canMerge=false;
                $threadRedirectModel = $bridge->getThreadRedirectModel();
                $newThread = $threadRedirectModel->getThreadRedirectById($thread['thread_id']);
                $redirectKey = $newThread['redirect_key'];
                $parts = preg_split('/-/', $redirectKey);
                $threadId = $parts[1];
                if (count($parts)<4){
                    $isMoved=false;
                    $isMerged=true;
                }else{
                    $isMoved=true;
                    $isMerged=false;
                }
            }
            $oMbqRdEtUser = MbqMain::$oClk->newObj('MbqRdEtUser');
            $oMbqRdEtForumPost = MbqMain::$oClk->newObj('MbqRdEtForumPost');
            $oMbqEtForumTopic = MbqMain::$oClk->newObj('MbqEtForumTopic');
            if(preg_match('/^tpann_\d+$/', $threadId))
            {
                $oMbqEtForumTopic->topicId->setOriValue($thread['thread_id']);
                $oMbqEtForumTopic->topicTitle->setOriValue($thread['title']);
                $oMbqEtForumTopic->topicAuthorId->setOriValue($thread['user_id']);
                if(isset($mbqOpt['oMbqEtUser']))
                {
                    $oMbqEtForumTopic->oAuthorMbqEtUser = $oMbqRdEtUser->initOMbqEtUser($oMbqEtForumTopic->topicAuthorId->oriValue, array('case' => 'byUserId'));
                }
                if(isset($thread['message']))
                {
                    $oMbqEtForumTopic->shortContent->setOriValue($bridge->renderPostPreview($thread['message'], $oMbqEtForumTopic->topicAuthorId->oriValue, 200));
                }
                $oMbqEtForumTopic->mbqBind = $thread;
                return $oMbqEtForumTopic;
            }

            $oMbqEtForumTopic->forumId->setOriValue($thread['node_id']);
            $oMbqEtForumTopic->topicId->setOriValue($thread['thread_id']);
            if(isset($mbqOpt['oMbqEtForum']))
            {
                if($mbqOpt['oMbqEtForum'] instanceof MbqEtForum)
                {
                    $oMbqEtForumTopic->oMbqEtForum = $mbqOpt['oMbqEtForum'];
                }
                else
                {
                    $oMbqRdEtForum = MbqMain::$oClk->newObj('MbqRdEtForum');
                    $oMbqEtForumTopic->oMbqEtForum = $oMbqRdEtForum->initOMbqEtForum($oMbqEtForumTopic->forumId->oriValue, array('case' => 'byForumId'));
                }

            }
            $forum = $oMbqEtForumTopic->oMbqEtForum->mbqBind;
            $oMbqEtForumTopic->topicTitle->setOriValue($thread['title']);
            if(isset($thread['prefix_id']))
            {
                $oMbqEtForumTopic->prefixId->setOriValue($thread['prefix_id']);
                $oMbqEtForumTopic->prefixName->setOriValue(TT_get_prefix_name($thread['prefix_id']));
            }
            $oMbqRdEtUser = MbqMain::$oClk->newObj('MbqRdEtUser');

            $oMbqEtForumTopic->topicAuthorId->setOriValue($thread['user_id']);
            if(isset($mbqOpt['oMbqEtUser']))
            {
                $oMbqEtForumTopic->oAuthorMbqEtUser = $oMbqRdEtUser->initOMbqEtUser($oMbqEtForumTopic->topicAuthorId->oriValue, array('case' => 'byUserId'));
            }
            if(isset($thread['last_post_user_id']) && $thread['last_post_user_id'] != 0)
            {
                $oMbqEtForumTopic->lastReplyAuthorId->setSafeOriValueFromArray($thread,'last_post_user_id');
                $oMbqEtForumTopic->oLastReplyMbqEtUser = $oMbqRdEtUser->initOMbqEtUser($oMbqEtForumTopic->lastReplyAuthorId->oriValue, array('case' => 'byUserId'));
            }
            else
            {
                if(isset($thread['last_post_username']))
                {
                    $oLastReplyMbqEtUser  = $oMbqRdEtUser->initOMbqEtUser($thread['last_post_username'], array('case' => 'byLoginName'));
                    if($oLastReplyMbqEtUser instanceof MbqEtUser)
                    {
                        $oMbqEtForumTopic->oLastReplyMbqEtUser = $oLastReplyMbqEtUser;
                        $oMbqEtForumTopic->lastReplyAuthorId->setOriValue($oLastReplyMbqEtUser->userId->oriValue);
                    }
                }
            }
            $oMbqEtForumTopic->canSubscribe->setOriValue(MbqMain::isActiveMember());
            $threadWatchModel = $bridge->getThreadWatchModel();
            $subscriptionStatus = $threadWatchModel->getThreadWatchStateForVisitor($thread['thread_id'], false);
            if($subscriptionStatus)
            {
                $oMbqEtForumTopic->isSubscribed->setOriValue(true);
                if($subscriptionStatus == 'watch_email')
                {
                    $oMbqEtForumTopic->subscriptionEmail->setOriValue(true);
                }
                else
                {
                    $oMbqEtForumTopic->subscriptionEmail->setOriValue(false);
                }
            }
            else
            {
                $oMbqEtForumTopic->isSubscribed->setOriValue(false);
            }
            if(isset($thread['discussion_open']))
            {
                $oMbqEtForumTopic->isClosed->setOriValue($thread['discussion_open'] == 0);
            }
            if ($bridge->isXenResourceAvailable() && isset($thread['discussion_type']) && $thread['discussion_type'] == "resource")
            {
                $xenResourceResourceModel = $bridge->getXenResourceResourceModel();
                $visitor = XenForo_Visitor::getInstance();

                $fetchOptions = array(
                    'join' => XenResource_Model_Resource::FETCH_CATEGORY
                        | XenResource_Model_Resource::FETCH_USER
                        | XenResource_Model_Resource::FETCH_ATTACHMENT
                        | XenResource_Model_Resource::FETCH_VERSION
                        | XenResource_Model_Resource::FETCH_DESCRIPTION,
                    'watchUserId' => $visitor['user_id'],
                    'permissionCombinationId' => $visitor['permission_combination_id']
                );

                $xenResource = $xenResourceResourceModel->getResourceByDiscussionId($thread['thread_id'], $fetchOptions);
                $xenResource = $xenResourceResourceModel->prepareResource($xenResource, $xenResource);
                $xenResource = $xenResourceResourceModel->prepareResourceCustomFields($xenResource, $xenResource);
                $thread['message'] = $xenResource['description'];
            }
            if(isset($thread['message']))
            {
                $oMbqEtForumTopic->shortContent->setOriValue($bridge->renderPostPreview($thread['message'], $oMbqEtForumTopic->topicAuthorId->oriValue, 200));
            }
            $lastPost = $postModel->getPostById($thread['last_post_id'], array('join' => XenForo_Model_Post::FETCH_USER));
            $oMbqEtForumTopic->lastPostShortContent->setOriValue($bridge->renderPostPreview($lastPost['message'], $thread['last_post_user_id'], 200));

            $oMbqEtForumTopic->authorIconUrl->setOriValue(TT_get_avatar($thread));
            $oMbqEtForumTopic->lastReplyTime->setSafeOriValueFromArray($thread,'last_post_date');
            $oMbqEtForumTopic->postTime->setSafeOriValueFromArray($thread,'post_date');
            if(isset($thread['reply_count']))
            {
                $oMbqEtForumTopic->replyNumber->setOriValue($thread['reply_count']);
                $oMbqEtForumTopic->totalPostNum->setOriValue($thread['reply_count']+1);
            }
            $oMbqEtForumTopic->viewNumber->setSafeOriValueFromArray($thread,'view_count');
            $oMbqEtForumTopic->newPost->setSafeOriValueFromArray($thread,'isNew');
            $oMbqEtForumTopic->likeCount->setSafeOriValueFromArray($thread,'first_post_likes');
            $oMbqEtForumTopic->participatedIn->setOriValue(isset($thread['user_post_count']) &&  $thread['user_post_count'] != null);
            $oMbqEtForumTopic->canDelete->setOriValue($threadModel->canDeleteThread($thread, $forum, 'soft'));
            $oMbqEtForumTopic->canClose->setOriValue($threadModel->canLockUnlockThread($thread, $forum));
            $oMbqEtForumTopic->canApprove->setOriValue($threadModel->canApproveUnapproveThread($thread, $forum));
            $oMbqEtForumTopic->canRename->setOriValue($threadModel->canEditThreadTitle($thread, $forum));
            $oMbqEtForumTopic->canStick->setOriValue($threadModel->canStickUnstickThread($thread, $forum));
            $oMbqEtForumTopic->canMove->setOriValue($threadModel->canMoveThread($thread, $forum));

            $oMbqEtForumTopic->isMoved->setOriValue($isMoved);
            $oMbqEtForumTopic->isMerged->setOriValue($isMerged);
            $oMbqEtForumTopic->canMerge->setOriValue($canMerge&&$threadModel->canMergeThread($thread, $forum));
            if(isset($thread['isModerated']))
            {
                $oMbqEtForumTopic->isApproved->setOriValue(!$thread['isModerated']);
            }
            $oMbqEtForumTopic->isDeleted->setSafeOriValueFromArray($thread,'isDeleted');
            $oMbqEtForumTopic->isSticky->setSafeOriValueFromArray($thread,'sticky');
            $oMbqEtForumTopic->canBan->setOriValue(false);
            if($visitor->hasAdminPermission('ban'))
            {
                if(!empty($thread['user_id']))
                {
                    $posibleSpammer = $userModel->getUserbyId( $thread['user_id']);
                }
                else
                {
                    $posibleSpammer = $userModel->getUserByName($thread['username']);
                }
                if($posibleSpammer && $userModel->couldBeSpammer($posibleSpammer)){
                    $oMbqEtForumTopic->canBan->setOriValue(true);
                }
            }
            $oMbqEtForumTopic->isBan->setSafeOriValueFromArray($thread,'is_banned');


            if(isset( $thread['reply_count']))
            {
                $firstUnreadPosition = $thread['reply_count'];
                if ($visitor['user_id'])
                {
                    $postModel = $bridge->getPostModel();

                    $ftpHelper = $bridge->getHelper('ForumThreadPost');
                    $threadFetchOptions = array('readUserId' => $visitor['user_id']);
                    $forumFetchOptions = array('readUserId' => $visitor['user_id']);
                    try
                    {
                        list($thread, $forum) = $ftpHelper->assertThreadValidAndViewable($thread['thread_id'], $threadFetchOptions, $forumFetchOptions);
                    }
                    catch(Exception $ex)
                    {
                        
                    }
                    $readDate = $bridge->getThreadModel()->getMaxThreadReadDate($thread, $forum);
                    $oMbqEtForumTopic->readTimestamp->setOriValue($readDate);
                    $fetchOptions = $postModel->getPermissionBasedPostFetchOptions($thread, $forum);
                    $firstUnread = $postModel->getNextPostInThread($thread['thread_id'], $readDate, $fetchOptions);
                    if ($firstUnread)
                    {
                        $firstUnreadPosition = $firstUnread['position'];
                    }
                }
                $oMbqEtForumTopic->firstUnreadPosition->setOriValue($firstUnreadPosition);
            }
            $oMbqEtForumTopic->canReply->setOriValue($threadModel->canReplyToThread($thread, $oMbqEtForumTopic->oMbqEtForum->mbqBind, $errorPhraseKey) && MbqMain::isActiveMember());

            $oMbqEtForumTopic->mbqBind = $thread;
            return $oMbqEtForumTopic;
        }
        elseif ($mbqOpt['case'] == 'byTopicId') {
            $bridge = Tapatalk_Bridge::getInstance();
            $visitor = XenForo_Visitor::getInstance();
            $ftpHelper = $bridge->getHelper('ForumThreadPost');
            $threadFetchOptions = array(
                'readUserId' => $visitor['user_id'],
                'watchUserId' => $visitor['user_id'],
                'join' => XenForo_Model_Thread::FETCH_AVATAR
            );
            $forumFetchOptions = array(
                 'readUserId' => $visitor['user_id']
             );
            $threadId = $var;
            if (preg_match('/^tpann_\d+$/', $threadId))
            {
                $prefix_id = preg_split('/_/', $threadId);
                $threadId = $prefix_id[1];
                $notices = array();
                if (XenForo_Application::get('options')->enableNotices)
                {
                    if (XenForo_Application::isRegistered('notices'))
                    {
                        $user = XenForo_Visitor::getInstance()->toArray();

                        $noticeTokens = array(
                                '{name}' => $user['username'] !== '' ? $user['username'] : new XenForo_Phrase('guest'),
                                '{user_id}' => $user['user_id'],
                        );
                        foreach (XenForo_Application::get('notices') AS $noticeId => $notice)
                        {
                            if (XenForo_Helper_Criteria::userMatchesCriteria($notice['user_criteria'], true, $user))
                            {
                                $notices[$noticeId] = array(
                                    'thread_id' => 'tpann_'.$noticeId,
                                    'user_id' => isset($notice['user_id']) ? $notice['user_id'] : 1,
                                    'title' => $notice['title'],
                                    'message' => str_replace(array_keys($noticeTokens), $noticeTokens, $notice['message']),
                                    'wrap' => $notice['wrap'],
                                    'dismissible' => ($notice['dismissible'] && XenForo_Visitor::getUserId())
                                );
                            }
                        }
                    }
                }

                if(isset($notices[$threadId]))
                {
                    $objMbqEtForumTopic = $this->initOMbqEtForumTopic($notices[$threadId], array('case' => 'byRow', 'oMbqEtUser'=>true));
                    return $objMbqEtForumTopic;
                }
            }

            try
            {
                list($thread, $forum) = $ftpHelper->assertThreadValidAndViewable($threadId, $threadFetchOptions, $forumFetchOptions);
            }
            catch(Exception $ex)
            {
                //  TT_LogException($ex);
                return null;
            }
            $objMbqEtForumTopic = false;
            if($thread)
            {
                $objMbqEtForumTopic = $this->initOMbqEtForumTopic($thread, array('case' => 'byRow', 'oMbqEtForum'=>true, 'oMbqEtUser'=>true));
            }
            return $objMbqEtForumTopic;
        }
    }
    public function getUrl($oMbqEtForumTopic)
    {
        return XenForo_Link::buildPublicLink('full:threads', array('thread_id' => $oMbqEtForumTopic->topicId->oriValue, 'title' => $oMbqEtForumTopic->mbqBind['title']));
    }
}