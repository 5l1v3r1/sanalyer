<?php

defined('IN_MOBIQUO') or exit;

function get_contact_func($xmlrpc_params)
{
    $params = php_xmlrpc_decode($xmlrpc_params);
    $bridge = Tapatalk_Bridge::getInstance();
    
    $data = $bridge->_input->filterExternal(array(
            'user_id' => XenForo_Input::UINT,
            'code' => XenForo_Input::STRING,
    ), $params);
    $code = isset($_POST['code']) ? trim($_POST['code']) : $data['code'];
    $userId = isset($_POST['user_id']) ? trim($_POST['user_id']) : $data['user_id'];
    $format = isset($_POST['format']) ? trim($_POST['format']) : 'json';

    $options = XenForo_Application::get('options');
    $mobi_api_key = $options->tp_push_key;
    if (!preg_match('/[A-Z0-9]{32}/', $mobi_api_key) || empty($code)){
        $result = array(
            'result' => new xmlrpcval(false, 'boolean'),
        );
        return new xmlrpcresp(new xmlrpcval($result, 'struct'));
    }

    $connection = new classTTConnection();
    $response = $connection->actionVerification($code, 'get_contact');

    if ($response !== true){
        $result = array(
            'result' => new xmlrpcval(false, 'boolean'),
        );
        return new xmlrpcresp(new xmlrpcval($result, 'struct'));
    }

    try
    {
        $visitor = XenForo_Visitor::getInstance();
        $userModel = $bridge->getUserModel();

        $fetchOptions =array(
            'join' =>  XenForo_Model_User::FETCH_USER_OPTION,
        );
        $userIds = explode(',',$userId);
    
        $usersResult = array();
        $languages = (XenForo_Application::isRegistered('languages')
            ? XenForo_Application::get('languages')
            : XenForo_Model::create('XenForo_Model_Language')->getAllLanguagesForCache()
        );
        foreach($userIds as $id)
        {
            $user = $userModel->getUserById($id, $fetchOptions);
            if(isset($user['user_id']))
            {
                $usersResult[] = array(
                    'user_id' => $user['user_id'],
                    'display_name' => $user['username'],
                    'enc_email' => tt_encrypt(trim($user['email']), $mobi_api_key),
                    'allow_email' => $user['receive_admin_email'],
                    'language' => $languages[$user['language_id']]['language_code'],
                    'activated' => $user['user_state'] == 'valid',
                );
            }
        
        }
        
        $result = array( 
            'result' => true,
            'result_text' => '',
            'users' => $usersResult);
    }
    catch(Exception $e)
    {
         $result = array( 
            'result' => true,
            'new_encrypt' => true,
            'result_text' => $e->getMessage(),);
    }
    $response = ($format == 'json') ? json_encode($result) : serialize($result);
    echo $response;
    exit;

}
