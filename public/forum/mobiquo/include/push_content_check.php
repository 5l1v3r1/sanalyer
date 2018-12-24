<?php

defined('IN_MOBIQUO') or exit;

function push_content_check_func(){
    $code = trim($_POST['code']);
    $format = trim($_POST['format']);
    $push_data = unserialize($_POST['data']);

    $connection = new classTTConnection();
    $response = $connection->actionVerification($code,'push_content_check');
    if ($response !== TRUE){
        $data = array(
            'result' => false,
            'result_text' => $response,
        );
        @ob_end_clean();
        echo ($format == 'json') ? json_encode($data) : serialize($data);
        exit;
    }
    $optionModel = XenForo_Model::create('XenForo_Model_Option');
    $tp_push_key = $optionModel->getOptionById('tp_push_key');
    
    if(!isset($tp_push_key['option_value']) || !isset($push_data['key']) || $tp_push_key['option_value'] != $push_data['key']){
        $data = array(
            'result' => false,
            'result_text' => 'incorrect api key',
        );
        @ob_end_clean();
        echo ($format == 'json') ? json_encode($data) : serialize($data);
        exit;
    }

    if (!isset($push_data['dateline']) || time() - intval($push_data['dateline']) > 86400){
        $data = array(
            'result' => false,
            'result_text' => 'time out',
        );
        @ob_end_clean();
        echo ($format == 'json') ? json_encode($data) : serialize($data);
        exit;
    }
    $result = false;
    switch ($push_data['type']){
        case 'newtopic':
        case 'sub':
        case 'quote':
        case 'tag':
            $bridge = Tapatalk_Bridge::getInstance();
            $postModel = $bridge->getPostModel();
            
            $post=$postModel->getPostById($push_data['subid']);
            if($post['thread_id'] == $push_data['id'] && ($post['user_id'] == $push_data['authorid'] || $post['username'] == $push_data['author']))
            {
                $result = true;
            } 
            break;
        case 'conv':
            $bridge = Tapatalk_Bridge::getInstance();
            $conversationModel = $bridge->getConversationModel();
            $recipients = $conversationModel->getConversationRecipients($push_data['id']);
            foreach($recipients as $recipient)
            {
                if($recipient['user_id'] == $push_data['authorid'] || $recipient['username'] == $push_data['author'])
                {
                    $result = true;
                }
            }
            break;
    }
  
    $data = array(
        'result' => $result,
        'result_text' => '',
    );
    @ob_end_clean();
    echo ($format == 'json') ? json_encode($data) : serialize($data);
    exit;
}