<?php

defined('IN_MOBIQUO') or exit;

function user_subscription_func(){
    $code = trim($_POST['code']);
    $uid = trim($_POST['uid']);
    $format = trim($_POST['format']) == 'serialize' ? 'serialize' : 'json';
    
    $connection = new classTTConnection();
    $response = $connection->actionVerification($code,'user_subscription');
    if($response === true)
    {
        try {
            $forums = array();
            $topics = array();
            
            $bridge = Tapatalk_Bridge::getInstance();
            $visitor = XenForo_Visitor::getInstance();
            if(XenForo_Application::get('options')->currentVersionId < 1020070)
            {
          
            }
            else
            {
                $forumWatchModel = $bridge->getForumWatchModel();
                $forumModel = $bridge->getForumModel();

                $forumsWatched = $forumWatchModel->getUserForumWatchByUser($uid);
                $forumids = array_keys($forumsWatched);

                $fetchOptions = array(
                    'watchUserId' => $visitor['user_id'],
                    'readUserId' => $visitor['user_id'],
                );
                $forumdetails = $forumModel->getForumsByIds($forumids, $fetchOptions);
                if (!empty($forumdetails)){
                    $forumdetails = $forumModel->prepareForums($forumdetails);
                }

                foreach($forumdetails as $id => $node)
                {
                    switch ($node['node_type_id'])
                    {
                        case 'Category' : $nodeType = 'category'; break;
                        case 'LinkForum': $nodeType = 'link'; break;
                        default : $nodeType = 'forum'; 
                    }
                 
                    $forums[] = array(
                        'fid'      => $node['node_id'],
                        'name'    => $node['title'],
                    );
                }
             
              
            }
            //TOPICS
            $threadWatchModel = $bridge->getThreadWatchModel();
            $threadModel = $bridge->getThreadModel();

            $fetchOptions = array(
                'join' => XenForo_Model_Thread::FETCH_FORUM | XenForo_Model_Thread::FETCH_USER,
                'readUserId' => $uid,
                'postCountUserId' => $uid,
                'permissionCombinationId' => $visitor['permission_combination_id'],
            );
            $threads = $threadWatchModel->getThreadsWatchedByUser($uid, false,$fetchOptions);
            $totalThreads = $threadWatchModel->countThreadsWatchedByUser($uid);
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

            foreach($threads as &$thread)
            {
                $topics[] = $thread['thread_id'];
            }
            
            $data = array(
                  'result'      => true,
                  'forums'      => $forums,
                  'topics'       => $topics,
              );
        }catch (Exception $e){
            $data = array(
                'result' => false,
                'result_text' => $e->getMessage(),
            );
        }
    }
    else
    {
        $data = array(
            'result' => false,
            'result_text' => $response,
        );
    }
    $response = ($format == 'json') ? json_encode($data) : serialize($data);
    echo $response;
    exit;
}