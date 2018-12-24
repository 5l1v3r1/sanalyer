<?php

defined('IN_MOBIQUO') or exit;

function reset_push_slug_func(){
    $code = trim($_REQUEST['code']);
    $format = isset($_REQUEST['format']) ? trim($_REQUEST['format']) : '';
    $connection = new classTTConnection();
    //$response = $connection->actionVerification($code,'reset_push_slug');
    $optionModel = XenForo_Model::create('XenForo_Model_Option');
    //$result = false;
    //$result_text = '';
    //if($response === true)
    //{
        $result_text = $optionModel->updateOptions(array('push_slug' => 0));
        $result = true;
    //}
 
    $data = array(
        'result' => $result,
        'result_text' => $result_text,
    );
    
    $response = ($format == 'json') ? json_encode($data) : serialize($data);
    echo $response;
    exit;
}