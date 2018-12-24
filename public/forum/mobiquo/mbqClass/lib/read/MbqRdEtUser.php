<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseRdEtUser');

/**
 * user read class
 */
Class MbqRdEtUser extends MbqBaseRdEtUser {

    public function __construct() {
    }
    public function makeProperty(&$oMbqEtUser, $pName, $mbqOpt = array()) {
        switch ($pName) {
            default:
                MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_PNAME . ':' . $pName . '.');
                break;
        }
    }
    public function login($login, $password, $anonymous = 0, $trustCode) {

        $bridge = Tapatalk_Bridge::getInstance();
        $loginModel = $bridge->getLoginModel();
        $userModel = $bridge->getUserModel();
        $conversationModel = $bridge->getConversationModel();
        $options = XenForo_Application::get('options');

        $userId = $userModel->validateAuthentication($login, $password, $error);

        if ($userId)
        {

            return $this->doLogin($userId, $trustCode);
        }
        $error_phrasename = TT_GetPhraseString($error->getPhraseName());
        $loginModel->logLoginAttempt($login);
        //if($error_phrasename == 'requested_user_x_not_found')
        //{
        //    $result = new xmlrpcval(array(
        //     'result'        => new xmlrpcval(false, 'boolean'),
        //     'status'         => new xmlrpcval('2', 'string'),
        //       'result_text'    => new xmlrpcval($error->__toString(), 'base64'),
        //   ), 'struct');
        //   return new xmlrpcresp($result);
        // }
        return $error_phrasename;
    }
    public function loginTwoStep($twoStepCode, $trust, &$trustCode)
    {
        $bridge = Tapatalk_Bridge::getInstance();
        $loginHelper = $bridge->getHelper('Login');
        $user = $loginHelper->getUserForTfaCheck();
		if (!$user)
		{
			$loginHelper->clearTfaSessionCheck();
			return "Two step athentication failed";
		}
        /** @var XenForo_Model_Tfa $tfaModel */
		$tfaModel = $bridge->getModelFromCache('XenForo_Model_Tfa');
		$providers = $tfaModel->getTfaConfigurationForUser($user['user_id'], $userData);

		if (!$providers)
		{
			$loginHelper->clearTfaSessionCheck();
            return "Two step athentication failed";
		}
        $loginHelper->assertNotTfaAttemptLimited($user['user_id']);
       $bridge->setUserParams('code', $twoStepCode);
       $validationResult = false;
       foreach($providers as $providerId=>$value)
       {
           $validationResult = $loginHelper->runTfaValidation($user, $providerId, $providers, $userData);
           if($validationResult === true)
           {
               break;
           }
       }
		if ($validationResult === true)
		{
			if ($trust)
			{
				$trustCode = $loginHelper->setDeviceTrusted($user['user_id']);
			}
			return $this->doFinalLogin($user['user_id']);
		}
		else if ($validationResult === false)
		{
			$var = new XenForo_Phrase('two_step_verification_value_could_not_be_confirmed');
            return $var->render(true);
		}
    }
    public function loginDirectly($oMbqEtUser, $trustCode) {
        return $this->doLogin($oMbqEtUser->userId->oriValue, $trustCode);
    }
    private function doLogin($userId, $trustCode = null)
    {
        $bridge = Tapatalk_Bridge::getInstance();
        $loginModel = $bridge->getLoginModel();
        $conversationModel = $bridge->getConversationModel();
        $userModel = $bridge->getUserModel();
        $options = XenForo_Application::get('options');
        if(version_compare(XenForo_Application::$version, '1.5.0', '>=') && $options->TT_2fa_enabled == 1)
        {
            $user = $userModel->getFullUserById($userId);
            if(!empty($trustCode))
            {
                $bridge->setUserCookie('tfa_trust', $trustCode);
            }
            /** @var XenForo_ControllerHelper_Login $loginHelper */
            $loginHelper = $bridge->getHelper('Login');

            if ($loginHelper->userTfaConfirmationRequired($user))
            {
                $loginHelper->setTfaSessionCheck($user['user_id']);
                $tfaModel = $bridge->getModelFromCache('XenForo_Model_Tfa');
                $providers = $tfaModel->getTfaConfigurationForUser($user['user_id'], $userData);
                if(array_key_exists('email',$providers))
                {
                    $viewParams = $loginHelper->triggerTfaCheck($user, 'email', $providers, $userData);
                }
                return "two-step-required";
            }
        }
        return $this->doFinalLogin($userId);
    }
    private function doFinalLogin($userId)
    {
        $bridge = Tapatalk_Bridge::getInstance();
        $loginModel = $bridge->getLoginModel();
        $conversationModel = $bridge->getConversationModel();
        $userModel = $bridge->getUserModel();
        $options = XenForo_Application::get('options');
        XenForo_Model_Ip::log($userId, 'user', $userId, 'login');

        $userModel->deleteSessionActivity(0, $bridge->_request->getClientIp(false));

        $session = XenForo_Application::get('session');
        $session->changeUserId($userId);
        XenForo_Visitor::setup($userId);

        $visitor = XenForo_Visitor::getInstance();
        $loginModel->clearLoginAttempts($visitor['username']);

        $this->initOCurMbqEtUser($userId);
        return MbqMain::$oCurMbqEtUser != null;
    }
    public function initOCurMbqEtUser($userId) {
        MbqMain::$Cache->Reset();
        MbqMain::$oCurMbqEtUser = $this->initOMbqEtUser($userId, array('case'=>'byUserId','loggedUser'=>true));
    }
    /**
     * get user objs
     *
     * @param  Mixed  $var
     * @param  Array  $mbqOpt
     * $mbqOpt['case'] = 'byUserIds' means get data by user ids.$var is the ids.
     * @mbqOpt['case'] = 'online' means get online user.
     * @return  Array
     */
    public function getObjsMbqEtUser($var, $mbqOpt) {
        if ($mbqOpt['case'] == 'byUserIds') {
            $result = array();
            foreach($var as $userId)
            {
                $oMbqEtUser = $this->initOMbqEtUser($userId, array('case'=>'byUserId'));
                if(is_a($oMbqEtUser,'MbqEtUser'))
                {
                    $result[] = $this->initOMbqEtUser($userId, array('case'=>'byUserId'));
                }
            }
            return $result;
        }
        elseif ($mbqOpt['case'] == 'searchByName')
        {
            $bridge = Tapatalk_Bridge::getInstance();
            $oMbqDataPage = $mbqOpt['oMbqDataPage'];
            $total = 0;
            $user_lists = array();
            if ($var !== '' && utf8_strlen($var) >= 2)
            {
                $user_lists = $bridge->getUserModel()->getUsers(
                    array('username' => array($var , 'r'), 'user_state' => 'valid', 'is_banned' => 0),
                    array('perPage' => $oMbqDataPage->numPerPage, 'page' => $oMbqDataPage->curPage)
                );
                $total = $bridge->getUserModel()->countUsers(array('username' => array($var , 'r'), 'user_state' => 'valid', 'is_banned' => 0));
            }
            else
            {
                $user_lists = array();
            }

            foreach ($user_lists as $user)
            {
                $oMbqDataPage->datas[] = $this->initOMbqEtUser($user, array('case'=>'user_row'));
            }
            $oMbqDataPage->totalNum = $total;
            return $oMbqDataPage;
        }
        elseif ($mbqOpt['case'] == 'online') {
            $oMbqDataPage = $mbqOpt['oMbqDataPage'];
            $bridge = Tapatalk_Bridge::getInstance();
            $visitor = XenForo_Visitor::getInstance();
            $sessionModel = $bridge->getSessionModel();

            $bypassUserPrivacy = $bridge->getUserModel()->canBypassUserPrivacy();

            if (empty($var->id))
            {
                $conditions = array(
                    'cutOff' => array('>', $sessionModel->getOnlineStatusTimeout()),
                    'getInvisible' => $bypassUserPrivacy,
                    'getUnconfirmed' => $bypassUserPrivacy,
                    'userLimit'      => 'registered',
                    // allow force including of self, even if invisible
                    'forceInclude' => ($bypassUserPrivacy ? false : XenForo_Visitor::getUserId())
                );

                $onlineUsers = $sessionModel->getSessionActivityRecords($conditions, array(
                    'perPage' => $oMbqDataPage->numPerPage ? $oMbqDataPage->numPerPage : 200,
                    'page' => $oMbqDataPage->curPage ?  $oMbqDataPage->curPage : 1,
                    'join' => XenForo_Model_Session::FETCH_USER,
                    'order' => 'view_date'
                ));

                $onlineUsers = $sessionModel->addSessionActivityDetailsToList($onlineUsers);

                foreach($onlineUsers as $id => $onlineuser)
                {
                    $oMbqDataPage->datas[] = $this->initOMbqEtUser($onlineuser['user_id'], array('case'=>'byUserId'));
                }

                $onlineTotals = $sessionModel->getSessionActivityQuickList(
                    $visitor->toArray(),
                    array('cutOff' => array('>', $sessionModel->getOnlineStatusTimeout())),
                    ($visitor['user_id'] ? $visitor->toArray() : null)
                );
                //scrolling page of online users will result duplicate because Xenforo itself do it the same.
                $oMbqDataPage->totalNum = $onlineTotals['total'];
            }
            return $oMbqDataPage;
        }
        elseif ($mbqOpt['case'] == 'ignored') {

            $oMbqDataPage = $mbqOpt['oMbqDataPage'];
            $visitor = XenForo_Visitor::getInstance();

            if(isset($visitor['ignoredUsers']) && !empty($visitor['ignoredUsers']))
            {
                $ignoredUsers = $visitor['ignoredUsers'];
                $oMbqDataPage->totalNum = sizeof($visitor['ignoredUsers']);
                $ignoredUsers = array_slice(array_keys($ignoredUsers), $oMbqDataPage->startNum, $oMbqDataPage->numPerPage);
                foreach($ignoredUsers as $id)
                {
                    $oMbqDataPage->datas[] = $this->initOMbqEtUser($id, array('case'=>'byUserId'));
                }

            }
            return $oMbqDataPage;
        }
        elseif ($mbqOpt['case'] == 'recommended')
        {
            $oMbqDataPage = $mbqOpt['oMbqDataPage'];
            $oMbqDataPage->datas = array();
            $oMbqDataPage->totalNum = 0;
            return $oMbqDataPage;
        }
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_CASE);
    }

    public function initOMbqEtUser($var, $mbqOpt) {
        if($mbqOpt['case'] == 'user_row')
        {
            if($var == false)
            {
                return null;
            }

            $bridge = Tapatalk_Bridge::getInstance();
            $visitor = XenForo_Visitor::getInstance();
            $conversationModel = $bridge->getConversationModel();
            $options = XenForo_Application::get('options');
            $userModel = $bridge->getUserModel();

            $member = $var;
            $isCurrentLoggedUser = false;

            $memberId = $member['user_id'];
            if($memberId == null)
            {
                return null;
            }

            $loggedMemberId = $visitor['user_id'];

            if($memberId == $loggedMemberId || (isset($mbqOpt['loggedUser']) && $mbqOpt['loggedUser'] ))
            {
                $isCurrentLoggedUser = true;
            }
            $oMbqEtUser = MbqMain::$oClk->newObj('MbqEtUser');
            $oMbqEtUser->userId->setOriValue($memberId);
            $oMbqEtUser->loginName->setOriValue($member['username']);
            $oMbqEtUser->userName->setOriValue($member['username']);
            $groups = array($visitor['user_group_id']);

            if ($visitor['secondary_group_ids'])
            {
                $secondary_groups = explode(",", $visitor['secondary_group_ids']);
                foreach($secondary_groups as $secondary_group_id){
                    $groups[] = $secondary_group_id;
                }
            }

            $oMbqEtUser->userGroupIds->setOriValue($groups);
            $oMbqEtUser->iconUrl->setOriValue(TT_get_avatar($member, "l"));
            $oMbqEtUser->postCount->setOriValue($member['message_count']);
            $oMbqEtUser->userType->setOriValue(TT_get_usertype_by_item($memberId));
            $oMbqEtUser->canBan->setOriValue($visitor->hasAdminPermission('ban') && $userModel->couldBeSpammer($member));
            $oMbqEtUser->isBan->setOriValue($member['is_banned'] == 1);
            $postCountdown=0;
            if (!XenForo_Visitor::getInstance()->hasPermission('general', 'bypassFloodCheck')){
                $postCountdown=$options->floodCheckLength;
            }
            $oMbqEtUser->postCountdown->setOriValue($postCountdown);
            $oMbqEtUser->regTime->setOriValue($member['register_date']);
            $oMbqEtUser->lastActivityTime->setOriValue($member['last_activity']);

            $activity = new XenForo_Phrase('viewing_forum');
            if(isset($sessionActivity['activityDescription']) && !empty($sessionActivity['activityDescription'])){
                $activity = $sessionActivity['activityDescription'];
                if(isset($sessionActivity['activityItemTitle']) && !empty($sessionActivity['activityItemTitle'])){
                    $activity .= " ".$sessionActivity['activityItemTitle'];
                }
            }
            $activity .= " (".XenForo_Template_Helper_Core::dateTime($member['last_activity'], 'relative').")";
            $oMbqEtUser->currentAction->setOriValue($activity);

            if($isCurrentLoggedUser)
            {
                $oMbqEtUser->userEmail->setOriValue($member['email']);
                $oMbqEtUser->canSearch->setOriValue($visitor->canSearch());

                $oMbqEtUser->isOnline->setOriValue(true);
                $oMbqEtUser->canPm->setOriValue(true);
                $oMbqEtUser->canSendPm->setOriValue($conversationModel->canStartConversations($errorPhraseKey));
                $oMbqEtUser->canModerate->setOriValue($member['is_moderator']);
                $oMbqEtUser->canWhosonline->setOriValue(true);
                $oMbqEtUser->canProfile->setOriValue(true);

                $permissions = $visitor->getPermissions();
                $maxFileSize = 0;
                if (isset($permissions) && !empty($permissions)){
                    $maxFileSize = XenForo_Permission::hasPermission($permissions, 'avatar', 'maxFileSize');
                }
                if (isset($maxFileSize) && !empty($maxFileSize) && $maxFileSize == -1){
                    $maxFileSize = XenForo_Model_Avatar::getSizeFromCode('l') * 1024;
                }

                $maxAttachmentFileSize = $options->attachmentMaxFileSize ? $options->attachmentMaxFileSize*1024 : 1048576;
                $oMbqEtUser->canUploadAvatar->setOriValue($visitor->canUploadAvatar());
                $oMbqEtUser->maxAvatarSize->setOriValue($maxFileSize);
                $oMbqEtUser->maxAvatarWidth->setOriValue($maxFileSize);
                $oMbqEtUser->maxAvatarHeight->setOriValue($maxFileSize);
                $oMbqEtUser->maxAttachment->setOriValue($options->attachmentMaxPerMessage ? $options->attachmentMaxPerMessage : 10);
                $oMbqEtUser->maxAttachmentSize->setOriValue($maxAttachmentFileSize);
                $oMbqEtUser->maxPngSize->setOriValue($maxAttachmentFileSize);
                $oMbqEtUser->maxJpgSize->setOriValue($maxAttachmentFileSize);
                $oMbqEtUser->allowedExtensions->setOriValue(implode(',',preg_split("/\s+/", trim($options->attachmentExtensions))));

                if(isset($member['ignored']) && !empty($member['ignored']))
                {
                    $unserialized = unserialize($member['ignored']);
                    if(!empty($unserialized))
                    {
                        $oMbqEtUser->ignoredUids->setOriValue(implode(',', array_keys($unserialized)));
                    }
                }
                $oMbqEtUser->isIgnored->setOriValue(false);
            }
            else
            {
                $oMbqEtUser->isIgnored->setOriValue(MbqCM::checkIfUserIsIgnored($memberId));
            }

            $oMbqEtUser->mbqBind = $var;
            return $oMbqEtUser;
        }
        else if($mbqOpt['case'] == 'byLoginName')
        {
            $username = $var;
            $bridge = Tapatalk_Bridge::getInstance();
            $userModel = $bridge->getUserModel();
            $member = $userModel->getUserByName($username);
            return $this->initOMbqEtUser($member['user_id'], array('case'=>'byUserId'));
        }
        else if($mbqOpt['case'] == 'byEmail')
        {
            $email = $var;
            $bridge = Tapatalk_Bridge::getInstance();
            $userModel = $bridge->getUserModel();
            $member = $userModel->getUserByEmail($email);
            return $this->initOMbqEtUser($member['user_id'], array('case'=>'byUserId'));
        }
        else if($mbqOpt['case'] == 'byUserId')
        {
            $userId = $var;
            if(empty($userId))
            {
                return null;
            }
            if(MbqMain::$Cache->Exists('MbqEtUser',$userId))
            {
                return MbqMain::$Cache->Get('MbqEtUser',$userId);
            }
            $bridge = Tapatalk_Bridge::getInstance();
            $visitor = XenForo_Visitor::getInstance();
            $userModel = $bridge->getUserModel();
            $userProfileModel = $bridge->getUserProfileModel();
            $sessionModel = $bridge->getSessionModel();
            //$warningModel = $bridge->getWarningModel();

            $custom_fields_list = array();

            $userFetchOptions = array(
                'join' => XenForo_Model_User::FETCH_LAST_ACTIVITY
            );
            $member = null;
            try
            {
             //   $member = $bridge->getHelper('UserProfile')->assertUserProfileValidAndViewable($userId, $userFetchOptions);
                $member = $bridge->getHelper('UserProfile')->getUserOrError($userId, $userFetchOptions);
                if($member['user_id'] == null)
                {
                    return null;
                }
                $member = $userModel->prepareUser($member);
            }
            catch(Exception $ex)
            {
            }
            if($member == null)
            {
                if($bridge->error)
                {
                    return $bridge->error;
                }
                return null;
            }
            //if (!$userProfileModel->canViewFullUserProfile($member, $errorPhraseKey))
            //{
            //    throw $bridge->getErrorOrNoPermissionResponseException($errorPhraseKey);
            //}

            if ($member['following'])
            {
                $followingCount = substr_count($member['following'], ',') + 1;
            }
            else
            {
                $followingCount = 0;
            }
            $followersCount = $userModel->countUsersFollowingUserId($member['user_id']);

            $birthday = $userProfileModel->getUserBirthdayDetails($member);
            $age = $userProfileModel->getUserAge($member);

            if(isset($member['custom_title']) && !empty($member['custom_title']))
                TT_addNameValue(new XenForo_Phrase('title'), $member['custom_title'], $custom_fields_list);
            if(isset($member['location']) && !empty($member['location']))
                TT_addNameValue(new XenForo_Phrase('location'), $member['location'], $custom_fields_list);
            if(isset($member['occupation']) && !empty($member['occupation']))
                TT_addNameValue(new XenForo_Phrase('occupation'), $member['occupation'], $custom_fields_list);
            if(isset($member['homepage']) && !empty($member['homepage']))
                TT_addNameValue(new XenForo_Phrase('home_page'), $member['homepage'], $custom_fields_list);
            if(isset($member['gender']) && !empty($member['gender']))
                TT_addNameValue(new XenForo_Phrase('gender'), new XenForo_Phrase($member['gender']), $custom_fields_list);

            if(!empty($birthday))
                TT_addNameValue(new XenForo_Phrase('birthday'), XenForo_Template_Helper_Core::date($birthday['timeStamp'], $birthday['format']).
                (isset($birthday['age']) && !empty($birthday['age']) ? (" (".new XenForo_Phrase('age').": ".$birthday['age'].")") : ""), $custom_fields_list);
            else if(!empty($age))
                TT_addNameValue(new XenForo_Phrase('age'), $age, $custom_fields_list);

            if (version_compare(XenForo_Application::$version, '1.0.4', '>'))
            {
                $fieldModel = $bridge->_getFieldModel();
                $customFields = $fieldModel->prepareUserFields($fieldModel->getUserFields(
                    array('profileView' => true),
                    array('valueUserId' => $member['user_id'])
                ));
                foreach ($customFields AS $key => $field)
                {
                    if (!$field['viewableProfile'] || !$field['hasValue'])
                    {
                        unset($customFields[$key]);
                    }
                }

                $customFieldsGrouped = $fieldModel->groupUserFields($customFields);
                if (!$userProfileModel->canViewIdentities($member))
                {
                    $customFieldsGrouped['contact'] = array();
                }

                if (isset($customFieldsGrouped['contact']) && is_array($customFieldsGrouped['contact']))
                    foreach($customFieldsGrouped['contact'] as $identity)
                        TT_addNameValue($identity['title'], $identity['field_value'], $custom_fields_list);
            }
            else
            {
                if ($userProfileModel->canViewIdentities($member))
                {
                    $identities = $userModel->getPrintableIdentityList($member['identities']);
                    foreach($identities as $identity)
                        TT_addNameValue($identity['title'], $identity['value'], $custom_fields_list);
                }
            }

            TT_addNameValue(new XenForo_Phrase('followers'), $followersCount, $custom_fields_list);
            TT_addNameValue(new XenForo_Phrase('following'), $followingCount, $custom_fields_list);
            TT_addNameValue(new XenForo_Phrase('likes_received'), $member['like_count'], $custom_fields_list);
            TT_addNameValue(new XenForo_Phrase('trophy_points'), $member['trophy_points'], $custom_fields_list);
            if ($userModel->canViewWarnings())
            {
                TT_addNameValue(new XenForo_Phrase('warning_points'), $member['warning_points'], $custom_fields_list);
            }


            $sessionActivity = $sessionModel->getSessionActivityRecords(array(
                'user_id' => $member['user_id']
            ));
            $sessionActivity = $sessionModel->addSessionActivityDetailsToList($sessionActivity);
            $sessionActivity = reset($sessionActivity);

            $activity = new XenForo_Phrase('viewing_forum');
            if(isset($sessionActivity['activityDescription']) && !empty($sessionActivity['activityDescription'])){
                $activity = $sessionActivity['activityDescription'];
                if(isset($sessionActivity['activityItemTitle']) && !empty($sessionActivity['activityItemTitle'])){
                    $activity .= " ".$sessionActivity['activityItemTitle'];
                }
            }
            $activity .= " (".XenForo_Template_Helper_Core::dateTime($member['view_date'], 'relative').")";

            $oMbqEtUser = $this->initOMbqEtUser($member, array('case'=>'user_row'));
            $oMbqEtUser->customFieldsList->setOriValue($custom_fields_list);
            MbqMain::$Cache->Set('MbqEtUser',$userId,$oMbqEtUser);
            return $oMbqEtUser;
        }
    }



    public function getCustomRegisterFields()
    {
        $bridge = Tapatalk_Bridge::getInstance();
        $conversationModel = $bridge->getConversationModel();
        $userModel = $bridge->getUserModel();
        $fieldModel = $bridge->_getFieldModel();


        $options = XenForo_Application::get('options');
        $custom_register_fields = array();

        if ($options->get('registrationSetup', 'requireDob')){
            $custom_register_fields[] = array(
                'name'        => TT_GetPhraseString('date_of_birth'),
                'description' => 'Required',
                'key'         => 'birthday',
                'type'        => 'input',
                'options'     => '','base64',
                'format'      => 'nnnn-nn-nn',
                'is_birthday' => 1,
            );
        }

        if ($options->get('registrationSetup', 'requireLocation')){
            $custom_register_fields[] = array(
            'name'        => TT_GetPhraseString('location'),
            'description' => 'Required',
            'key'         => 'location',
            'type'        => 'input',
            'options'     => '',
            'format'      => '',
        );
        }

        $fields = $fieldModel->prepareUserFields($fieldModel->getUserFields());

        foreach ( $fields as $key => $value)
        {
            if(!$value['required']) continue;

            $field_type="";

            switch ($value['field_type'])
            {
                case 'textbox':
                    $field_type = 'input';
                    break;
                case 'textarea':
                    $field_type = 'textarea';
                    break;
                case 'select':
                    $field_type = 'drop';
                    break;
                case 'radio':
                    $field_type = 'radio';
                    break;
                case 'checkbox':
                case 'multiselect':
                    $field_type = 'cbox';
                    break;
            }

            $format = "";
            switch ($value['match_type'])
            {
                case 'none':
                    $format="";
                    break;
                case 'number':
                    if($value['max_length'] == 0)
                    {
                        $format = 'nnnnnnnnnn';
                    }
                    else
                    {
                        for($ix=0;$ix< $value['max_length'];$ix++)
                        {
                            $format .= 'n';
                        }
                    }
                    break;
            }

            $option="";
            $field_choices = unserialize($value['field_choices']);
            foreach ($field_choices as $title => $text){
                $option .= $title.'='.$text.'|';
            }
            $option=substr($option, 0, strlen($option)-1);

            $custom_register_fields[] = array(
                'name'        => $value['title']->render(),
                'description' => $value['description']->render(),
                'key'         => $value['field_id'],
                'type'        => $field_type,
                'options'     => $option,
                'format'      => $format,
            );
        }




        return $custom_register_fields;
    }
    /**
     * forget_password this function should send the email password change to this user
     *
     * @return Array
     */
    public function forgetPassword($oMbqEtUser) {

        $bridge = Tapatalk_Bridge::getInstance();
        $userModel = $bridge->getUserModel();
        $userConfirmModel = $bridge->getUserConfirmationModel();

        $options = XenForo_Application::get('options');

        $user = $oMbqEtUser->mbqBind;

        if(!empty($user))
        {
            $res = $userConfirmModel->sendPasswordResetRequest($user);
            if(!$res)
            {
                return 'Failed to send confirmation email.';
            }
        }
        return true;
    }
    public function logout()
    {
        $bridge = Tapatalk_Bridge::getInstance();

        // remove an admin session if we're logged in as the same person
        if (XenForo_Visitor::getInstance()->get('is_admin'))
        {
            $adminSession = new XenForo_Session(array('admin' => true));
            $adminSession->start();
            if ($adminSession->get('user_id') == XenForo_Visitor::getUserId())
            {
                $adminSession->delete();
            }
        }

        $bridge->getSessionModel()->processLastActivityUpdateForLogOut(XenForo_Visitor::getUserId());

        if(XenForo_Application::get('session') != null)
        {
            XenForo_Application::get('session')->delete();
        }
        XenForo_Helper_Cookie::deleteAllCookies(
            array('session'),
            array('user' => array('httpOnly' => false))
        );

        XenForo_Visitor::setup(0);

        return true;
    }


    public function getDisplayName($oMbqEtUser) {
        //return $oMbqEtUser->loginName->oriValue;
        return htmlspecialchars_decode($oMbqEtUser->loginName->oriValue);
    }




    /**
     * the response should be bool to indicate if the username meet the forum requirement
     *
     * @param string $username
     */
    public function validateUsername($username){
        $options = array(XenForo_DataWriter_User::OPTION_ADMIN_EDIT => true);

        $v_data = array(
                'name' => 'username',
                'value' => $username,
        );
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

        if ($error = $vwriter->getErrors()){
            return false;
        }
        return true;
    }

    /**
     * the response should be bool to indicate if the password meet the forum requirement
     *
     * @param string $password
     */
    public function validatePassword($password){
		$auth = XenForo_Authentication_Abstract::createDefault();
		$authData = $auth->generate($password);
		if (!$authData || trim($password) == '' || strlen($password) < 2)
        {
            return false;
        }
        return true;
    }
}