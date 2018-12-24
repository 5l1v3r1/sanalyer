<?php
define('MBQ_PROTOCOL','web');
global $tapatalk_cmd;
$tapatalk_cmd = 'update';
define('IN_MOBIQUO', true);
define('TT_ROOT', getcwd() . DIRECTORY_SEPARATOR);

require_once('mobiquoCommon.php');

MbqMain::init(); // frame init
MbqMain::input(); // handle input data
require_once(MBQ_PATH.'IncludeBeforeMbqAppEnv.php');
MbqMain::initAppEnv(); // application environment init
MbqMain::$oMbqConfig->calCfg();
@ ob_start();
require_once(MBQ_PATH . '/logger.php');
require_once(MBQ_FRAME_PATH . '/MbqBaseStatus.php');
class MbqStatus extends MbqBaseStatus
{

    public function GetLoggedUserName()
    {
        if(MbqMain::$oCurMbqEtUser != null)
        {
            return MbqMain::$oCurMbqEtUser->loginName->oriValue;
        }
        return 'anonymous';
    }
    protected function GetMobiquoFileSytemDir()
    {
        return TT_ROOT;
    }
    protected function GetMobiquoDir()
    {
        $optionModel = XenForo_Model::create('XenForo_Model_Option');
        $tp_directory = $optionModel->getOptionById('tp_directory');
        return $tp_directory['option_value'];
    }
    protected function GetApiKey()
    {
        $optionModel = XenForo_Model::create('XenForo_Model_Option');
        $tp_push_key = $optionModel->getOptionById('tp_push_key');
        return md5($tp_push_key['option_value']);
    }
    protected function GetForumUrl()
    {
        return TT_get_board_url();
    }


    protected function GetPushSlug()
    {
        if (file_exists(MBQ_PATH . '/push/TapatalkPush.php')) {
            require_once(MBQ_PATH . '/push/TapatalkPush.php');
            $tapatalkPush = new \TapatalkPush();
            return $tapatalkPush->get_push_slug();
        }
        return null;
    }

    protected function ResetPushSlug()
    {
        $optionModel = XenForo_Model::create('XenForo_Model_Option');
        $optionModel->updateOptions(array('push_slug' => 0));
        return true;
    }

    protected function GetBYOInfo()
    {
        $options = XenForo_Application::get('options');
        $app_banner_enable =  $options->full_banner;
        $google_indexing_enabled = $options->google_indexing_enabled;
        $facebook_indexing_enabled = $options->facebook_indexing_enabled;
        $twitter_indexing_enabled = $options->twitter_indexing_enabled;
        $TT_update = isset($options->tapatalk_banner_update) ? $options->tapatalk_banner_update : 0;
        $TT_bannerControlData = isset($options->tapatalk_banner_control) ? unserialize($options->tapatalk_banner_control) : array();
        if (file_exists(MBQ_3RD_LIB_PATH .'/classTTConnection.php')){
             include_once(MBQ_3RD_LIB_PATH .'/classTTConnection.php');
        }
        $TT_connection = new classTTConnection();
        $TT_connection->calcSwitchOptions($TT_bannerControlData, $app_banner_enable, $google_indexing_enabled, $facebook_indexing_enabled, $twitter_indexing_enabled);
        $TT_bannerControlData['update'] = $TT_update;
        $TT_bannerControlData['banner_enable'] = $app_banner_enable;
        $TT_bannerControlData['google_enable'] = $google_indexing_enabled;
        $TT_bannerControlData['facebook_enable'] = $facebook_indexing_enabled;
        $TT_bannerControlData['twitter_enable'] = $twitter_indexing_enabled;
        return $TT_bannerControlData;
    }
  
    protected function GetOtherPlugins()
    {
        $addOnModel = XenForo_Model::create('XenForo_Model_AddOn');
        $addOns = $addOnModel->getAllAddOns();
        $result = array();
        foreach ($addOns as $addOn) {
            $result[] = array('name'=>$addOn['title'], 'version'=>$addOn['version_string']);
        }
        return $result;
    }

}
$mbqStatus = new MbqStatus();

