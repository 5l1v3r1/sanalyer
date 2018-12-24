<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseAclEtUser');

/**
 * user acl class
 */
Class MbqAclEtUser extends MbqBaseAclEtUser {

    public function __construct() {
    }

    /**
     * judge can get online users
     *
     * @return  Boolean
     */
    public function canAclGetOnlineUsers() {
        return true;
    }

    /**
     * judge can get online users
     *
     * @return  Boolean
     */
    public function canAclGetIgnoredUsers() {
        return true;
    }

    /**
     * judge can m_ban_user
     * here,this function is just the same to m_mark_as_spam
     * @param  Object  $oMbqEtUser
     * @param  Integer  $mode
     * @return  Boolean
     */
    public function canAclMBanUser($oMbqEtUser, $mode) {
        $visitor = XenForo_Visitor::getInstance();
        $bridge = Tapatalk_Bridge::getInstance();
        $userModel = $bridge->getUserModel();


        $user =$oMbqEtUser->mbqBind;
        if (!$visitor->hasAdminPermission('ban'))
        {
            return TT_GetPhraseString('security_error_occurred');
        }
        return true;
    }

    /**
     * judge can m_mark_as_spam
     *
     * @return  Boolean
     */
    public function canAclMMarkAsSpam($oMbqEtUser) {
        $visitor = XenForo_Visitor::getInstance();
        $bridge = Tapatalk_Bridge::getInstance();
        $userModel = $bridge->getUserModel();


        $user =$oMbqEtUser->mbqBind;

        if (!$userModel->couldBeSpammer($user, $errorKey))
        {
            return TT_GetPhraseString($errorKey);
        }
        return  true;
    }

    /**
     * judge can m_ban_user
     *
     * @return  Boolean
     */
    public function canAclMUnbanUser($oMbqEtUser) {
        $visitor = XenForo_Visitor::getInstance();
        if (!$visitor->hasAdminPermission('ban'))
        {
            return TT_GetPhraseString('security_error_occurred');
        }
        return true;
    }

    /**
     * judge can update_password
     *
     * @return Boolean
     */
    public function canAclUpdatePassword() {
        $visitor = XenForo_Visitor::getInstance();
        if(isset($visitor['is_admin']) && $visitor['is_admin'])
        {
            return false;
        }
        return MbqMain::hasLogin();
    }

    /**
     * judge can update_email
     *
     * @return Boolean
     */
    public function canAclUpdateEmail() {
        $visitor = XenForo_Visitor::getInstance();
        if(isset($visitor['is_admin']) && $visitor['is_admin'])
        {
            return false;
        }
        return MbqMain::hasLogin();
    }

    /**
     * judge can upload avatar
     *
     * @return Boolean
     */
    public function canAclUploadAvatar() {
        return MbqMain::hasLogin();
    }

    /**
     * judge can searc_user
     *
     * @return Boolean
     */
    public function canAclSearchUser() {
        $visitor = XenForo_Visitor::getInstance();
        return $visitor->canSearch();
    }

    /**
     * judge can get_recommended_user
     *
     * @return Boolean
     */
    public function canAclGetRecommendedUser() {
        return MbqMain::hasLogin();
    }

    /**
     * judge can ignore_user
     *
     * @return Boolean
     */
    public function canAclIgnoreUser($oMbqEtUser, $mode) {
        return MbqMain::hasLogin();
    }
}