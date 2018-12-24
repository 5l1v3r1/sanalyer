<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseWrCommon');


Class MbqWrCommon extends MbqBaseWrCommon {

    public function __construct() {
    }
    public function setApiKey($apiKey)
    {
        $optionModel = XenForo_Model::create('XenForo_Model_Option');
        $input['options']['tp_push_key'] = $apiKey;
        $result_text = $optionModel->updateOptions($input['options']);
        $tp_push_key = $optionModel->getOptionById('tp_push_key');
        if ($apiKey == $tp_push_key['option_value']){
            return true;
        }
        return false;
    }
    public function SetSmartbannerInfo($smartbannerInfo)
    {
        $optionModel = XenForo_Model::create('XenForo_Model_Option');
        $input['options']['tapatalk_banner_control'] = serialize($smartbannerInfo);
        $input['options']['tapatalk_banner_update'] = time();
        if(isset($smartBannerInfo) && isset($smartBannerInfo['forum_id']) && !empty($smartBannerInfo['forum_id']))
        {
             $input['options']['tapatalk_forum_id'] =  $smartBannerInfo['forum_id'];
        }
        $result_text = $optionModel->updateOptions($input['options']);
        $tapatalk_banner_control = $optionModel->getOptionById('tapatalk_banner_control');
        if (serialize($smartbannerInfo) == $tapatalk_banner_control['option_value']){
            return true;
        }
        return false;
    }
}