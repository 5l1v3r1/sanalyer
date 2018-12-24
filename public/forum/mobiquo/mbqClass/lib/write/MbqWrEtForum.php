<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseWrEtForum');

/**
 * forum write class
 */
Class MbqWrEtForum extends MbqBaseWrEtForum {

    public function __construct() {
    }


    /**
     * subscribe forum
     */
    public function subscribeForum($oMbqEtForum, $receiveEmail) {
        $visitor = XenForo_Visitor::getInstance();
        $bridge = Tapatalk_Bridge::getInstance();
        $forumWatchModel = $bridge->getForumWatchModel();

        $forumId = $oMbqEtForum->forumId->oriValue;
        $userId = $visitor['user_id'];

        $sendAlert = true;
        $sendEmail = $receiveEmail;
        $unwatch = false;

        $forum = $bridge->getHelper('ForumThreadPost')->assertForumValidAndViewable(
            $forumId,
            array(
                'readUserId' => $userId,
                'watchUserId' => $userId
            )
        );
        if ($unwatch)
        {
            $notifyOn = 'delete';
        }
        else
        {
            $notifyOn = 'thread';//we only notify new thread
            if ($notifyOn)
            {
                if ($forum['allowed_watch_notifications'] == 'none')
                {
                    $notifyOn = '';
                }
                else if ($forum['allowed_watch_notifications'] == 'thread' && $notifyOn == 'message')
                {
                    $notifyOn = 'thread';
                }
            }
        }
        $forumWatchModel->setForumWatchState(
            XenForo_Visitor::getUserId(), $forumId,
            $notifyOn, $sendAlert, $sendEmail
        );

        return true;
    }

    /**
     * unsubscribe forum
     */
    public function unsubscribeForum($oMbqEtForum) {
        $visitor = XenForo_Visitor::getInstance();
        $bridge = Tapatalk_Bridge::getInstance();
        $forumWatchModel = $bridge->getForumWatchModel();



        $forumId =  $oMbqEtForum->forumId->oriValue;
        $userId = $visitor['user_id'];

        $sendAlert = true;
        $sendEmail = false;
        $unwatch = true;

        if($forumId == 'ALL')
        {
            $forumWatchModel->setForumWatchStateForAll(
                XenForo_Visitor::getUserId(), ''
            );

            return xmlresptrue();
        }

        $forum = $bridge->getHelper('ForumThreadPost')->assertForumValidAndViewable(
            $forumId,
            array(
                'readUserId' => $userId,
                'watchUserId' => $userId
            )
        );
        $forumId = $forum['node_id'];
      
        if ($unwatch)
        {
            $notifyOn = 'delete';
        }
        else
        {
            $notifyOn = 'thread';//we only notify new thread
            if ($notifyOn)
            {
                if ($forum['allowed_watch_notifications'] == 'none')
                {
                    $notifyOn = '';
                }
                else if ($forum['allowed_watch_notifications'] == 'thread' && $notifyOn == 'message')
                {
                    $notifyOn = 'thread';
                }
            }
        }
        $forumWatchModel->setForumWatchState(
            XenForo_Visitor::getUserId(), $forumId,
            $notifyOn, $sendAlert, $sendEmail
        );

        return true;
    }

    public function markForumRead($oMbqEtForum){
        $bridge = Tapatalk_Bridge::getInstance();
        $visitor = XenForo_Visitor::getInstance();
        $forum = null;
        if ($oMbqEtForum != null) {
            $ftpHelper = $bridge->getHelper('ForumThreadPost');
            $forum = $ftpHelper->assertForumValidAndViewable(
                $oMbqEtForum->forumId->oriValue, array('readUserId' => $visitor['user_id'])
            );
        }
        $bridge->getForumModel()->markForumTreeRead($forum, XenForo_Application::$time);
        return true;
    }

}