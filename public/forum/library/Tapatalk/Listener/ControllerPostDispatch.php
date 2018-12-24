<?php
class Tapatalk_Listener_ControllerPostDispatch{
    public static function postDispatchListener($controller,
    $controllerResponse,$controllerName,$action){
        switch ($action){
            case 'AddReply':
                if(isset($controllerResponse->params))
                {
                    $params = $controllerResponse->params;
                    $post = isset($params['lastPost']) ? $params['lastPost'] : '';
                    $thread = isset($params['thread']) ? $params['thread'] : '';
                }
                break;
            case 'Like':
                if(isset($controllerResponse->params))
                {
                    $params = $controllerResponse->params;
                    if (isset($params['liked']) && $params['liked']){
                        $post = isset($params['post']) ? $params['post'] : '';
                        $thread = isset($params['thread']) ? $params['thread'] : '';
                    }
                }
                break;
            case 'AddThread':
            case 'Watch':
                if(isset($controllerResponse->redirectTarget))
                {
                    $target = $controllerResponse->redirectTarget;
                    if(strpos($target,'?')!== false)
                    {
                        $url_query = parse_url($target, PHP_URL_QUERY);
                    }
                    else
                    {
                        $url_query = $target;
                    }
                    $split_rs = preg_split('/\//', $url_query);
                    if(!empty($split_rs) && is_array($split_rs))
                    {
                        $location = isset($split_rs[0]) && !empty($split_rs[0])?  $split_rs[0] : '';
                        $title = isset($split_rs[1]) && !empty($split_rs[1])?  $split_rs[1] : '';
                        $other = isset($split_rs[2]) && !empty($split_rs[2])?  $split_rs[2] : '';
                        if ($location == 'threads'){
                            if(preg_match('/\.(\d+)/', $title, $match))
                            {
                                $thread_id = $match[1];
                            } else if (preg_match('/^\d+$/', $title, $match)){
                                $thread_id = $match[0];
                            }
                        }
                    }
                    if (!isset($thread_id) || empty($thread_id)){
                        break;
                    }
                    if ($action == 'Watch'){
                        $threadModel = XenForo_Model::create('XenForo_Model_ThreadWatch');
                        $watch = $threadModel->getUserThreadWatchByThreadIds(XenForo_Visitor::getUserId(), array($thread_id));
                        if (empty($watch)){
                            break;
                        }
                    }
                    $thread_model = XenForo_Model::create('XenForo_Model_Thread');
                    $thread = $thread_model->getThreadById($thread_id);
                    $post_model = XenForo_Model::create('XenForo_Model_Post');
                    $post = $post_model->getLastPostInThread($thread['thread_id']);
                }
                break;
        }
        if (isset($post) && !empty($post) && isset($thread) &&!empty($thread)){
            XenForo_Application::autoload('Tapatalk_Push_Push');
            Tapatalk_Push_Push::tapatalk_push_reply($action, $post, $thread);
        }
    }
}