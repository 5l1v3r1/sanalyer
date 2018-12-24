<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseWrEtPc');

/**
 * private conversation write class
 */
Class MbqWrEtPc extends MbqBaseWrEtPc {
    
    public function __construct() {
    }
    /**
     * add private conversation
     *
     * @param  Object  $oMbqEtPc
     */
    public function addMbqEtPc($oMbqEtPc) {
        $participants = $oMbqEtPc->userNames->oriValue;
        if(!is_array($participants))
        {
            $participants = array($participants);
        }
    
        $visitor = XenForo_Visitor::getInstance();
        $bridge = Tapatalk_Bridge::getInstance();
        $conversationModel = $bridge->getConversationModel();

        $input = array(
            'recipients'    => $participants,
            'title'         => $oMbqEtPc->convTitle->oriValue,
            'message'       => $oMbqEtPc->convContent->oriValue,
            'attachment_id_array' => $oMbqEtPc->attachmentIdArray->oriValue,
            'group_id'      => $oMbqEtPc->groupId->oriValue,
        );
        
        $visitor = XenForo_Visitor::getInstance();

        $conversationDw = XenForo_DataWriter::create('XenForo_DataWriter_ConversationMaster');
        $conversationDw->setExtraData(XenForo_DataWriter_ConversationMaster::DATA_ACTION_USER, $visitor->toArray());
        $conversationDw->set('user_id', $visitor['user_id']);
        $conversationDw->set('username', $visitor['username']);
        $conversationDw->set('title', $input['title']);
        $conversationDw->set('open_invite', 0);
        $conversationDw->set('conversation_open', 1);
        $conversationDw->addRecipientUserNames($input['recipients']); // checks permissions

        $input['message'] = XenForo_Helper_String::autoLinkBbCode($input['message']);

        $messageDw = $conversationDw->getFirstMessageDw();
        $messageDw->set('message', $input['message']);
        $messageDw->setExtraData(XenForo_DataWriter_ConversationMessage::DATA_ATTACHMENT_HASH,$input['group_id']);
        
        $conversationDw->preSave();
        
        if (!$conversationDw->hasErrors())
        {
            try{
                $bridge->assertNotFlooding('conversation');
            }
            catch(Exception $ex)
            {
                return $bridge->error;
            }
        }
        else
        {
            $errors = $conversationDw->getErrors();
            return TT_GetPhraseString(reset($errors));
        }
        try
        {
            $conversationDw->save();
        }
        catch(Exception $ex)
        {
            return $ex->getMessage();
        }
        $conversation = $conversationDw->getMergedData();
        
        $conversationModel->markConversationAsRead(
            $conversation['conversation_id'], XenForo_Visitor::getUserId(), XenForo_Application::$time
        );
        $oMbqRdEtPc = MbqMain::$oClk->newObj('MbqRdEtPc');
        $oMbqEtPc =  $oMbqRdEtPc->initOMbqEtPc($conversation['conversation_id'],  array('case'=>'byConvId'));
        return $oMbqEtPc;
    }
    public function inviteParticipant($oMbqEtPcInviteParticipant) {
      
        $conversation = $oMbqEtPcInviteParticipant->oMbqEtPc->mbqBind;
        $conversationId = $oMbqEtPcInviteParticipant->oMbqEtPc->convId->oriValue;

        $bridge = Tapatalk_Bridge::getInstance();
        $conversationModel = $bridge->getConversationModel();
    
      
        $recipients = array();
        foreach ($oMbqEtPcInviteParticipant->objsMbqEtUser as $objsMbqEtUser)
        {
            $recipients[] = $objsMbqEtUser->userName->oriValue;
        }

        $conversationDw = XenForo_DataWriter::create('XenForo_DataWriter_ConversationMaster');
        $conversationDw->setExistingData($conversationId);
        $conversationDw->setExtraData(XenForo_DataWriter_ConversationMaster::DATA_ACTION_USER, XenForo_Visitor::getInstance()->toArray());
        $conversationDw->addRecipientUserNames($recipients);
        $conversationDw->save();
    }
    /**
     * delete conversation
     *
     * @param  Object  $oMbqEtPc
     * @param  Integer  $mode
     */
    public function deleteConversation($oMbqEtPc, $mode) {
        if ($mode ==1 || $mode == 2) {
            try {

                $bridge = Tapatalk_Bridge::getInstance();
                $conversationModel = $bridge->getConversationModel();
                
                $conversationId =$oMbqEtPc->convId->oriValue;
                
                $deleteType = (isset($mode) && $mode == 2) ? 'delete_ignore' : 'delete';
                
                $conversationModel->deleteConversationForUser(
                    $conversationId, XenForo_Visitor::getUserId(), $deleteType
                );
            }
            catch (Exception $e) {
                MbqError::alert('', "Can not delete conversation!", '', MBQ_ERR_APP);
            }
        } else {
            MbqError::alert('', "Need valid mode id!", '', MBQ_ERR_APP);
        }
    }
    /**
     * mark private conversation read
     */
    public function markPcRead($oMbqEtPc) {
        $bridge = Tapatalk_Bridge::getInstance();
        $conversationModel = $bridge->getConversationModel();
        $conversationModel->markConversationAsRead(
        $oMbqEtPc->convId->oriValue, XenForo_Visitor::getUserId(), time());
    }
     /**
     * mark private conversationunread
     */
    public function markPcUnread($oMbqEtPc) {
        $bridge = Tapatalk_Bridge::getInstance();
        $conversationModel = $bridge->getConversationModel();
        $conversationModel->markConversationAsUnread(
        $oMbqEtPc->convId->oriValue, XenForo_Visitor::getUserId());
    }
    
    /**
     * mark all private conversations read
     */
    public function markAllPcRead(){
       
        
    }
}