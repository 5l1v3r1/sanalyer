<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseRdEtForumPost');

/**
 * forum post read class
 */
Class MbqRdEtForumPost extends MbqBaseRdEtForumPost {

    public function __construct() {
    }

    public function makeProperty(&$oMbqEtForumPost, $pName, $mbqOpt = array()) {
        switch ($pName) {
            default:
                MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_PNAME . ':' . $pName . '.');
                break;
        }
    }
    /**
     * get forum post objs
     *
     * @param  Mixed  $var
     * @param  Array  $mbqOpt
     * $mbqOpt['case'] = 'byTopic' means get data by forum topic obj.$var is the forum topic obj.
     * $mbqOpt['case'] = 'byPostIds' means get data by post ids.$var is the ids.
     * $mbqOpt['case'] = 'byReplyUser' means get data by reply user.$var is the MbqEtUser obj.
     * @return  Mixed
     */
    public function getObjsMbqEtForumPost($var, $mbqOpt) {
        switch($mbqOpt['case'])
        {
            case 'byTopic':
                {
                    $oMbqEtForumTopic = $var;
                    $forum = isset($oMbqEtForumTopic->oMbqEtForum) ? $oMbqEtForumTopic->oMbqEtForum->mbqBind : null;
                    $thread = $oMbqEtForumTopic->mbqBind;
                    $oMbqDataPage = $mbqOpt['oMbqDataPage'];
                    $bridge = Tapatalk_Bridge::getInstance();
                    $visitor = XenForo_Visitor::getInstance();
                    $threadModel = $bridge->getThreadModel();
                    $nodeModel = $bridge->getNodeModel();
                    $userModel = $bridge->getUserModel();
                    $oldTopicId = $oMbqEtForumTopic->topicId->oriValue;
                    $ftpHelper = $bridge->getHelper('ForumThreadPost');
                    $threadFetchOptions = array(
                        'readUserId' => $visitor['user_id'],
                        'watchUserId' => $visitor['user_id'],
                        'join' => XenForo_Model_Thread::FETCH_AVATAR
                    );
                    $forumFetchOptions = array(
                        'readUserId' => $visitor['user_id']
                    );
                    $threadId = $oMbqEtForumTopic->topicId->oriValue;

                    $posts = array();

                    // get announcement
                    if (preg_match('/^tpann_\d+$/', $threadId))
                    {
                            $notices = array();
                            $prefix_id = preg_split('/_/', $threadId);
                            $specified_id = $prefix_id[1];
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
                                                                    'title' => $notice['title'],
                                                                    'message' => str_replace(array_keys($noticeTokens), $noticeTokens, $notice['message']),
                                                                    'wrap' => $notice['wrap'],
                                                                    'dismissible' => ($notice['dismissible'] && XenForo_Visitor::getUserId())
                                                            );
                                                        }
                                                }
                                        }
                                }

                            if(isset($notices[$specified_id]))
                            {
                                $notice = $notices[$specified_id];
                                $post = array(
                                    'post_id'         => $threadId,
                                    'post_title'       => '', //not supported in XenForo
                                    'message'   => $notice['message'],
                                    'post_author_name' => 'admin',
                                    'user_type'     => '',
                                    'is_online'     => false,
                                    'can_edit'       => '',
                                    'icon_url'       => '',
                                    'post_time'     => '',
                                    'timestamp'      => '',
                                    'can_like'       => false,
                                    'is_liked'       => false,
                                    'like_count'       => 0,
                                    'can_upload'       => false,
                                    'allow_smilies' => true, // always true

                                    'can_delete'        => false,
                                    'can_approve'      => false,
                                    'can_move'        => false,
                                    'is_approved'      => true,
                                    'is_deleted'        => false,
                                    'can_ban'          => false,
                                    'is_ban'            => false,
                                );
                                $posts[] = $post;
                            }

                        }
                    else
                    {
                        $start = $oMbqDataPage->startNum;
                        $limit = $oMbqDataPage->numPerPage;
                        $postModel = $bridge->getPostModel();

                        $postFetchOptions = $postModel->getPermissionBasedPostFetchOptions($thread, $forum) + array(
                            'limit' => $limit,
                            'offset' => $start,
                            'join' => XenForo_Model_Post::FETCH_USER | XenForo_Model_Post::FETCH_USER_PROFILE,
                            'likeUserId' => $visitor['user_id']
                        );
                        if (isset($postFetchOptions['deleted']) && !empty($postFetchOptions['deleted']))
                        {
                            $postFetchOptions['join'] |= XenForo_Model_Post::FETCH_DELETION_LOG;
                        }

                        $posts = $postModel->getPostsInThread($threadId, $postFetchOptions);

                        $threadModel->logThreadView($threadId);
                    }
                    $newMbqOpt = $mbqOpt;
                    $newMbqOpt['case'] = 'byRow';
                    $newMbqOpt['oMbqEtForum'] = $oMbqEtForumTopic->oMbqEtForum;
                    $newMbqOpt['oMbqEtForumTopic'] = $oMbqEtForumTopic;
                    $newMbqOpt['oMbqDataPage'] = $oMbqDataPage;
                    $newMbqOpt['oMbqEtUser'] = true;
                    $objsMbqEtForumPost = array();
                    foreach($posts as $id => &$post)
                    {
                        $objsMbqEtForumPost[] = $this->initOMbqEtForumPost($post, $newMbqOpt);
                    }
                    /* common end */
                    if (isset($mbqOpt['oMbqDataPage'])) {
                        $oMbqDataPage = $mbqOpt['oMbqDataPage'];
                        $oMbqDataPage->datas = $objsMbqEtForumPost;
                        return $oMbqDataPage;
                    } else {
                        return $objsMbqEtForumPost;
                    }
                    break;
                }

            case 'byPostIds':
                {
                    $arrPids = explode(',', $var);
                    $arrPids = is_array($arrPids) ? $arrPids : array($arrPids);
                    $objsMbqEtForumPost = array();
                    $oMbqRdEtForumPost = MbqMain::$oClk->newObj('MbqRdEtForumPost');
                    foreach ($arrPids
                        as $pid) {
                            $objsMbqEtForumPost[] = $oMbqRdEtForumPost->initOMbqEtForumPost($pid, array('case' => 'byPostId'));
                        }
                    return $objsMbqEtForumPost;


                    break;
                }

            case 'awaitingModeration':
                {
                    $oMbqDataPage = $mbqOpt['oMbqDataPage'];

                    $bridge = Tapatalk_Bridge::getInstance();
                    $moderationQueueModel = $bridge->getModerationQueueModel();

                    $queue = $moderationQueueModel->getModerationQueueEntries();
                    foreach($queue as $key => $value)
                        if ($value['content_type'] != 'post')
                            unset($queue[$key]);

                    $datas = $moderationQueueModel->getVisibleModerationQueueEntriesForUser($queue);

                    $total_post_num = count($datas);
                    $objsMbqEtForumPosts = array();
                    $postModel = $bridge->getPostModel();

                    $datas = array_slice($datas, $oMbqDataPage->startNum, $oMbqDataPage->numPerPage);
                    foreach($datas as $data)
                    {
                            $objsMbqEtForumPosts[] = $this->initOMbqEtForumPost($data['content_id'], array('case' => 'byPostId', 'oMbqEtUser' => true));
                        }
                    if ($mbqOpt['oMbqDataPage']) {
                        $oMbqDataPage = $mbqOpt['oMbqDataPage'];
                        $oMbqDataPage->totalNum = $total_post_num;
                        $oMbqDataPage->datas = $objsMbqEtForumPosts;
                        return $oMbqDataPage;
                    } else {
                        return $objsMbqEtForumPosts;
                    }
                }
            case 'deleted':
                {
                    $oMbqDataPage = $mbqOpt['oMbqDataPage'];

                    $totalNum = 0;
                    $objsMbqEtForumPosts= array();

                    //foreach($rows as $row)
                    //{
                    //    $objsMbqEtForumPosts[] = $this->initOMbqEtForumPost($row, array('case' => 'byRow', 'oMbqEtUser' => true));
                    //}
                    if ($mbqOpt['oMbqDataPage']
                        ) {
                            $oMbqDataPage = $mbqOpt['oMbqDataPage'];
                            $oMbqDataPage->totalNum = $totalNum;
                            $oMbqDataPage->datas = $objsMbqEtForumPosts;
                            return $oMbqDataPage;
                        } else {
                        return $objsMbqEtForumPosts;
                    }
                }
            case 'reported':
                {
                    $oMbqDataPage = $mbqOpt['oMbqDataPage'];

                    $totalNum = 0;
                    $objsMbqEtForumPosts= array();

                    $bridge = Tapatalk_Bridge::getInstance();
                    $reportModel = $bridge->getReportModel();

                    $activeReports = $reportModel->getActiveReports();

                    if (XenForo_Application::isRegistered('reportCounts'))
                    {
                            $reportCounts = XenForo_Application::get('reportCounts');
                            if (count($activeReports) != $reportCounts['activeCount'])
                            {
                                    $reportModel->rebuildReportCountCache(count($activeReports));
                                }
                        }

                    $reports = $reportModel->getVisibleReportsForUser($activeReports);

                    $session = XenForo_Application::get('session');
                    $sessionReportCounts = $session->get('reportCounts');

                    if (!is_array($sessionReportCounts) || $sessionReportCounts['total'] != count($reports))
                    {
                        $sessionReportCounts = $reportModel->getSessionCountsForReports($reports, XenForo_Visitor::getUserId());
                        $sessionReportCounts['lastBuildDate'] = XenForo_Application::$time;
                        $session->set('reportCounts', $sessionReportCounts);
                    }
                    $totalNum = count($reports);
                    $reports = array_slice($reports, $oMbqDataPage->startNum, $oMbqDataPage->numPerPage);
                    foreach($reports as $rid => $report)
                    {
                        // case by 'byPostId' may return null, so judge here
                        $oMbqEtForumPost = $this->initOMbqEtForumPost($report['content_id'], array('case' => 'byPostId', 'oMbqEtForum' => true));
                        if(empty($oMbqEtForumPost))
                        {
                            $totalNum -= 1;
                            continue;
                        }
                        $oMbqDataPage->datas[] = $oMbqEtForumPost;
                    }
                    $oMbqDataPage->totalNum =$totalNum;
                    return $oMbqDataPage;
                }

        }
    }
    /**
     * init one forum post by condition
     *
     * @param  Mixed  $var
     * @param  Array  $mbqOpt
     * $mbqOpt['case'] = 'byObj' means init forum post by obj from viewtopic.php page
     * $mbqOpt['case'] = 'byPostId' means init forum post by post id
     * $mbqOpt['withAuthor'] = true means load post author,default is true
     * $mbqOpt['withAtt'] = true means load post attachments,default is true
     * $mbqOpt['withObjsNotInContentMbqEtAtt'] = true means load the attachement objs not in the content,default is true
     * $mbqOpt['oMbqEtForum'] = true means load oMbqEtForum property of this post,default is true
     * $mbqOpt['oMbqEtForumTopic'] = true means load oMbqEtForumTopic property of this post,default is true
     * $mbqOpt['objsMbqEtThank'] = true means load objsMbqEtThank property of this post,default is true
     * @return  Mixed
     */
    public function initOMbqEtForumPost($var, $mbqOpt) {
        if($mbqOpt['case'] == 'byPostId') {
            $id = $var;
            $bridge = Tapatalk_Bridge::getInstance();
            $ftpHelper = $bridge->getHelper('ForumThreadPost');
            $postModel = $bridge->getPostModel();
            $visitor = XenForo_Visitor::getInstance();
            if (preg_match('/^tpann_\d+$/', $id))
            {
                $positionInTopic = 1;
                try
                {
                    $post = $ftpHelper->getPostOrError($id);
                }
                catch(Exception $ex)
                {
                    return null;
                }
            }
            else
            {
                try
                {
                    $postFetchOptions = array(
                          'join' => XenForo_Model_Post::FETCH_USER | XenForo_Model_Post::FETCH_USER_PROFILE,
                          'likeUserId' => $visitor['user_id']
                      );
                    list($post, $thread, $forum) = $ftpHelper->assertPostValidAndViewable($id,$postFetchOptions);
                }
                catch(Exception $ex)
                {
                    return null;
                }
                $positionInTopic = $post['position'] + 1;
            }
            $oMbqRdEtForumTopic = MbqMain::$oClk->newObj('MbqRdEtForumTopic');
            $oMbqEtForumTopic = $oMbqRdEtForumTopic->initOMbqEtForumTopic($post['thread_id'], array('case' => 'byTopicId'));
            $mbqOpt['oMbqEtForum'] = $oMbqEtForumTopic->oMbqEtForum;
            $mbqOpt['oMbqEtForumTopic'] = $oMbqEtForumTopic;

            $newMbqOpt['case'] = 'byRow';
            $newMbqOpt['oMbqEtForum'] = $oMbqEtForumTopic->oMbqEtForum;
            $newMbqOpt['oMbqEtForumTopic'] = $oMbqEtForumTopic;
            $newMbqOpt['oMbqEtUser'] = true;
            $oMbqEtForumPost = $this->initOMbqEtForumPost($post, $newMbqOpt);
            $oMbqEtForumPost->position->setOriValue($positionInTopic);

            return $oMbqEtForumPost;
        }
        else if($mbqOpt['case'] == 'byRow') {
            $bridge = Tapatalk_Bridge::getInstance();
            $postModel = $bridge->getPostModel();
            $threadModel = $bridge->getThreadModel();
            $visitor = XenForo_Visitor::getInstance();
            $userModel = $bridge->getUserModel();

            $post = $var;
            $oMbqEtForumPost = MbqMain::$oClk->newObj('MbqEtForumPost');
            $oMbqEtForumPost->oMbqEtForumTopic = isset($mbqOpt['oMbqEtForumTopic']) ? $mbqOpt['oMbqEtForumTopic'] : true;
            $oMbqEtForumPost->oMbqEtForum = isset($mbqOpt['oMbqEtForum']) ? $mbqOpt['oMbqEtForum'] : true;

            if($oMbqEtForumPost->oMbqEtForumTopic === true)
            {
                $oMbqRdEtForumTopic = MbqMain::$oClk->newObj('MbqRdEtForumTopic');
                $oMbqEtForumPost->oMbqEtForumTopic = $oMbqRdEtForumTopic->initOMbqEtForumTopic($post['thread_id'], array('case' => 'byTopicId'));
                $oMbqEtForumPost->oMbqEtForum =  $oMbqEtForumPost->oMbqEtForumTopic->oMbqEtForum;
            }
            $topicId = $oMbqEtForumPost->oMbqEtForumTopic->topicId->oriValue;
            if (isset($oMbqEtForumPost->oMbqEtForumTopic->mbqBind['first_post_id']) && $oMbqEtForumPost->oMbqEtForumTopic->mbqBind['first_post_id'] == $post['post_id'] && isset($oMbqEtForumPost->oMbqEtForumTopic->mbqBind['discussion_type']) && $oMbqEtForumPost->oMbqEtForumTopic->mbqBind['discussion_type'] == "resource" && $bridge->isXenResourceAvailable())
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

                $xenResource = $xenResourceResourceModel->getResourceByDiscussionId($topicId, $fetchOptions);
                $xenResource = $xenResourceResourceModel->prepareResource($xenResource, $xenResource);
                $xenResource = $xenResourceResourceModel->prepareResourceCustomFields($xenResource, $xenResource);
                $post = array_merge($post,$xenResource);
                $post['message'] = $xenResource['description'];
                if(isset($xenResource['attachment']))
                {
                    $xenResource['attachment']['content_type'] = '';
                    $post['attachments'][] = $xenResource['attachment'];
                    $post['attach_count']++;
                }
                if(isset($xenResource['external_purchase_url']) && !empty($xenResource['external_purchase_url']) && isset($xenResource['cost']) && !empty($xenResource['cost']))
                {
                    $post['message'] = '[url=' . $xenResource['external_purchase_url'] . ']' . $xenResource['cost'] . '[/url]' . PHP_EOL . $post['message'] ;
                }
                if(isset($xenResource['download_url']) && !empty($xenResource['download_url']))
                {
                    $post['message'] = '[url=' . $xenResource['download_url'] . ']' . $xenResource['download_url'] . '[/url]' . PHP_EOL . $post['message'] ;
                }
            }
            if (preg_match('/^tpann_\d+$/', $topicId))
            {
                $oMbqEtForumPost->postId->setOriValue($post['post_id']);
                $postContent = preg_replace('/\[quote="(.*?), post: (.*?), member: (.*?)"\](.*?)/si', '[quote uid=$3 name="$1" post=$2]$4',$post['message']);
                $postContent = $bridge->cleanPost($postContent, array());
                $oMbqEtForumPost->postContent->setOriValue($postContent);
                $oMbqEtForumPost->postContent->setAppDisplayValue($postContent);
                $oMbqEtForumPost->postContent->setTmlDisplayValue($postContent);
                $oMbqEtForumPost->postContent->setTmlDisplayValueNoHtml($postContent);

                $oMbqEtForumPost->shortContent->setOriValue($bridge->renderPostPreview($post['message'], $oMbqEtForumPost->oMbqEtForumTopic->topicAuthorId->oriValue, 200));
                $oMbqEtForumPost->postAuthorId->setOriValue($oMbqEtForumPost->oMbqEtForumTopic->topicAuthorId->oriValue);
                $oMbqEtForumPost->postAuthorName->setOriValue($post['post_author_name']);

                $oMbqEtForumPost->mbqBind = $post;
                return $oMbqEtForumPost;
            }
            $forumId = $oMbqEtForumPost->oMbqEtForum->forumId->oriValue;
            $threadModel->standardizeViewingUserReferenceForNode($forumId, $viewingUser, $nodePermissions);
            $permissions = $visitor->getNodePermissions($oMbqEtForumPost->oMbqEtForumTopic->mbqBind['node_id']);
            $postModel->addInlineModOptionToPost(
                $post, $oMbqEtForumPost->oMbqEtForumTopic->mbqBind, $oMbqEtForumPost->oMbqEtForum->mbqBind, $permissions
            );

            $post = $postModel->preparePost($post, $oMbqEtForumPost->oMbqEtForumTopic->mbqBind, $oMbqEtForumPost->oMbqEtForum->mbqBind, $permissions);

            $oMbqEtForumPost->postId->setOriValue($post['post_id']);
            $oMbqEtForumPost->forumId->setOriValue($forumId);
            $oMbqEtForumPost->topicId->setOriValue($topicId);
            $oMbqEtForumPost->postTitle->setOriValue('');

            $defaultOptions = array(
              'states' => array(
                  'viewAttachments' => $threadModel->canViewAttachmentsInThread($oMbqEtForumPost->oMbqEtForumTopic->mbqBind, $oMbqEtForumPost->oMbqEtForum->mbqBind),
                        'returnHtml' => isset($mbqOpt['returnHtml'])? $mbqOpt['returnHtml'] : true
                    )
                );
         //   if(isset($post['attach_count']) && !empty($post['attach_count']) && $post['attach_count']>0){
                $postAttachements = $postModel->getAndMergeAttachmentsIntoPosts(array($post['post_id'] => $post));
                $post = $postAttachements[$post['post_id']];
                if(isset($post['attachments']) && !empty($post['attachments'])){
                    $defaultOptions['states']['attachments'] = $post['attachments'];
                }
                if (stripos($post['message'], '[/attach]') !== false)
                {
                    if (preg_match_all('#\[attach(=[^\]]*)?\](?P<id>\d+)\[/attach\]#i', $post['message'], $matches))
                    {
                        foreach ($matches['id'] AS $attachId)
                        {
                            $attachment = isset($post['attachments'][$attachId]) ? $post['attachments'][$attachId] :  null;
                            if($attachment != null)
                            {
                                $oMbqRdAtt = MbqMain::$oClk->newObj('MbqRdEtAtt');
                                $oMbqEtAtt = $oMbqRdAtt->initOMbqEtAtt($attachment, array('case' => 'byRow'));
                                $oMbqEtAtt->postId->setOriValue( $oMbqEtForumPost->postId->oriValue);
                                $oMbqEtAtt->forumId->setOriValue( $oMbqEtForumPost->forumId->oriValue);
                                $oMbqEtForumPost->objsMbqEtAtt[] = $oMbqEtAtt;
                                unset($post['attachments'][$attachId]);
                            }
                            else
                            {
                                $oMbqRdAtt = MbqMain::$oClk->newObj('MbqRdEtAtt');
                                $oMbqEtAtt = $oMbqRdAtt->initOMbqEtAtt($attachId, array('case' => 'byAttId'));
                                if($oMbqEtAtt != null)
                                {
                                    $oMbqEtAtt->postId->setOriValue( $oMbqEtForumPost->postId->oriValue);
                                    $oMbqEtAtt->forumId->setOriValue( $oMbqEtForumPost->forumId->oriValue);
                                    $oMbqEtForumPost->objsMbqEtAtt[] = $oMbqEtAtt;
                                }
                            }
                        }
                    }
                }
                if(isset($post['attachments']))
                {
                    foreach($post['attachments'] as $attachment)
                    {
                        $oMbqRdAtt = MbqMain::$oClk->newObj('MbqRdEtAtt');
                        $oMbqEtAtt = $oMbqRdAtt->initOMbqEtAtt($attachment, array('case' => 'byRow'));
                        $oMbqEtAtt->postId->setOriValue( $oMbqEtForumPost->postId->oriValue);
                        $oMbqEtAtt->forumId->setOriValue( $oMbqEtForumPost->forumId->oriValue);
                        $oMbqEtForumPost->objsNotInContentMbqEtAtt[] = $oMbqEtAtt;
                    }
                }
            //    }
          //  }



            $postContent = preg_replace('/\[quote="(.*?), post: (.*?), member: (.*?)"\](.*?)/si', '[quote uid=$3 name="$1" post=$2]$4',$post['message']);
            $postContent = $bridge->cleanPost($postContent, $defaultOptions);
            if(MbqCM::checkIfUserIsIgnored($post['user_id']))
            {
                $postContent = '[spoiler]' . $postContent . '[/spoiler]';
            }
            $oMbqEtForumPost->postContent->setOriValue($postContent);
            $oMbqEtForumPost->postContent->setAppDisplayValue($postContent);
            $oMbqEtForumPost->postContent->setTmlDisplayValue($postContent);
            $oMbqEtForumPost->postContent->setTmlDisplayValueNoHtml($postContent);

            $oMbqEtForumPost->shortContent->setOriValue($bridge->renderPostPreview($post['message'], $post['user_id'], 200));
            $oMbqEtForumPost->postAuthorId->setOriValue($post['user_id']);
            $oMbqEtForumPost->postAuthorName->oriValue = $post['username'];
            $oMbqRdEtUser = MbqMain::$oClk->newObj('MbqRdEtUser');
            if($mbqOpt['oMbqEtUser'])
            {
                if($oAuthorMbqEtUser = $oMbqRdEtUser->initOMbqEtUser($oMbqEtForumPost->postAuthorId->oriValue, array('case' => 'byUserId')) )
                {
                    if(is_a($oAuthorMbqEtUser,  'MbqEtUser'))
                    {
                        $oMbqEtForumPost->oAuthorMbqEtUser = $oAuthorMbqEtUser;
                        $oMbqEtForumPost->isOnline->setOriValue($oMbqEtForumPost->oAuthorMbqEtUser->isOnline->oriValue);
                    }
                }
            }
            $forum = $oMbqEtForumPost->oMbqEtForum->mbqBind;
            $thread = $oMbqEtForumPost->oMbqEtForumTopic->mbqBind;
            $oMbqEtForumPost->postTime->setOriValue($post['post_date']);
            $oMbqEtForumPost->canEdit->setOriValue(isset($post['canEdit']) && $post['canEdit']);
            $oMbqEtForumPost->canLike->setOriValue(isset($post['canLike']) && $post['canLike']);
            $oMbqEtForumPost->isLiked->setOriValue($post['likes'] > 0 && isset($post['like_date']) && $post['like_date'] > 0);
            $oMbqEtForumPost->likeCount->setOriValue($post['likes']);
            $oMbqEtForumPost->allowSmilies->setOriValue(true);
            $oMbqEtForumPost->canReport->setOriValue(true);
            $oMbqEtForumPost->canDelete->setOriValue($postModel->canDeletePost($post, $thread, $forum, 'soft', $errorPhraseKey, $nodePermissions, $viewingUser));
            $oMbqEtForumPost->canApprove->setOriValue($postModel->canApproveUnapprovePost($post, $thread, $forum, $errorPhraseKey, $nodePermissions, $viewingUser));
            $oMbqEtForumPost->canMove->setOriValue($postModel->canMovePost($post, $thread, $forum, $errorPhraseKey, $nodePermissions, $viewingUser));
            $oMbqEtForumPost->isApproved->setOriValue(!isset($post['isModerated']) || !$post['isModerated']);
            $oMbqEtForumPost->isDeleted->setOriValue(isset($post['isDeleted']) && $post['isDeleted']);
            $oMbqEtForumPost->canBan->setOriValue(false);
            if($visitor->hasAdminPermission('ban'))
            {
                $posibleSpammer = $userModel->getUserbyId($post['user_id']);
                if(is_array($posibleSpammer) && $userModel->couldBeSpammer($posibleSpammer)){
                    $oMbqEtForumPost->canBan->setOriValue(true);
                }
            }
            $oMbqEtForumPost->isBan->setOriValue(isset($post['is_banned']) && $post['is_banned']);

            if (!empty($post['likeUsers']))
            {
                $like_users = $post['likeUsers'];
            }
            if(isset($like_users) && !empty($like_users))
            {
                foreach($like_users as $index => $user)
                {
                    $oMbqEtLike = MbqMain::$oClk->newObj('MbqEtLike');
                    $oMbqEtLike->key->setOriValue($oMbqEtForumPost->postId->oriValue);
                    $oMbqEtLike->userId->setOriValue($user['user_id']);
                    $oMbqEtLike->type->setOriValue('post');
                    if($oLikeEtUser = $oMbqRdEtUser->initOMbqEtUser($user['user_id'], array('case' => 'byUserId')))
                    {
                        $oMbqEtLike->oMbqEtUser = $oLikeEtUser;
                    }
                    $oMbqEtLike->mbqBind = $user;
                    $oMbqEtForumPost->objsMbqEtLike[] = $oMbqEtLike;
                }
            }

            if(isset($post['last_edit_user_id']) && $post['last_edit_user_id'])
            {
                $editName="";
                $editUser=$userModel->getUserById($post['last_edit_user_id']);
                if ($editUser){
                    $editName=$editUser['username'];
                }
                $oMbqEtForumPost->editedByUserId->setOriValue($post['last_edit_user_id']);
                $oMbqEtForumPost->editedByUsername->setOriValue($editName);
                $oMbqEtForumPost->editedByTime->setOriValue($post['last_edit_date']);
            }

            $positionInTopic = $post['position'] + 1;
            $oMbqEtForumPost->position->setOriValue($positionInTopic);

            $oMbqEtForumPost->mbqBind = $post;
            return $oMbqEtForumPost;
        }

    }
    /**
     * return raw post content
     *
     * @return  String
     */
    public function getRawPostContent($oMbqEtForumPost) {
        return $oMbqEtForumPost->mbqBind['message'];
    }
     /**
     * return raw post content
     *
     * @return  String
     */
    public function getRawPostContentOriginal($oMbqEtForumPost) {
        return $oMbqEtForumPost->mbqBind['message'];
    }

    /**
     * return raw post content
     *
     * @return  String
     */
    public function getQuotePostContent($oMbqEtForumPost)
    {
        $postId = $oMbqEtForumPost->postId->oriValue;
        $bridge = Tapatalk_Bridge::getInstance();
        $postModel = $bridge->getPostModel();
        $ftpHelper = $bridge->getHelper('ForumThreadPost');
        list($post, $thread, $forum) = $ftpHelper->assertPostValidAndViewable($postId,  array(
             'join' => XenForo_Model_Post::FETCH_USER
         ));
		$quote = $postModel->getQuoteTextForPost($post);
        return $quote;
    }
    public function getUrl($oMbqEtForumPost)
    {
        $oMbqRdEtForumTopic = MbqMain::$oClk->newObj('MbqRdEtForumTopic');
        $oMbqEtForumTopic = $oMbqRdEtForumTopic->initOMbqEtForumTopic($oMbqEtForumPost->topicId->oriValue, array('case' => 'byTopicId'));
        return XenForo_Link::buildPublicLink('full:threads', array('thread_id' => $oMbqEtForumPost->topicId->oriValue, 'title' => $oMbqEtForumTopic->mbqBind['title'])) . '#post-' . $oMbqEtForumPost->postId->oriValue;
    }
}