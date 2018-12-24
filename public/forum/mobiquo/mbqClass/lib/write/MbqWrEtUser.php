<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseWrEtUser');

/**
 * user write class
 */
Class MbqWrEtUser extends MbqBaseWrEtUser {

    public function __construct() {
    }
    /**
     * register user
     */
	public function registerUser($username, $password, $email, $verified, $custom_register_fields, $profile, &$errors) {

        $bridge = Tapatalk_Bridge::getInstance();

        //Xenforo Validate fields
        $v_datas = array(
            'username' => array(
                'name' => 'username',
                'value' => $username,
        ),
            'email' => array(
                'name' => 'email',
                'value' => $email,
        ),
        );
        $options = array(XenForo_DataWriter_User::OPTION_ADMIN_EDIT => true);
        foreach($v_datas as $field_name => $v_data)
        {
            $v_data = array_merge(array('existingDataKey' => 0), $v_data);
            $vwriter = XenForo_DataWriter::create('XenForo_DataWriter_User');
            if (!empty($v_data['existingDataKey']) || $v_data['existingDataKey'] === '0')
            {
                $vwriter->setExistingData($v_data['existingDataKey']);
            }

            foreach ($options AS $key => $value)
            {
                $vwriter->setOption($key, $value);
            }
            $vwriter->set($v_data['name'], $v_data['value']);

            $error = $vwriter->getErrors();
            if (!empty($error)){
                if (is_array($error)){
                    foreach ($error as $key => $value){
                        if ($value instanceof XenForo_Phrase){
                            $errors[] = $value->render();
                        }else{
                            $errors[] = $error;
                        }
                    }
                }else{
                    if ($error instanceof XenForo_Phrase){
                        $errors[] = $error->render();
                    }else{
                        $errors[] = $error;
                    }
                }
            }
        }
        //apply profile
        $xf_options = XenForo_Application::get('options');
        if(isset($custom_register_fields['birthday']) && !empty($custom_register_fields['birthday'])){
            $birthday = preg_split('/-/', $custom_register_fields['birthday']);
            unset($custom_register_fields['birthday']);
        }

        $requireDob = $xf_options->get('registrationSetup', 'requireDob');
        $requireLocation =  $xf_options->get('registrationSetup', 'requireLocation');
        if(isset($custom_register_fields['location']) && !empty($custom_register_fields['location'])){
            $location = $custom_register_fields['location'];
        }else if (isset($profile['location']) && !empty($profile['location'])){
            $location = $custom_register_fields['location'];
        }
        if(isset($location))
        {
            $custom_register_fields['location'] = $location;
        }

        //user state
        $xf_reg_option = $xf_options->registrationSetup;
        $email_confirmation = $xf_reg_option['emailConfirmation'];
        $moderation = $xf_reg_option['moderation'];
        if(empty($email_confirmation) && empty($moderation)){
            $user_state = 'valid';
        }else if(!empty($email_confirmation) && empty($moderation)){
            $user_state = $verified ? 'valid' : 'email_confirm';
        }else if(empty($email_confirmation) && !empty($moderation)){
            $user_state = ($verified && isset($xf_options->auto_approval_tp_user) && $xf_options->auto_approval_tp_user) ? 'valid' : 'moderated';
        }else{
            $user_state = $verified ? ((isset($xf_options->auto_approval_tp_user) && $xf_options->auto_approval_tp_user == true) ? 'valid' : 'moderated') : 'email_confirm';
        }

        $gender='';
        if(isset($profile['gender']) && !empty($profile['gender'])){
            if($profile['gender']=='male'||$profile['gender']=='female'){
                $gender=$profile['gender'];
            }
        }

        $userGroupModel = $bridge->getUserGroupModel();
        $userGroups = $userGroupModel->getAllUserGroups();
        $tapatalk_reg_ug = $xf_options->tapatalk_reg_ug;
        if (!array_key_exists($tapatalk_reg_ug, $userGroups)){
            $tapatalk_reg_ug = 0;
        }

        //indicate if it can use gravatar
        $gravatar = false;
        if(isset($profile['avatar_url'])&& !empty($profile['avatar_url'])){
            if(preg_match('/gravatar\.com\/avatar/', $profile['avatar_url'])){
                if ($xf_options->gravatarEnable){
                    $gravatar = true;
                }
            }
        }

        $data = array(
        'username' => $username,
        'email' => $email,
        'user_group_id' => XenForo_Model_User::$defaultRegisteredGroupId,
        'secondary_group_ids' => $tapatalk_reg_ug,
        'user_state' => $user_state,
        'is_discouraged' => '0',
        'gender' => $gender,
        'dob_day' => isset($birthday[2]) && $birthday[2]>=0 && $birthday[2]<=31 ? $birthday[2] : ($requireDob ? '01' : '0'),
        'dob_month' => isset($birthday[1]) && $birthday[2]>=0 && $birthday[2]<=12 ? $birthday[1] : ($requireDob ? '01' : '0'),
        'dob_year' => isset($birthday[0]) ? $birthday[0] : ($requireDob ? '1971' : '0'),
        'location' => isset($location) && !empty($location) ? $location : '',
        'occupation' => '',
        'custom_title' => '',
        'homepage' => isset($profile['link']) && !empty($profile['link']) ? $profile['link'] : '',
        'about' => isset($profile['description']) && !empty($profile['description']) ? $profile['description'] : '',
        'signature' => isset($profile['signature']) && !empty($profile['signature']) ? $profile['signature'] : '',
        'message_count' => '0',
        'like_count' => '0',
        'trophy_points' => '0',
        'style_id' => '0',
        'language_id' => '1',
        'timezone' => (!isset($xf_options->guestTimeZone) || empty($xf_options->guestTimeZone)) ? 'Europe/London' : $xf_options->guestTimeZone,
        'content_show_signature' => '1',
        'enable_rte' => '1',
        'visible' => '1',
        'receive_admin_email' => '1',
        'show_dob_date' => '1',
        'show_dob_year' => '1',
        'allow_view_profile' => 'everyone',
        'allow_post_profile' => 'members',
        'allow_send_personal_conversation' => 'members',
        'allow_view_identities' => 'everyone',
        'allow_receive_news_feed' => 'everyone',
        'gravatar' => $gravatar ? $email : '',
        );
        $writer = XenForo_DataWriter::create('XenForo_DataWriter_User');
        if(!empty($spamModel))
        {
            $spamModel = $bridge->getSpamPreventionModel();
            $spamResponse = $spamModel->allowRegistration($data, $bridge->_request);
            switch ($spamResponse)
            {
                case XenForo_Model_SpamPrevention::RESULT_DENIED:
                    $spamModel->logSpamTrigger('user', null);
                    $errors[] = TT_GetPhraseString('spam_prevention_registration_rejected');
                    return null;
                    break;

                case XenForo_Model_SpamPrevention::RESULT_MODERATED:
                    $user_state = 'moderated';
                    break;
            }
        }
        $writer->setOption(XenForo_DataWriter_User::OPTION_ADMIN_EDIT, false);

        $writer->bulkSet($data);
        if ($password !== '')
        {
            $writer->setPassword($password);
        }

        $fieldModel=$bridge->_getFieldModel();
        $userFields=$fieldModel->getUserFields();
        $customFieldsShown=array_keys($userFields);
        if(in_array('tapatalk_avatar_url',$customFieldsShown) && isset($profile['avatar']))
        {
            $custom_register_fields['tapatalk_avatar_url'] = $profile['avatar'];
        }
        if(is_array($custom_register_fields))
        {
            foreach ($custom_register_fields as $key=>$value){
                if (isset($userFields[$key]) && !empty($userFields[$key])){
                    switch ($userFields[$key]['field_type']){
                        case 'textbox':
                        case 'textarea':
                            break;
                        case 'select':
                        case 'radio':
                            if (is_array($custom_register_fields[$key])){
                                $keys = array_keys($custom_register_fields[$key]);
                                $custom_register_fields[$key]=reset($keys);
                            }
                            break;
                        case 'checkbox':
                            if (is_array($custom_register_fields[$key])){
                                $keys = array_keys($custom_register_fields[$key]);
                                $custom_register_fields[$key]=reset($keys);
                            }
                            break;
                        case 'multiselect':
                            foreach ($custom_register_fields[$key] as $key2=>$value2){
                                $custom_register_fields[$key][]=$key2;
                                unset($custom_register_fields[$key][$key2]);
                            }
                            break;
                    }
                }
            }
            $writer->setCustomFields($custom_register_fields, $customFieldsShown);
        }

        $writer->preSave();

        $error = $writer->getErrors();
        if($error)
        {
            if (is_array($error)){
                foreach ($error as $key => $value){
                    if ($value instanceof XenForo_Phrase){
                        $errors[] = $value->render();
                    }else{
                        $errors[] = $error;
                    }
                }
            }else {
                if ($error instanceof XenForo_Phrase){
                    $errors[] = $error->render();
                }else{
                    $errors[] = $error;
                }
            }

            return null;
        }

        $writer->save();

        $user = $writer->getMergedData();
        $userConfirmModel = $bridge->getUserConfirmationModel();
        XenForo_Model_Ip::log($user['user_id'], 'user', $user['user_id'], 'register');
        $result_text="";
        if($user_state == 'email_confirm' && $user['user_id'] != 0)
        {
            $userConfirmModel->sendEmailConfirmation($user);
            $errors[] = (string) new XenForo_Phrase('your_account_is_currently_awaiting_confirmation_confirmation_sent_to_x', array('email' => $data['email']));
        }
        else if($user_state == 'moderated' && $user['user_id'] != 0)
        {
            $errors[] = (string) new XenForo_Phrase('thanks_for_registering_registration_must_be_approved');
        }

		$oMbqRdEtUser = MbqMain::$oClk->newObj('MbqRdEtUser');
		return $oMbqRdEtUser->initOMbqEtUser($user, array('case'=>'user_row'));

	}

    public function updatePasswordDirectly($oMbqEtUser, $newPassword)
    {
        $bridge = Tapatalk_Bridge::getInstance();
        $userModel = $bridge->getUserModel();
        $visitor = XenForo_Visitor::getInstance();
        $writer = XenForo_DataWriter::create('XenForo_DataWriter_User');
        if (isset($visitor['user_id']) && $visitor['user_id'])
        {
            $writer->setExistingData($visitor['user_id']);
        }else if(isset($userFromEmail['user_id']) && $userFromEmail['user_id'])
        {
            return false;
        }
        $writer->setOption(XenForo_DataWriter_User::OPTION_ADMIN_EDIT, true);
        if ($newPassword !== '')
        {
            $writer->setPassword($newPassword);
        }
        else
        {
            return $bridge->responseError('password cannot be empty');
        }
        $writer->save();
        if ($errors = $writer->getErrors())
        {
            return $bridge->responseError('Update failed.');
        }
		return true;
    }
	/**
     * update password
     */
	public function updatePassword($oldPassword, $newPassword) {

        $bridge = Tapatalk_Bridge::getInstance();
        $userModel = $bridge->getUserModel();
        $visitor = XenForo_Visitor::getInstance();
        if(!isset($visitor['username']) || empty($visitor['username']))
        {
            return $bridge->responseError('You are not logged in.');
        }
        $userId = $userModel->validateAuthentication($visitor['username'], $oldPassword, $error);
        if (!$userId)
        {
            return $error;
        }
        $writer = XenForo_DataWriter::create('XenForo_DataWriter_User');
        if (isset($visitor['user_id']) && $visitor['user_id'])
        {
            $writer->setExistingData($visitor['user_id']);
        }
        $writer->setOption(XenForo_DataWriter_User::OPTION_ADMIN_EDIT, true);
        if ($newPassword !== '')
        {
            $writer->setPassword($newPassword);
        }
        else
        {
            return $bridge->responseError('password cannot be empty');
        }
        $writer->save();
        if ($errors = $writer->getErrors())
        {
            return $bridge->responseError('Update failed.');
        }


		return true;
	}

	/**
     * update email
     */
	public function updateEmail($password, $email, &$resultMessage) {

        $bridge = Tapatalk_Bridge::getInstance();
        $userModel = $bridge->getUserModel();
        $visitor = XenForo_Visitor::getInstance();

        $options = XenForo_Application::get('options');


        if(empty($password) || empty($email))
            return 'Password/Email could not be empty!';

        $visitor = XenForo_Visitor::getInstance();

        $auth = $userModel->getUserAuthenticationObjectByUserId($visitor['user_id']);
        if (!$auth)
        {
            return $bridge->responseError('You have no permissions to perform this action.');
        }

        if (!$auth->hasPassword())
        {
            unset($email);
        }

        if (isset($email) && $email !== $visitor['email'])
        {
            $auth = $userModel->getUserAuthenticationObjectByUserId($visitor['user_id']);
            if (!$auth->authenticate($visitor['user_id'], $password))
            {
                return $bridge->responseError(new XenForo_Phrase('your_existing_password_is_not_correct'));
            }
        }
        //modify data
        $data['receive_admin_email'] = $visitor['receive_admin_email'];
        $data['email_on_conversation'] = $visitor['email_on_conversation'];
        $data['allow_send_personal_conversation'] = $visitor['allow_send_personal_conversation'];
        unset($data['password']);

        $writer = XenForo_DataWriter::create('XenForo_DataWriter_User');
        $writer->setExistingData(XenForo_Visitor::getUserId());
        $writer->bulkSet($data);

        if ($writer->isChanged('email')
            && XenForo_Application::get('options')->get('registrationSetup', 'emailConfirmation')
            && !$writer->get('is_moderator')
            && !$writer->get('is_admin')
        )
        {
            switch ($writer->get('user_state'))
            {
                case 'moderated':
                case 'email_confirm':
                    $writer->set('user_state', 'email_confirm');
                    break;

                default:
                    $writer->set('user_state', 'email_confirm_edit');
            }
        }

        $writer->preSave();

        if ($dwErrors = $writer->getErrors())
        {
            $error_message = '';
            foreach($dwErrors as $error)
            {
                $error_message .= (string) $error;
            }
            if(empty($error_message))
                $error_message = 'Register failed for unkown reason!';
            return $bridge->responseError($error_message);
        }

        $writer->save();

        $user = $writer->getMergedData();
        if ($writer->isChanged('email')
            && ($user['user_state'] == 'email_confirm_edit' || $user['user_state'] == 'email_confirm')
        )
        {
            $userConfModel = $bridge->getUserConfirmationModel();
            $userConfModel->sendEmailConfirmation($user);
            $resultMessage = new XenForo_Phrase('your_account_must_be_reconfirmed');
        }
        else
        {
            $resultMessage = "Email changed";
        }

        return true;
	}

	/**
     * upload avatar
     */
	public function uploadAvatar() {

        $bridge = Tapatalk_Bridge::getInstance();
        $visitor = XenForo_Visitor::getInstance();



        if (!$visitor->canUploadAvatar())
        {
            return TT_GetPhraseString('do_not_have_permission');
        }

        $avatar = XenForo_Upload::getUploadedFile('upload');

        /* @var $avatarModel XenForo_Model_Avatar */
        $avatarModel = $bridge->getModelFromCache('XenForo_Model_Avatar');
        if($avatar)
        {
            $avatarData = $avatarModel->uploadAvatar($avatar, $visitor['user_id'], $visitor->getPermissions());


            // merge new data into $visitor, if there is any
            if (isset($avatarData) && is_array($avatarData))
            {
                foreach ($avatarData AS $key => $val)
                {
                    $visitor[$key] = $val;
                }
            }



            return true;
        }
        return false;
	}


    /**
     * m_ban_user
     * here,this function is just the same to m_mark_as_spam,so params mode and reason willn't be used.
     */
    public function mBanUser($oMbqEtUser, $mode, $reason, $expires) {
        $visitor = XenForo_Visitor::getInstance();
        $bridge = Tapatalk_Bridge::getInstance();
        $userModel = $bridge->getUserModel();

        $user = $oMbqEtUser->mbqBind;
        $userId = $user['user_id'];
        if ($ban = $bridge->getBanningModel()->getBannedUserById($userId))
        {
            $existing = true;
        }
        else
        {
            $existing = false;
        }
        if(empty($expires))
        {
            $expires = 0;
        }
        if (!$userModel->ban($userId, $expires, $reason, $existing, $errorKey))
        {
            return TT_GetPhraseString($errorKey);
        }
        $options = array(
            'action_threads'  => $mode == 2 ? 1 : 0,
            'delete_messages' => $mode == 2 ? 1 : 0,
            'email_user'      => 0,
            'email'           => '',
        );

        $spamCleanerModel = $bridge->getSpamCleanerModel();

        if (!$log = $spamCleanerModel->cleanUp($user, $options, $log, $errorKey))
        {
            return get_error($errorKey);
        }

        return true;
    }

    /**
     * m_unban_user
     * here,this function just unflag as spammer.
     */
    public function mUnBanUser($oMbqEtUser) {
        $visitor = XenForo_Visitor::getInstance();
        $visitor_permissions = $visitor->getPermissions();
        $bridge = Tapatalk_Bridge::getInstance();
        $userModel = $bridge->getUserModel();

        $userId = $oMbqEtUser->userId->oriValue;
        $userModel->liftBan($userId);
        return true;
    }

    /**
     * ignoreUser
     */
    public function ignoreUser($oMbqEtUser, $mode) {
        $bridge = Tapatalk_Bridge::getInstance();
        $visitor = XenForo_Visitor::getInstance();

        $ignoreModel = $bridge->getIgnoreModel();
        if(isset($mode) && $mode == 0)
        {
            $ignoreModel->unignoreUser($visitor['user_id'], array($oMbqEtUser->userId->oriValue));
        }
        else if(isset($mode) && $mode)
        {
            if (!$ignoreModel->canIgnoreUser($visitor['user_id'], $oMbqEtUser->mbqBind, $error))
            {
                return TT_GetPhraseString($error);
            }
            $ignoreModel->ignoreUsers($visitor['user_id'], array($oMbqEtUser->userId->oriValue));
        }
        return true;
    }
}