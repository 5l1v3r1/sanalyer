<?php

define('MBQ_PUSH_BLOCK_TIME', 60);    /* push block time(minutes) */
if(!class_exists('TapatalkBasePush'))
{
    require_once(dirname(__FILE__) . '/../mbqFrame/basePush/TapatalkBasePush.php');
}
require_once dirname(__FILE__) . '/../helper.php';

/**
 * push class

 */
Class TapatalkPush extends TapatalkBasePush {

    //init
    public function __construct() {
        parent::__construct($this);

    }
    function get_push_slug()
    {
        $options = XenForo_Application::get('options');
        $slug = $options->push_slug;
        if(isset($slug))
        {
            return $slug;
        }
        return null;
    }
    function set_push_slug($slug = null)
    {
        $optionModel = XenForo_Model::create('XenForo_Model_Option');
        $optionModel->updateOptions(array('push_slug' => $slug));
        return true;
    }
    function doAfterAppLogin($userId)
    {
        $bridge = Tapatalk_Bridge::getInstance();
        $conversationModel = $bridge->getConversationModel();
        $userModel = $bridge->getUserModel();
        $options = XenForo_Application::get('options');

        XenForo_Model_Ip::log($userId, 'user', $userId, 'login');
        $tapatalk_user_writer = XenForo_DataWriter::create('Tapatalk_DataWriter_TapatalkUser');
        $tapatalk_user_model = $tapatalk_user_writer->getTapatalkUserModel();
        $existing_record = $tapatalk_user_model->getTapatalkUserById($userId);
        if(empty($existing_record))
        {
            $tapatalk_user_writer->set('userid',$userId);
             $tapatalk_user_writer->preSave();
            $tapatalk_user_writer->save();
        }
        else
        {
            $tapatalk_user_writer->setExistingData($existing_record);
            $tapatalk_user_writer->save();
        }

    }
    public function processPush($action, $post, $thread)
    {
        try
        {
            if (!$post['post_id'] || !$thread['thread_id'] || (!function_exists('curl_init') && !ini_get('allow_url_fopen')))
            {
                return false;
            }

            $push_datas = self::analysisPushAction($action, $post, $thread);

            foreach ($push_datas as $push_data){
                self::do_push_request($push_data);
            }
        }
        catch(Exception $ex){}
        return true;
    }
    public function doPushLike($data)
    {

    }
    public function doPushDelete($action, $ids)
    {
        $boardurl = self::tt_get_board_url();
        $push_data = array(
                    'url'  => $boardurl,
                    'userid'    => '',
                    'type'      => $action,
                    'id'        => implode(',',$ids),
                    'from_app'  => self::getIsFromApp(),
                    'dateline'  => time(),
            );
        $options = XenForo_Application::get('options');
        if(isset($options->tp_push_key) && !empty($options->tp_push_key)){
            $push_data['key'] = $options->tp_push_key;
        }
        self::do_push_request($push_data);
    }
    public function doPushConv($data, $values)
    {
        if (isset($data['recepients']) && !empty($data['recepients']) && $data['title'] && (function_exists('curl_init') || ini_get('allow_url_fopen')))
        {
            $visitor = XenForo_Visitor::getInstance();
            $options = XenForo_Application::get('options');
            if(isset($options->tapatalk_push_notifications) && $options->tapatalk_push_notifications == 1){
                $convModel = XenForo_Model::create('XenForo_Model_Conversation');
                $message = $convModel->getConversationMessageById($data['last_message_id']);
                $myOptions = array(
                    'states' => array(
                        'returnHtml' => true,
                    ),
                );
                $content = self::cleanPost($message['message'], $myOptions);
            }

            $tapatalkUser_model = XenForo_Model::create('Tapatalk_Model_TapatalkUser');
            $spcTpUsers = $tapatalkUser_model->getAllPmOpenTapatalkUsersInArray($data['recepients']);
            $title = Tapatalk_Push_Push::tt_push_clean($data['title']);
            $author = Tapatalk_Push_Push::tt_push_clean($data['conv_sender_name']);
            $boardurl = self::tt_get_board_url();
            if (empty($spcTpUsers)){
                return;
            }
            $tpu_ids = '';
            foreach($spcTpUsers as $tpu_id => $tapatalk_user)
            {
                $tpu_ids .= $tpu_id . ',';
            }
            $tpu_ids = substr($tpu_ids, 0, strlen($tpu_ids)-1);

            $inviteFlag = 0;
            // add a flag to indicate it's an invite
            if(isset($data['action']) && $data['action'] == 'InviteInsert' ||
                isset($data['action']) && $data['action'] == 'invite_participant')
            {
                $inviteFlag = 1;
            }

            $ttp_data = array(
                    'url'  => $boardurl,
                    'userid'    => $tpu_ids,
                    'type'      => 'conv',
                    'id'        => $data['conversation_id'],
                    'subid'     => $data['reply_count']+1,
                    'mid'       => $data['last_message_id'],
                    'title'     => $title,
                    'author'    => $author,
                    'authorid'  => $data['conv_sender_id'],
                    'author_postcount' => $visitor['message_count'],
                    'author_ip' => self::getClientIp(),
                    'author_ua' => self::getClienUserAgent(),
                    'author_type' => self::get_usertype_by_item('', $visitor['display_style_group_id'], $visitor['is_banned'], $visitor['user_state']),
                    'from_app'  => self::getIsFromApp(),
                    'dateline'  => time(),
                    'invite'    => $inviteFlag,
            );
            if (isset($content) && !empty($content)){
                $ttp_data['content'] = $content;
            }

            $options = XenForo_Application::get('options');
            if(isset($options->tp_push_key) && !empty($options->tp_push_key)){
                $ttp_data['key'] = $options->tp_push_key;
            }

            self::do_push_request($ttp_data);
        }
    }
    
    public function doPushConvInvite($data, $invited_user_ids)
    {
        if (!empty($invited_user_ids) && $data['title'] && (function_exists('curl_init') || ini_get('allow_url_fopen')))
        {
            $visitor = XenForo_Visitor::getInstance();

            $options = XenForo_Application::get('options');
            if(isset($options->tapatalk_push_notifications) && $options->tapatalk_push_notifications == 1){
                $convModel = XenForo_Model::create('XenForo_Model_Conversation');
                $message = $convModel->getConversationMessageById($data['last_message_id']);
                $myOptions = array(
                    'states' => array(
                        'returnHtml' => true,
                    ),
                );
                $content = self::cleanPost($message['message'], $myOptions);
            }

            $tapatalkUser_model = XenForo_Model::create('Tapatalk_Model_TapatalkUser');
            $title = Tapatalk_Push_Push::tt_push_clean($data['title']);
            $author = Tapatalk_Push_Push::tt_push_clean($data['conv_sender_name']);
            $boardurl = self::tt_get_board_url();

            $ttp_data = array(
                    'url'  => $boardurl,
                    'userid'    => $invited_user_ids,
                    'type'      => 'conv',
                    'id'        => $data['conversation_id'],
                    'subid'     => $data['reply_count']+1,
                    'mid'       => $data['last_message_id'],
                    'title'     => $title,
                    'author'    => $author,
                    'authorid'  => $data['conv_sender_id'],
                    'author_postcount' => $visitor['message_count'],
                    'author_ip' => self::getClientIp(),
                    'author_ua' => self::getClienUserAgent(),
                    'author_type' => self::get_usertype_by_item('', $visitor['display_style_group_id'], $visitor['is_banned'], $visitor['user_state']),
                    'from_app'  => self::getIsFromApp(),
                    'dateline'  => time(),
                    'invite'    => 1,
            );
            if (isset($content) && !empty($content)){
                $ttp_data['content'] = $content;
            }

            $options = XenForo_Application::get('options');
            if(isset($options->tp_push_key) && !empty($options->tp_push_key)){
                $ttp_data['key'] = $options->tp_push_key;
            }

            self::do_push_request($ttp_data);
        }
    }
    
    public function doPushNewTopic($data)
    {

    }

    public function doPushReply($data, $excludeUsers = array())
    {
    }

    public function doPushQuote($data, $quotedUsers)
    {

    }
    protected  function doInternalPushThank($p){}

    protected  function doInternalPushReply($p){
        //this is addressed directly in MbqWrEtForumPost to get the real quote code
    }

    protected  function doInternalPushReplyConversation($p){}

    protected  function doInternalPushNewTopic($p){
        $oMbqEtForumTopic = $p['oMbqEtForumTopic'];
        $oMbqRdEtForumTopic = MbqMain::$oClk->newObj('MbqRdEtForumTopic');
        $oMbqEtForumTopic = $oMbqRdEtForumTopic->initOMbqEtForumTopic($oMbqEtForumTopic->topicId->oriValue,array('case'=>'byTopicId'));
        $bridge = Tapatalk_Bridge::getInstance();
        $post = $bridge->getPostModel()->getLastPostInThread($oMbqEtForumTopic->topicId->oriValue);
        self::processPush('AddThread', $post, $oMbqEtForumTopic->mbqBind);
    }

    protected  function doInternalPushNewConversation($p){}

    protected  function doInternalPushNewMessage($p){}

    protected  function doInternalPushLike($p){
        $oMbqEtForumPost = $p['oMbqEtForumPost'];
        $post = $oMbqEtForumPost->mbqBind;
        $thread = $oMbqEtForumPost->oMbqEtForumTopic->mbqBind;
        self::processPush('Like', $post, $thread);
    }

    protected  function doInternalPushNewSubscription($p){
        $oMbqEtForumTopic = $p['oMbqEtForumTopic'];
        $bridge = Tapatalk_Bridge::getInstance();
        $post = $bridge->getPostModel()->getLastPostInThread($oMbqEtForumTopic->topicId->oriValue);
        self::processPush('Watch', $post, $oMbqEtForumTopic->mbqBind);
    }
    protected function doInternalPushDeleteTopic($p)
    {
        self::doPushDelete('deltopic', array($p['oMbqEtForumTopic']->topicId->oriValue));
    }

    protected function doInternalPushDeletePost($p)
    {
        self::doPushDelete('delpost', array($p['oMbqEtForumPost']->postId->oriValue));
    }
    protected static function analysisPushAction($action, $post, $thread){
        $pushData = array();

        $visitor = XenForo_Visitor::getInstance();
        $ttp_data = array('id' => $thread['thread_id']);
        if(isset($post))
        {
            $ttp_data['subid'] = $post['post_id'];
        }
        $ttp_data['subfid'] = $thread['node_id'];
        $ttp_data['title'] = self::tt_push_clean($thread['title']);
        $ttp_data['author_ip'] = self::getClientIp();
        $ttp_data['author_ua'] = self::getClienUserAgent();
        $ttp_data['author_type'] = self::get_usertype_by_item('', $visitor['display_style_group_id'], $visitor['is_banned'], $visitor['user_state']);
        $ttp_data['from_app'] = self::getIsFromApp();
        $ttp_data['dateline'] = time();

        if($action == 'Like' || $action == 'Watch')
        {
            $ttp_data['author'] = self::tt_push_clean($visitor['username']);
            $ttp_data['authorid'] = $visitor['user_id'];
            $ttp_data['author_postcount'] = $visitor['message_count'];
        }
        else
        {
            $ttp_data['author'] = self::tt_push_clean($post['username']);
            $ttp_data['authorid'] = $post['user_id'];
            $ttp_data['author_postcount'] = $visitor['message_count'] + 1;
        }

        $forumModel = XenForo_Model::create('XenForo_Model_Forum');
        $forum = $forumModel->getForumById($thread['node_id']);
        $ttp_data['sub_forum_name'] = self::tt_push_clean($forum['title']);

        $options = XenForo_Application::get('options');
        $ttp_data['url'] = self::tt_get_board_url();
        if(isset($options->tp_push_key) && !empty($options->tp_push_key)){
            $ttp_data['key'] = $options->tp_push_key;
        }

        if($action == 'DeleteTopic')
        {
            $ttp_data['contenttype'] = 'topic';
            $ttp_data['type'] = 'delete';
            $ttp_data['userid'] = '';
            $pushData[] = $ttp_data;
        }
        else if($action == 'DeletePost')
        {
            $ttp_data['contenttype'] = 'post';
            $ttp_data['type'] = 'delete';
            $ttp_data['userid'] = '';
            $pushData[] = $ttp_data;
        }
        else
        {
            if(isset($options->tapatalk_push_notifications) && $options->tapatalk_push_notifications == 1){
                $myOptions = array(
                    'states' => array(
                        'returnHtml' => true,
                ),
                );
                $content = self::cleanPost($post['message'], $myOptions);
                $ttp_data['content'] = $content;
            }
            $data = self::findParticipants($action, $post, $thread);

            $tapatalkUser_model = XenForo_Model::create('Tapatalk_Model_TapatalkUser');

            foreach ($data as $pushAction => $users){
                $ttp_data['type'] = $pushAction;
                if(empty($users))
                {
                    continue;
                }
                unset($users[$visitor['user_id']]);
                $user_ids = $tapatalkUser_model->getTapatalkUsersInArray(array_keys($users));
                if (empty($user_ids)){
                    continue;
                }
                $ttp_data['userid'] = implode(',', array_keys($user_ids));
                $pushData[] = $ttp_data;
            }

            if (empty($pushData)){
                if($action == 'Like')
                {
                    $ttp_data['type'] = 'like';
                    $ttp_data['userid'] = '';
                    $pushData[] = $ttp_data;
                }
                else
                {
                    if($action == 'AddThread')
                    {
                        $ttp_data['type'] = 'newtopic';
                    }
                    else
                    {
                        $ttp_data['type'] = 'sub';
                    }
                    $ttp_data['userid'] = '';
                    $pushData[] = $ttp_data;

                }
            }
        }

        return $pushData;
    }
    protected static function tt_push_clean($str)
    {
        $str = strip_tags($str);
        $str = trim($str);
        return html_entity_decode($str, ENT_QUOTES, 'UTF-8');
    }
    protected static function get_usertype_by_item($userid = '', $groupid = '', $is_banned = false, $state = '')
    {
        if($is_banned)
            return 'banned';
        if($state == 'email_confirm' || $state == 'email_confirm_edit' || $state == 'Email invalid (bounced)')
            return 'inactive';
        if($state == 'moderated')
            return 'unapproved';
        if (empty($groupid))
        {
            if(!empty($userid))
            {
                $userModel = XenForo_Model::create('XenForo_Model_User');
                $user = $userModel->getUserById($userid);
                if($user['is_banned'])
                    return 'banned';
                $groupid = $user['display_style_group_id'];
            }
            else
                return ' ';
        }

        if($groupid == 3)
            return 'admin';
        else if($groupid == 4)
            return 'mod';
        else if($groupid == 2)
            return 'normal';
        else if($groupid == 1)
            return ' ';
    }

    protected static function tt_get_board_url(){
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
    protected static function cleanPost($post, $extraStates=array())
    {
        if (!isset($extraStates['states']['returnHtml']))
            $extraStates['states']['returnHtml'] = false;

        if ($extraStates['states']['returnHtml'])
        {
            $post = str_replace("&", '&amp;', $post);
            $post = str_replace("<", '&lt;', $post);
            $post = str_replace(">", '&gt;', $post);
            $post = str_replace("\r", '', $post);
            $post = str_replace("\n", '<br />', $post);
        }

        if(!$extraStates)
            $extraStates = array('states' => array());

        // replace code like content with quote
        //      $post = preg_replace('/\[(CODE|PHP|HTML)\](.*?)\[\/\1\]/si','[CODE]$2[/CODE]',$post);

        $post = self::processListTag($post);
        $bbCodeFormatter = new Tapatalk_BbCode_Formatter_Tapatalk((boolean)$extraStates['states']['returnHtml']);
        if (version_compare(XenForo_Application::$version, '1.2.0') >= 0){
            $bbCodeParser = XenForo_BbCode_Parser::create($bbCodeFormatter);
        }else{
            $bbCodeParser = new XenForo_BbCode_Parser($bbCodeFormatter);
        }
        $post = $bbCodeParser->render($post, $extraStates['states']);
        $post = trim($post);


        $options = XenForo_Application::get('options');
        $custom_replacement = $options->tapatalk_custom_replacement;
        if(!empty($custom_replacement))
        {
            $replace_arr = explode("\n", $custom_replacement);
            foreach ($replace_arr as $replace)
            {
                preg_match('/^\s*(\'|")((\#|\/|\!).+\3[ismexuADUX]*?)\1\s*,\s*(\'|")(.*?)\4\s*$/', $replace,$matches);
                if(count($matches) == 6)
                {
                    $temp_post = $post;
                    $post = @preg_replace($matches[2], $matches[5], $post);
                    if(empty($post))
                    {
                        $post = $temp_post;
                    }
                }
            }
        }
        return $post;
    }
    protected static function processListTag($message)
    {
        $contents = preg_split('#(\[LIST=[^\]]*?\]|\[/?LIST\])#siU', $message, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        $result = '';
        $status = 'out';
        foreach($contents as $content)
        {
            if ($status == 'out')
            {
                if ($content == '[LIST]')
                {
                    $status = 'inlist';
                } elseif (strpos($content, '[LIST=') !== false)
                {
                    $status = 'inorder';
                } else {
                    $result .= $content;
                }
            } elseif ($status == 'inlist')
            {
                if ($content == '[/LIST]')
                {
                    $status = 'out';
                } else
                {
                    $result .= str_replace('[*]', '  * ', ltrim($content));
                }
            } elseif ($status == 'inorder')
            {
                if ($content == '[/LIST]')
                {
                    $status = 'out';
                } else
                {
                    $index = 1;
                    $result .= preg_replace_callback('/\[\*\]/s',
                        'TapatalkPush::matchCount',
                    ltrim($content));
                }
            }
        }
        return $result;
    }
    protected static function matchCount($matches){
        static $index = 1;
        return '  '.$index++.'. ';
    }
    protected static function findParticipants($action, $post, $thread){
        $participants = array();
        $participants['tag'] = array();
        $participants['quote'] = array();
        $participants['sub'] = array();
        $participants['like'] = array();
        $participants['newtopic'] = array();
        $participants['newsub'] = array();
        if ($action == 'AddReply' || $action == 'AddThread'){
            //handle tag
            if (file_exists(SCRIPT_ROOT.'library/XenForo/Model/UserTagging.php')){
                $taggingModel = XenForo_Model::create('XenForo_Model_UserTagging');
                $taggedUsers = $taggingModel->getTaggedUsersInMessage(
                $post['message'], $newMessage, 'text'
                );
                if (!empty($taggedUsers)){
                    foreach($taggedUsers as $taggedUser)
                    {
                        if(self::can_view_post($post['post_id'], $taggedUser['user_id']))
                        {
                            $participants['tag'][$taggedUser['user_id']] = $taggedUser;
                        }
                    }
                }
            }

            //handle quote
            if (preg_match_all('/\[quote=("|\'|)([^,]+),\s*post:\s*(\d+?).*\\1\]/siU', $post['message'], $quotes)){
                $postModel = XenForo_Model::create('XenForo_Model_Post');
                if (version_compare(XenForo_Application::$version, '1.2.0') >= 0){
                    $fetchOptions = array(
                     'join' => XenForo_Model_Post::FETCH_USER_OPTIONS
                    | XenForo_Model_Post::FETCH_USER_PROFILE
                    | XenForo_Model_Post::FETCH_THREAD
                    | XenForo_Model_Post::FETCH_FORUM
                    | XenForo_Model_Post::FETCH_NODE_PERMS
                    );
                }else{
                    $fetchOptions = array(
                     'join' => XenForo_Model_Post::FETCH_USER_OPTIONS
                    | XenForo_Model_Post::FETCH_USER_PROFILE
                    | XenForo_Model_Post::FETCH_THREAD
                    | XenForo_Model_Post::FETCH_FORUM
                    );
                }
                $quotedPosts = $postModel->getPostsByIds(array_unique($quotes[3]), $fetchOptions);
                $userModel = XenForo_Model::create('XenForo_Model_User');

                foreach ($quotedPosts AS $quotedPostId => $quotedPost)
                {
                    if (!isset($quotedUsers[$quotedPost['user_id']]) && $quotedPost['user_id'] && $quotedPost['user_id'] != $post['user_id'])
                    {
                        $user = $userModel->getUserById($quotedPost['user_id']);
                        if(!isset($participants['tag'][$user['user_id']]))
                        {
                            if(self::can_view_post($post['post_id'], $user['user_id']))
                            {
                                $participants['quote'][$user['user_id']] = $user;
                            }
                        }
                    }
                }
            }
        }

        if ($action == 'AddReply'){
            //handle sub
            $threadWatchModel = XenForo_Model::create('XenForo_Model_ThreadWatch');
            $users = $threadWatchModel->getUsersWatchingThread($thread['thread_id'], $thread['node_id']);
            if (!empty($users)){
                foreach($users as $user)
                {
                    if(!isset($participants['tag'][$user['user_id']]) && !isset($participants['quote'][$user['user_id']]))
                    {
                        $participants['sub'][$user['user_id']] = $user;
                    }
                }
            }
        }

        //handle like
        if ($action == 'Like'){
            $userModel = XenForo_Model::create('XenForo_Model_User');
            $user = $userModel->getUserById($post['user_id']);
            $participants['like'][$user['user_id']] = $user;
        }

        //handle new topic
        if ($action == 'AddThread'){
            if (file_exists(SCRIPT_ROOT.'library/XenForo/Model/ForumWatch.php')){
                $forumWatchModel = XenForo_Model::create('XenForo_Model_ForumWatch');
                $users = $forumWatchModel->getUsersWatchingForum($thread['node_id'], $thread['thread_id']);
                if (!empty($users)){
                    foreach($users as $user)
                    {
                        $participants['newtopic'][$user['user_id']] = $user;
                    }
                }
            }
        }

        //handle subscrib topic
        if ($action == 'Watch'){
            $userModel = XenForo_Model::create('XenForo_Model_User');
            $user = $userModel->getUserById($thread['user_id']);
            $participants['newsub'][$user['user_id']] = $user;
        }
        return $participants;
    }
    protected static function can_view_post($post_id, $user_id)
    {
        $userModel = XenForo_Model::create('XenForo_Model_User');
        $user = $userModel->getUserById($user_id);
        if ($user)
        {
            $user = $userModel->prepareUser($user);

            $postModel = XenForo_Model::create('XenForo_Model_Post');
            $post = $postModel->getPostById($post_id);
            if ($post)
            {
                $thread_id = $post['thread_id'];
                if ($thread_id)
                {
                    $thread = XenForo_Model::create('XenForo_Model_Thread')->getThreadById($thread_id);
                    if ($thread)
                    {
                        $forum_id = $thread['node_id'];
                        if ($forum_id)
                        {
                            $forumModel = XenForo_Model::create('XenForo_Model_Forum');
                            $forum = $forumModel->getForumById($forum_id, array(
                                'permissionCombinationId' => $user['permission_combination_id']
                            ));
                            if ($forum)
                            {
                                $permissions = XenForo_Permission::unserializePermissions($forum['node_permission_cache']);
                                if ($postModel->canViewPost($post, $thread, $forum, $null, $permissions, $user))
                                {
                                    return true;
                                }
                            }
                        }
                    }
                }
            }
        }

        return false;
    }
}

