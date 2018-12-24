<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseWrEtPcMsg');

/**
 * private conversation message write class
 */
Class MbqWrEtPcMsg extends MbqBaseWrEtPcMsg {

    public function __construct() {
    }
    /**
     * add private conversation message
     *
     * @param  Object  $oMbqEtPcMsg
     * @param  Object  $oMbqEtPc
     */
    public function addMbqEtPcMsg($oMbqEtPcMsg, $oMbqEtPc) {
        $bridge = Tapatalk_Bridge::getInstance();

        $conversationId = $oMbqEtPc->convId->oriValue;
        $visitor = XenForo_Visitor::getInstance();

        $newcontent = XenForo_Helper_String::autoLinkBbCode($oMbqEtPcMsg->msgContent->oriValue);

        $messageDw = XenForo_DataWriter::create('XenForo_DataWriter_ConversationMessage');
        $messageDw->setExtraData(XenForo_DataWriter_ConversationMessage::DATA_MESSAGE_SENDER, $visitor->toArray());
        if($oMbqEtPcMsg->groupId->hasSetOriValue())
        {
            $messageDw->setExtraData(XenForo_DataWriter_ConversationMessage::DATA_ATTACHMENT_HASH, $oMbqEtPcMsg->groupId->oriValue);
        }
        $messageDw->set('conversation_id', $conversationId);
        $messageDw->set('user_id', $visitor['user_id']);
        $messageDw->set('username', $visitor['username']);
        $messageDw->set('message', $newcontent);
        $messageDw->preSave();

        if (!$messageDw->hasErrors())
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
            $errors = $messageDw->getErrors();
            if(isset($errors['message']))
            {
                return TT_GetPhraseString($errors['message']);
            }
            if ($errors)
            {
                $error = reset($errors);
                if(is_a($error,'Xenforo_Phrase'))
                {
                    return TT_GetXenforoPhraseString($error);
                }
                return $bridge->responseError($error);
            }
        }
        $messageDw->save();

        $message = $messageDw->getMergedData();

        $oMbqEtPcMsg->msgId->setOriValue($message['message_id']);
        return $oMbqEtPcMsg;
    }
}