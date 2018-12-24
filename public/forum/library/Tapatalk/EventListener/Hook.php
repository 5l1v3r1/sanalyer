<?php

class Tapatalk_EventListener_Hook
{
    public static function templateHook ($hookName, &$contents, $hookParams, XenForo_Template_Abstract $template)
    {
        if ($hookName == 'page_container_head')
        {
            $options = XenForo_Application::get('options');
            $app_kindle_url = XenForo_Application::get('options')->tp_kf_url;
            $app_android_id = XenForo_Application::get('options')->tp_android_url;
            $app_ios_id = XenForo_Application::get('options')->tp_app_ios_id;
            $app_banner_message = XenForo_Application::get('options')->tp_app_banner_msg;
            $app_banner_message = preg_replace('/\r\n/','<br>',$app_banner_message);
            $app_location_url = Tapatalk_EventListener_Hook::get_scheme_url($page_type, $id_value);
            if($page_type == 'index')
            {
                $page_type = 'home';
            }
            $is_mobile_skin = false;
            $app_forum_name = XenForo_Application::get('options')->boardTitle;
            $board_url = self::tt_get_board_url();
            
            $tapatalk_dir = XenForo_Application::get('options')->tp_directory;  // default as 'mobiquo'
            $tapatalk_dir_url = $board_url.'/'.$tapatalk_dir;
            $api_key = XenForo_Application::get('options')->tp_push_key;
            $twitterfacebook_card_enabled = XenForo_Application::get('options')->twitterfacebook_card_enabled;
            $app_banner_enable = XenForo_Application::get('options')->full_banner;
            $tapatalk_dir_name = XenForo_Application::get('options')->tp_directory;

            $banner_last_check = isset($options->banner_last_check) ? intval($options->banner_last_check) : null;
            $banner_control = isset($options->banner_control) ? $options->banner_control : true;
            $validTime = 60 * 60 * 24;
            if (empty($banner_last_check) || time() - $banner_last_check > $validTime){
                $forum_root = dirname(dirname(dirname(dirname(__FILE__))));
                if (file_exists($forum_root.'/'.$tapatalk_dir_name.'/lib/classTTConnection.php')){
                    include_once($forum_root.'/'.$tapatalk_dir_name.'/lib/classTTConnection.php');
                }
                if (class_exists('classTTConnection')){
                    $connection = new classTTConnection();
                    $url = "https://tapatalk.com/get_forum_info.php";
                    $data = array(
                        'key' => md5($options->tp_push_key),
                        'url' => self::tt_get_board_url(),
                    );
                    $response = $connection->getContentFromSever($url,$data,'post',true);
                    if (!empty($response)){
                        $forum_info = self::handle_forum_info($response);
                        if (isset($forum_info['banner_control'])){
                            $banner_control = (bool)$forum_info['banner_control'];
                            $input['banner_control'] = $banner_control;
                        }
                    }
                    $input['banner_last_check'] = time();
                    $optionModel = XenForo_Model::create('XenForo_Model_Option');
                    $optionModel->updateOptions($input);
                }
            }
            if (!$banner_control){
                $app_banner_enable = 1;
            }

            $containerData = $template->getExtraContainerData();
            $twc_title = isset($containerData['title']) ? $containerData['title'] : $containerData['h1'];
            //$twc_description = '';
            //$twc_image = '';
            
            if (empty($tapatalk_dir_name)) $tapatalk_dir_name = 'mobiquo';
            $forum_root = dirname(dirname(dirname(dirname(__FILE__))));
            
            $hide_forums = $options->hideForums;

            $is_show = self::is_show_banner($page_type, intval($id_value), $hide_forums);

            if (!function_exists('tt_getenv')){
               include($forum_root.'/'.$tapatalk_dir_name.'/smartbanner/head.inc.php');
            }
            if($is_show && isset($app_head_include)){
                $contents .= $app_head_include;
            }
            $contents .= '
<!-- Tapatalk Detect style start -->
<style type="text/css">
.ui-mobile [data-role="page"], .ui-mobile [data-role="dialog"], .ui-page 
{
top:auto;
}
</style>
<!-- Tapatalk Detect banner style end -->
                ';
        }
        else if($hookName == 'body')
        {
            $contents = '
<!-- Tapatalk Detect body start -->
<script type="text/javascript">if(typeof(tapatalkDetect) == "function"){tapatalkDetect()}</script>
<!-- Tapatalk Detect banner body end -->
                '.$contents;
        }
    }
    
