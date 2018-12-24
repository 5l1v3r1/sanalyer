<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseRdEtPc');

/**
 * private conversation read class
 */
Class MbqRdEtPc extends MbqBaseRdEtPc {

    public function __construct() {
    }

    public function makeProperty(&$oMbqEtPc, $pName, $mbqOpt = array()) {
        switch ($pName) {
            default:
            MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_PNAME . ':' . $pName . '.');
            break;
        }
    }
    /**
     * get unread private conversations number
     *
     * @return  Integer
     */
    public function getUnreadPcNum() {
        $visitor = XenForo_Visitor::getInstance();
        $inbox_unread_count = $visitor['conversations_unread'] ? $visitor['conversations_unread'] : 0;
        return $inbox_unread_count;
    }
    /**
     * get unread private conversations number
     *
     * @return  Integer
     */
    public function getSubcribedUnreadPcNum() {
        $visitor = XenForo_Visitor::getInstance();
        $bridge = Tapatalk_Bridge::getInstance();
        $threadWatchModel = $bridge->getThreadWatchModel();
        $visitor = XenForo_Visitor::getInstance();

        $newThreads = $threadWatchModel->getThreadsWatchedByUser($visitor['user_id'], true, array(
            'join' => XenForo_Model_Thread::FETCH_FORUM | XenForo_Model_Thread::FETCH_USER,
            'readUserId' => $visitor['user_id'],
            'postCountUserId' => $visitor['user_id'],
            'permissionCombinationId' => $visitor['permission_combination_id'],
        ));

        $newThreads = $threadWatchModel->unserializePermissionsInList($newThreads, 'node_permission_cache');
        $newThreads = $threadWatchModel->getViewableThreadsFromList($newThreads);
        $sub_threads_num = count($newThreads);
        return $sub_threads_num;
    }
    /**
     * get private conversation objs
     *
     * $mbqOpt['case'] = 'all' means get my all data.
     * $mbqOpt['case'] = 'byConvIds' means get data by conversation ids.$var is the ids.
     * $mbqOpt['case'] = 'byObjsStdPc' means get data by objsStdPc.$var is the objsStdPc.
     * @return  Mixed
     */
    public function getObjsMbqEtPc($var, $mbqOpt) {
        if ($mbqOpt['case'] == 'all') {
            $oMbqDataPage = $mbqOpt['oMbqDataPage'];

            $start = $oMbqDataPage->startNum;
            $limit = $oMbqDataPage->numPerPage;
            $page = $oMbqDataPage->curPage;

            $visitor = XenForo_Visitor::getInstance();
            $bridge = Tapatalk_Bridge::getInstance();
            $conversationModel = $bridge->getConversationModel();
            $totalConversations = $conversationModel->countConversationsForUser($visitor['user_id']);
            $unreadConversations = $visitor['conversations_unread'] ? $visitor['conversations_unread'] : 0;

            $conversations = $conversationModel->getConversationsForUser($visitor['user_id'], array(), array(
                'page' => $page,
                'perPage' => $limit
            ));
            $conversations = $conversationModel->prepareConversations($conversations);

            $conversation_list = array();

            foreach($conversations as $conversation)
            {
                $oMbqDataPage->datas[] = $this->initOMbqEtPc($conversation, array('case'=>'byRow'));
            }

            $oMbqDataPage->totalNum = $totalConversations;
            $oMbqDataPage->totalUnreadNum = $unreadConversations;
            return $oMbqDataPage;

        }
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_CASE);
    }
    function initOMbqEtPc($var, $mbqOpt)
    {
        if($mbqOpt['case'] == 'byRow')
        {
            $bridge = Tapatalk_Bridge::getInstance();
            $conversationModel = $bridge->getConversationModel();
            $conversation = $var;
            $conversation = $conversationModel->prepareConversation($conversation);

            $oMbqEtPc = MbqMain::$oClk->newObj('MbqEtPc');
            $oMbqEtPc->mbqBind = $conversation;
            $oMbqEtPc->convId->setOriValue($conversation['conversation_id']);
            $oMbqEtPc->convTitle->setOriValue($conversation['title']);
            $conversationModel = $bridge->getConversationModel();
            $message = $conversationModel->getConversationMessageById($conversation['last_message_id']);

            $oMbqEtPc->convContent->setOriValue($bridge->renderPostPreview($message['message'], $conversation['last_message_user_id'],200));
            $oMbqEtPc->totalMessageNum->setOriValue($conversation['reply_count'] +1);
            $oMbqEtPc->participantCount->setOriValue($conversation['recipient_count']);
            $oMbqEtPc->startUserId->setOriValue($conversation['user_id']);
            $oMbqEtPc->startConvTime->setOriValue($conversation['start_date']);
            $oMbqEtPc->lastUserId->setOriValue($conversation['last_message_user_id']);
            $oMbqEtPc->lastConvTime->setOriValue($conversation['last_message_date']);
            $oMbqEtPc->newPost->setOriValue($conversation['isNew']);
            $oMbqEtPc->firstMsgId->setOriValue($conversation['first_message_id']);
            $oMbqEtPc->deleteMode->setOriValue(MbqBaseFdt::getFdt('MbqFdtPc.MbqEtPc.deleteMode.range.soft-delete'));
            $oMbqEtPc->canInvite->setOriValue($conversationModel->canInviteUsersToConversation($conversation));
            $oMbqEtPc->canEdit->setOriValue($conversationModel->canEditConversation($conversation));
            $oMbqEtPc->canClose->setOriValue($conversationModel->canEditConversation($conversation));
            $oMbqEtPc->isClosed->setOriValue(!isset($conversation['conversation_open']) || empty($conversation['conversation_open']));
            $viewingUser = XenForo_Visitor::getInstance()->toArray();
            $oMbqEtPc->canUpload->setOriValue(XenForo_Permission::hasPermission($viewingUser['permissions'], 'conversation', 'uploadAttachment'));
            $recipients = $conversationModel->getConversationRecipients($conversation['conversation_id']);
            $userIds = array();
            foreach($recipients as $uid => $recipient)
            {
                $userIds[] = $uid;
            }
            $oMbqRdEtUser = MbqMain::$oClk->newObj('MbqRdEtUser');
            $objsRecipientMbqEtUser = $oMbqRdEtUser->getObjsMbqEtUser($userIds, array('case' => 'byUserIds'));
            foreach ($objsRecipientMbqEtUser as $oRecipientMbqEtUser) {
                if($oRecipientMbqEtUser != null)
                {
                   $oMbqEtPc->objsRecipientMbqEtUser[$oRecipientMbqEtUser->userId->oriValue] = $oRecipientMbqEtUser;
                }
            }
            return $oMbqEtPc;
        }
        if($mbqOpt['case'] == "byConvId")
        {
            try
            {
                $conversationId = $var;
                $bridge = Tapatalk_Bridge::getInstance();
                $conversationModel = $bridge->getConversationModel();

                if($conversation = $conversationModel->getConversationForUser($conversationId, XenForo_Visitor::getUserId()))
                {
                    $conversation = $conversationModel->prepareConversation($conversation);
                    return $this->initOMbqEtPc($conversation, array('case'=>'byRow'));
                }
                return false;
            }
            catch(Exception $ex)
            {
                return false;
            }
        }
    }
    function canUpload()
    {
        $viewingUser = XenForo_Visitor::getInstance()->toArray();
        return XenForo_Permission::hasPermission($viewingUser['permissions'], 'conversation', 'uploadAttachment');
    }
    function getUrl($oMbqEtPc)
    {
        return XenForo_Link::buildPublicLink('full:conversations',
			array(
				'conversation_id' => $oMbqEtPc->convId->oriValue,
				'title' => $oMbqEtPc->convTitle->oriValue,
			));
    }
}