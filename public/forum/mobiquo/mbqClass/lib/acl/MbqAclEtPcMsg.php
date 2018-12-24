<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseAclEtPcMsg');

/**
 * private conversation message acl class
 */
Class MbqAclEtPcMsg extends MbqBaseAclEtPcMsg {
    
    public function __construct() {
    }
    /**
     * judge can reply_conversation 
     *
     * @return  Boolean
     */
    public function canAclReplyConversation($oMbqEtPcMsg, $oMbqEtPc) {
        $conversation  = $oMbqEtPc->mbqBind;
        if (!$conversation)
        {
            return TT_get_error('requested_conversation_not_found');
        }
        $bridge = Tapatalk_Bridge::getInstance();
        $conversationModel = $bridge->getConversationModel();
        
        if (!$conversationModel->canReplyToConversation($conversation, $errorPhraseKey))
        {
            return TT_get_error($errorPhraseKey);
        }
        return MbqMain::hasLogin();
    }
    
    /**
     * judge can get_quote_conversation
     *
     * @return  Boolean
     */
    public function canAclGetQuoteConversation($oMbqEtPcMsg, $oMbqEtPc) {
        return MbqMain::hasLogin();
    }
}