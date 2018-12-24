<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseRdCommon');

Class MbqRdCommon extends MbqBaseRdCommon {

    public function __construct() {
    }

    public function getApiKey()
    {
       $optionModel = XenForo_Model::create('XenForo_Model_Option');
       $tp_push_key = $optionModel->getOptionById('tp_push_key');
       return $tp_push_key['option_value'];
    }
    public function getForumUrl()
    {
        return TT_get_board_url();
    }
    public function getCheckSpam()
    {
        return true;
    }
    public function get_id_by_url($url)
    {
        $bridge = Tapatalk_Bridge::getInstance();
        $visitor = XenForo_Visitor::getInstance();

        $url = str_ireplace("index.php?", "", $url);

        $request = new Zend_Controller_Request_Http($url);
        $request->setBasePath($bridge->_request->getBasePath());

        $routeMatch = $bridge->getDependencies()->route($request);

        switch($routeMatch->getControllerName()){
            case "XenForo_ControllerPublic_Thread":
                if($request->getParam('thread_id'))
                {
                    $oMbqRdEtForumTopic = MbqMain::$oClk->newObj('MbqRdEtForumTopic');
                    return $oMbqRdEtForumTopic->initOMbqEtForumTopic($request->getParam('thread_id'), array('case'=>'byTopicId'));
                }
                break;
            case "XenForo_ControllerPublic_Forum":
                if($request->getParam('node_id'))
                {
                    $oMbqRdEtForum = MbqMain::$oClk->newObj('MbqRdEtForum');
                    return $oMbqRdEtForum->initOMbqEtForum($request->getParam('node_id'), array('case'=>'byForumId'));
                }
                break;
            case "XenForo_ControllerPublic_Post":
                if($request->getParam('post_id'))
                {
                    $oMbqRdEtForumPost = MbqMain::$oClk->newObj('MbqRdEtForumPost');
                    return $oMbqRdEtForumPost->initOMbqEtForumPost($request->getParam('post_id'), array('case'=>'byPostId'));
                }
                break;
            case "XenForo_ControllerPublic_Conversation":
                if($request->getParam('conversation_id'))
                {
                    $oMbqRdEtPc = MbqMain::$oClk->newObj('MbqRdEtPc');
                    return $oMbqRdEtPc->initOMbqEtPc($request->getParam('conversation_id'), array('case'=>'byConvId'));
                }
                break;
        }

        return TT_GetPhraseString('dark_unknown_url');
    }
    public function getPushSlug()
    {
        $options = XenForo_Application::get('options');
        $slug = $options->push_slug;
        if(isset($slug) && !empty($slug))
        {
            return @unserialize($slug);
        }
        return null;
    }
    public function getSmartbannerInfo()
    {
        $options = XenForo_Application::get('options');
        $tapatalkBannerControl = $options->tapatalk_banner_control;
        if(isset($tapatalkBannerControl))
        {
            return unserialize($tapatalkBannerControl);
        }
        return null;
    }
    public function getTapatalkForumId()
    {
        $options = XenForo_Application::get('options');
        if(isset($options->tapatalk_forum_id) && !empty($options->tapatalk_forum_id))
        {
            return $options->tapatalk_forum_id;
        }
        return null;
    }
}