    private static function is_show_banner($page_type, $id_value, array $hide_forums){
        $forum_model = XenForo_Model::create('XenForo_Model_Forum');
        $node_model = XenForo_Model::create('XenForo_Model_Node');

        if (empty($id_value)){
            return true;
        }

        switch ($page_type){
            case 'forum':
                $current_node = $forum_model->getForumById($id_value);
                break;
            case 'topic':
                $current_node = $forum_model->getForumByThreadId($id_value);
                break;
            case 'node':
                $current_node = $node_model->getNodeById($id_value);
                break;
            default:
                return true;
        }

        if(isset($current_node['node_id']) && !empty($current_node['node_id'])){
            if (in_array($current_node['node_id'], $hide_forums)){
                return false;
            }
            if (isset($current_node['parent_node_id']) && !empty($current_node['parent_node_id'])){
                if (in_array($current_node['parent_node_id'], $hide_forums)){
                    return false;
                }
                return self::is_show_banner('node', $current_node['parent_node_id'], $hide_forums);
            }
        }
        return true;
    }

    public static function get_scheme_url(&$location, &$id_value)
    {
        $baseUrl = self::tt_get_board_url().'?';
        $baseUrl = preg_replace('/https?:\/\//', 'tapatalk://', $baseUrl);
        $visitor = XenForo_Visitor::getInstance();
        $options = XenForo_Application::get('options');
        if($visitor['user_id'] != 0)
            $baseUrl .= 'user_id='.$visitor['user_id'].'&';

        $router = new XenForo_Router();
        $path = $router->getRoutePath(new Zend_Controller_Request_Http());

        $location = 'index';
        $split_rs = preg_split('/\//', $path);
        if(!empty($split_rs) && is_array($split_rs))
        {
            $action = isset($split_rs[0]) && !empty($split_rs[0])?  $split_rs[0] : '';
            $title = isset($split_rs[1]) && !empty($split_rs[1])?  $split_rs[1] : '';
            $other = isset($split_rs[2]) && !empty($split_rs[2])?  $split_rs[2] : '';
            if(!empty($action))
            {

                switch($action)
                {
                    case 'threads':
                        $location = 'topic';
                        $id_name = 'tid';
                        $perPage = $options->messagesPerPage;
                        break;
                    case 'forums':
                        $location = 'forum';
                        $id_name = 'fid';
                        $perPage = $options->discussionsPerPage;
                        break;
                    case 'members':
                        $location = 'profile';
                        $id_name = 'uid';
                        $perPage = $options->membersPerPage;
                        break;
                    case 'conversations':
                        $location = 'message';
                        $id_name = 'mid';
                        $perPage = $options->discussionsPerPage;
                    case 'online':
                        $location = 'online';
                        $perPage = $options->membersPerPage;
                    case 'search':
                        $location = 'search';
                        $perPage = $options->searchResultsPerPage;
                    case 'login':
                        $location = 'login';
                    default:
                        break;
                }

                if(preg_match('/(page=|page-)(\d+)/', $other, $match)){
                    $page = $match[2];
                }else{
                    $page = 1;
                }

                $other_info = '';
                if(!empty($title) && $location != 'index')
                {
                    if(preg_match('/\./',$title,$match))
                    {
                        $departs = preg_split('/\./', $title);
                        if(isset($id_name) && !empty($id_name) && isset($departs[1]) && !empty($departs[1]))
                        {
                            $other_info .= $id_name.'='.intval($departs[1]);
                            $id_value = intval($departs[1]);
                        }
                    } else if (preg_match('/^\d+$/', $title, $match)){
                        if (isset($id_name) && !empty($id_name)){
                            $other_info .= $id_name.'='.intval($match[0]);
                            $id_value = intval($match[0]);
                        }
                    }
                }
                if (!empty($page)){
                    if(!empty($other_info)){
                        $other_info .= '&';
                    }
                    $other_info .= 'page='.$page.'&perpage='.(intval($perPage) ? intval($perPage) : 20);
                }
            }
        }
        else
        {
            $location = 'index';
        }
        return $baseUrl.'location='.$location.(!empty($other_info) ? '&'.$other_info : '');
    }

    public static function handle_forum_info($forum_info){
        $result = array();
        if (empty($forum_info)){
            return $result;
        }
        $infos = preg_split('/\s*?\n\s*?/', $forum_info);
        foreach ($infos as $info){
            $value = preg_split('/\s*:\s*/', $info, 2);
            $result[trim($value[0])] = isset($value[1]) ? $value[1] : '';
        }
        return $result;
    }

    public static function tt_get_board_url(){
        $request = new Zend_Controller_Request_Http();
        $getScheme = $request->getScheme();
        $getHttpHost = $request->getHttpHost();
        $getBasePath = $request->getBasePath();
        if (!empty($getScheme) && !empty($getHttpHost)){
            return $getScheme . '://' . $getHttpHost . $getBasePath;
        } else {
            return XenForo_Application::get('options')->boardUrl;
        }
    }
}