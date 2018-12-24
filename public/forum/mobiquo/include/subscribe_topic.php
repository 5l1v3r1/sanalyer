<?php

defined('IN_MOBIQUO') or exit;

function subscribe_topic_func($xmlrpc_params)
{
    $params = php_xmlrpc_decode($xmlrpc_params);
    $bridge = Tapatalk_Bridge::getInstance();
    
    $data = $bridge->_input->filterExternal(array(
            'topic_id' => XenForo_Input::UINT,
            'subscribe_mode' => XenForo_Input::UINT,
    ), $params);

    list($thread, $forum) = $bridge->getHelper('ForumThreadPost')->assertThreadValidAndViewable($data['topic_id']);
    if (!$bridge->getThreadModel()->canWatchThread($thread, $forum))
    {
        return $bridge->responseNoPermission();
    }
    $bridge->getThreadWatchModel()->setThreadWatchState(XenForo_Visitor::getUserId(), $thread['thread_id'], 'watch_no_email'); 

    require_once SCRIPT_ROOT.'library/Tapatalk/Push/Push.php';
    $post_model = XenForo_Model::create('XenForo_Model_Post');
    $post = $post_model->getLastPostInThread($thread['thread_id']);
    Tapatalk_Push_Push::tapatalk_push_reply('Watch', $post, $thread);

    return xmlresptrue();
    
}
