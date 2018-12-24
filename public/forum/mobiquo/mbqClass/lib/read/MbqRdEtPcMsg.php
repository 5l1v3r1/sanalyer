<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseRdEtPcMsg');

/**
 * private conversation message read class
 */
Class MbqRdEtPcMsg extends MbqBaseRdEtPcMsg {

    public function __construct() {
    }

    public function makeProperty(&$oMbqEtPcMsg, $pName, $mbqOpt = array()) {
        switch ($pName) {
            default:
            MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_PNAME . ':' . $pName . '.');
            break;
        }
    }
    /**
     * get private conversation message objs
     *
     * @param  Mixed  $var
     * @param  Array  $mbqOpt
     * $mbqOpt['case'] = 'byPc' means get data by private conversation obj.$var is the private conversation obj
     * $mbqOpt['case'] = 'byMsgIds' means get data by conversation message ids.$var is the ids.
     * @return  Mixed
     */
    public function getObjsMbqEtPcMsg($var, $mbqOpt) {
        if ($mbqOpt['case'] == 'byPc') {
            $oMbqEtPc = $var;
            $oMbqDataPage = $mbqOpt['oMbqDataPage'];
            $bridge = Tapatalk_Bridge::getInstance();
            $conversationModel = $bridge->getConversationModel();
            $conversationId = $oMbqEtPc->convId->oriValue;
            $conversation = $oMbqEtPc->mbqBind;
            $messages = $conversationModel->getConversationMessages($conversationId, array(
                'perPage' => $oMbqDataPage->numPerPage,
                'page' => $oMbqDataPage->curPage,
                'join' => 0
            ));



            $messages = $conversationModel->prepareMessages($messages, $conversation);
            foreach($messages as $message)
            {
                $oMbqDataPage->datas[] = $this->initOMbqEtPcMsg($message, array('case'=>'byRow'));
            }
            $oMbqDataPage->totalNum = $conversation['reply_count'] + 1;
            return $oMbqDataPage;
        }
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_CASE);
    }
    function initOMbqEtPcMsg($var, $mbqOpt)
    {
        if($mbqOpt['case'] == 'byRow')
        {
            $bridge = Tapatalk_Bridge::getInstance();
            $conversationModel = $bridge->getConversationModel();
            $oMbqEtPcMsg = MbqMain::$oClk->newObj('MbqEtPcMsg');
            $message = $var;
            $oMbqEtPcMsg->mbqBind = $message;
            $visitor = XenForo_Visitor::getInstance();
            $conversation = $conversationModel->getConversationForUser($message['conversation_id'],$visitor['user_id']);
            if($conversation)
            {
                $conversation = $conversationModel->prepareConversation($conversation);

                $defaultOptions = array(
                'states' => array(
                    'viewAttachments' => $conversationModel->canViewAttachmentOnConversationMessage($message, $conversation),
                    'returnHtml' => true
                    )
                );
            }
            else
            {
                $defaultOptions = array(
               'states' => array(
                   'viewAttachments' => false,
                   'returnHtml' => true
                   )
               );
            }
            $messageResult = $conversationModel->getAndMergeAttachmentsIntoConversationMessages(array($message['message_id'] => $message));
            $message = $messageResult[$message['message_id']];

            if(isset($message['attachments']) && !empty($message['attachments'])){
                $defaultOptions['states']['attachments'] = $message['attachments'];

                if (stripos($message['message'], '[/attach]') !== false)
                {
                    if (preg_match_all('#\[attach(=[^\]]*)?\](?P<id>\d+)\[/attach\]#i', $message['message'], $matches))
                    {
                        foreach ($matches['id'] AS $attachId)
                        {
                            $attachment = isset($message['attachments'][$attachId]) ? $message['attachments'][$attachId] :  null;
                            if($attachment != null)
                            {
                                $oMbqRdAtt = MbqMain::$oClk->newObj('MbqRdEtAtt');
                                $oMbqEtAtt = $oMbqRdAtt->initOMbqEtAtt($attachment, array('case' => 'byRow'));
                                $oMbqEtPcMsg->objsMbqEtAtt[] = $oMbqEtAtt;
                                unset($message['attachments'][$attachId]);
                            }
                        }
                    }
                }

                foreach($message['attachments'] as $attachment)
                {
                    $oMbqRdAtt = MbqMain::$oClk->newObj('MbqRdEtAtt');
                    $oMbqEtAtt = $oMbqRdAtt->initOMbqEtAtt($attachment, array('case' => 'byRow'));
                    $oMbqEtPcMsg->objsNotInContentMbqEtAtt[] = $oMbqEtAtt;
                }
            }
            $oMbqEtPcMsg->msgId->setOriValue($message['message_id']);
            $oMbqEtPcMsg->convId->setOriValue($message['conversation_id']);
            $oMbqEtPcMsg->msgTitle->setOriValue('');

            $content = $bridge->cleanPost($message['message'], $defaultOptions);
            $oMbqEtPcMsg->msgContent->setOriValue($content);
            $oMbqEtPcMsg->msgContent->setAppDisplayValue($content);
            $oMbqEtPcMsg->msgContent->setTmlDisplayValue($content);
            $oMbqEtPcMsg->msgContent->setTmlDisplayValueNoHtml($content);
            $oMbqEtPcMsg->msgAuthorId->setOriValue($message['user_id']);
            $oMbqEtPcMsg->postTime->setOriValue($message['message_date']);
            $oMbqRdEtUser = MbqMain::$oClk->newObj('MbqRdEtUser');
            $oMbqEtPcMsg->oAuthorMbqEtUser = $oMbqRdEtUser->initOMbqEtUser($oMbqEtPcMsg->msgAuthorId->oriValue, array('case' => 'byUserId'));
            return $oMbqEtPcMsg;
        }
        else if($mbqOpt['case'] == 'byPcMsgId')
        {
            $oMbqEtPc = $var;
            if( $oMbqEtPc != null && $oMbqEtPc->mbqBind)
            {
                $oMbqRdEtPc = MbqMain::$oClk->newObj('MbqRdEtPc');
                $oMbqEtPc =  $oMbqRdEtPc->initOMbqEtPc($oMbqEtPc->convId->oriValue,  array('case'=>'byConvId'));
                $conversation = $oMbqEtPc->mbqBind;
            }
            $msgId = $mbqOpt['pcMsgId'];
            $bridge = Tapatalk_Bridge::getInstance();
            $conversationModel = $bridge->getConversationModel();
            $message = $conversationModel->getConversationMessageById($msgId);
            //if(!isset($conversation))
            //{
            //    $conversation = $conversationModel->getConversationForUser($message['conversation_id']);
            //}
            //$message = $conversationModel->prepareMessage($message, $conversation);
            $defaultMessage = $conversationModel->getQuoteForConversationMessage($message);
            $message['defaultMessage'] = $defaultMessage;
            return $this->initOMbqEtPcMsg($message, array('case'=>'byRow'));
        }
    }

    function getQuoteConversation($oMbqEtPcMsg)
    {
        return $oMbqEtPcMsg->mbqBind['defaultMessage'];
    }
    function getUrl($oMbqEtPcMsg)
    {
        return XenForo_Link::buildPublicLink('full:conversations/message',
			array(
				'conversation_id' => $oMbqEtPcMsg->convId->oriValue,
				'title' => $oMbqEtPcMsg->msgTitle->oriValue,
			),
			array(
				'message_id' => $oMbqEtPcMsg->msgId->oriValue,
			));
    }
}