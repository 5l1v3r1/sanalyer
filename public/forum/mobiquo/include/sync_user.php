<?php

defined('IN_MOBIQUO') or exit;

function sync_user_func(){
    $code = trim($_POST['code']);
    $start = (isset($_POST['start']) && is_numeric($_POST['start']) && intval($_POST['start']) > 0) ? intval($_POST['start']) : 0;
    $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && intval($_POST['limit']) > 0) ? intval($_POST['limit']) : 1000;
    $format = trim($_POST['format']);

    $options = XenForo_Application::get('options');
    $mobi_api_key = $options->tp_push_key;
    if (!preg_match('/[A-Z0-9]{32}/', $mobi_api_key)){
        $result = array(
            'result' => new xmlrpcval(false, 'boolean'),
            'new_encrypt' => true,
            'result_text' => new xmlrpcval('Invalid API Key', 'string'),
        );
        return new xmlrpcresp(new xmlrpcval($result, 'struct'));
    }
    $connection = new classTTConnection();
    $response = $connection->actionVerification($code,'sync_user');

    if($response === true){
        try {


            // Get users...
            $users = array();

            $userModel = XenForo_Model::create('XenForo_Model_User');
            $languages = (XenForo_Application::isRegistered('languages')
                    ? XenForo_Application::get('languages')
                    : XenForo_Model::create('XenForo_Model_Language')->getAllLanguagesForCache()
                );
            $sql = "SELECT user.user_id, user.username, user.email, user_option.receive_admin_email, user.language_id, user.message_count, user.register_date, user.last_activity
                    FROM xf_user AS user LEFT JOIN xf_user_option AS user_option ON user.user_id = user_option.user_id 
                    WHERE user.is_banned = 0 and user.user_state = 'valid' and user.user_id > ? ORDER BY user.user_id ASC LIMIT ?";

            $result = XenForo_Application::getDb()->query($sql, array($start, $limit), Zend_Db::FETCH_ASSOC);
            while ($row = $result->fetch())
            {
                $user['uid'] = $row['user_id'];
                $user['username'] = $row['username'];
                $user['encrypt_email'] = base64_encode(tt_encrypt(trim($row['email']), $mobi_api_key));
                $user['allow_email'] = $row['receive_admin_email'];
                $user['language'] = $languages[$row['language_id']]['language_code'];
                $user['reg_date'] = $row['register_date'];
                $user['post_num'] = $row['message_count'];
                $user['last_active'] = $row['last_activity'];
                $users[] = $user;
            }

            $data = array(
            'result' => true,
            'new_encrypt' => true,
            'users' => $users,
            );
        }catch (Exception $e){
            $data = array(
                'result' => false,
                'new_encrypt' => true,
                'result_text' => $e->getMessage(),
            );
        }
    }
    else
    {
        $data = array(
            'result' => false,
            'new_encrypt' => true,
            'result_text' => $response,
        );
    }
    $response = ($format == 'json') ? json_encode($data) : serialize($data);
    echo $response;
    exit;
}