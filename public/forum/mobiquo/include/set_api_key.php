<?php

defined('IN_MOBIQUO') or exit;

function set_api_key_func(){
    $code = trim($_REQUEST['code']);
    $key = trim($_REQUEST['key']);
    $format = isset($_REQUEST['format']) ? trim($_REQUEST['format']) : '';
    $connection = new classTTConnection();
    $response = $connection->actionVerification($code,'set_api_key');
    $optionModel = XenForo_Model::create('XenForo_Model_Option');
    if($response === true)
    {
        $input['options']['tp_push_key'] = $key;
        $result_text = $optionModel->updateOptions($input['options']);
    }
    $result = false;

    $tp_push_key = $optionModel->getOptionById('tp_push_key');
    if ($key == $tp_push_key['option_value']){
        $result = true;
        $result_text = '';
    }

    $data = array(
        'result' => $result,
        'result_text' => $result_text,
    );
    
    $response = ($format == 'json') ? json_encode($data) : serialize($data);
    echo $response;
    exit;
}