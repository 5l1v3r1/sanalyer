<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseAclEtForum');

/**
 * forum acl class
 */
Class MbqAclEtForum extends MbqBaseAclEtForum {

    public function __construct() {
    }

    /**
     * judge can get subscribed forum
     *
     * @return  Boolean
     */
    public function canAclGetSubscribedForum() {
        return MbqMain::isActiveMember();
    }

    /**
     * judge can subscribe forum
     *
     * @param  Object  $oMbqEtForum
     * @return  Boolean
     */
    public function canAclSubscribeForum($oMbqEtForum, $receiveEmail) {
        $visitor = XenForo_Visitor::getInstance();
        $bridge = Tapatalk_Bridge::getInstance();
        $forumWatchModel = $bridge->getForumWatchModel();

        $forumId =  $oMbqEtForum->forumId->oriValue;
        $userId = $visitor['user_id'];

        $sendAlert = true;
        $sendEmail = $receiveEmail;
        $unwatch = true;

        $forum = $bridge->getHelper('ForumThreadPost')->assertForumValidAndViewable(
            $forumId,
            array(
                'readUserId' => $userId,
                'watchUserId' => $userId
            )
        );
        $forumId = $forum['node_id'];
        if (!$bridge->getForumModel()->canWatchForum($forum) || !MbqMain::isActiveMember())
        {
            return TT_GetPhraseString('do_not_have_permission');
        }
        return true;
    }

    /**
     * judge can unsubscribe forum
     *
     * @param  Object  $oMbqEtForum
     * @return  Boolean
     */
    public function canAclUnsubscribeForum($oMbqEtForum) {
        $visitor = XenForo_Visitor::getInstance();
        $bridge = Tapatalk_Bridge::getInstance();
        $forumWatchModel = $bridge->getForumWatchModel();

        $forumId =  $oMbqEtForum->forumId->oriValue;
        $userId = $visitor['user_id'];

        $sendAlert = true;
        $sendEmail = false;
        $unwatch = true;

        $forum = $bridge->getHelper('ForumThreadPost')->assertForumValidAndViewable(
            $forumId,
            array(
                'readUserId' => $userId,
                'watchUserId' => $userId
            )
        );
        $forumId = $forum['node_id'];
        if (!$bridge->getForumModel()->canWatchForum($forum) || !MbqMain::isActiveMember())
        {
            return TT_GetPhraseString('do_not_have_permission');
        }
        return true;
    }

    public function canAclMarkAllAsRead($oMbqEtForum)
    {
        return MbqMain::isActiveMember();
    }
}