<?php

defined('MBQ_IN_IT') or exit;

/**
 * private conversation message read class
 */
Abstract Class MbqBaseRdEtPcMsg extends MbqBaseRd {

    public function __construct() {
    }

    /**
     * return private conversation message api data
     *
     * @param  Object  $oMbqEtPcMsg
     * @param  Boolean  $returnHtml
     * @return  Array
     */
    public function returnApiDataPcMsg($oMbqEtPcMsg, $returnHtml = true) {
        $data = array();
        if ($oMbqEtPcMsg->msgId->hasSetOriValue()) {
            $data['msg_id'] = (string) $oMbqEtPcMsg->msgId->oriValue;
        }
        if ($returnHtml) {
            if ($oMbqEtPcMsg->msgContent->hasSetTmlDisplayValue()) {
                $data['msg_content'] = (string) $oMbqEtPcMsg->msgContent->tmlDisplayValue;
            }
        } else {
            if ($oMbqEtPcMsg->msgContent->hasSetTmlDisplayValueNoHtml()) {
                $data['msg_content'] = (string) $oMbqEtPcMsg->msgContent->tmlDisplayValueNoHtml;
            }
        }
        if ($oMbqEtPcMsg->msgAuthorId->hasSetOriValue()) {
            $data['msg_author_id'] = (string) $oMbqEtPcMsg->msgAuthorId->oriValue;
        }
        if ($oMbqEtPcMsg->isUnread->hasSetOriValue()) {
            $data['is_unread'] = (boolean) $oMbqEtPcMsg->isUnread->oriValue;
        }
        if ($oMbqEtPcMsg->oAuthorMbqEtUser && $oMbqEtPcMsg->oAuthorMbqEtUser->isOnline->hasSetOriValue()) {
            $data['is_online'] = (boolean) $oMbqEtPcMsg->oAuthorMbqEtUser->isOnline->oriValue;
        }
        if ($oMbqEtPcMsg->oAuthorMbqEtUser && $oMbqEtPcMsg->oAuthorMbqEtUser->userType->hasSetOriValue()) {
            $data['msg_author_user_type'] = (string) $oMbqEtPcMsg->oAuthorMbqEtUser->userType->oriValue;
        }
        if ($oMbqEtPcMsg->hasLeft->hasSetOriValue()) {
            $data['has_left'] = (boolean) $oMbqEtPcMsg->hasLeft->oriValue;
        }
        if ($oMbqEtPcMsg->postTime->hasSetOriValue()) {
            $data['post_time'] = (string) MbqMain::$oMbqCm->datetimeIso8601Encode($oMbqEtPcMsg->postTime->oriValue);
        }
        if ($oMbqEtPcMsg->newPost->hasSetOriValue()) {
            $data['new_post'] = (boolean) $oMbqEtPcMsg->newPost->oriValue;
        }

        /* attachments */
        $oMbqRdEtAtt = MbqMain::$oClk->newObj('MbqRdEtAtt');
        $data['attachments'] = (array) $oMbqRdEtAtt->returnApiArrDataAttachment($oMbqEtPcMsg->objsNotInContentMbqEtAtt);
        /* inline attachments*/
        $oMbqRdEtAtt = MbqMain::$oClk->newObj('MbqRdEtAtt');
        $data['inlineattachments'] = (array) $oMbqRdEtAtt->returnApiArrDataAttachment($oMbqEtPcMsg->objsMbqEtAtt);
        return $data;
    }

    /**
     * return private conversation message array api data
     *
     * @param  Array  $objsMbqEtPcMsg
     * @param  Boolean  $returnHtml
     * @return  Array
     */
    public function returnApiArrDataPcMsg($objsMbqEtPcMsg, $returnHtml = true) {
        $data = array();
        foreach ($objsMbqEtPcMsg as $oMbqEtPcMsg) {
            $data[] = $this->returnApiDataPcMsg($oMbqEtPcMsg, $returnHtml);
        }
        return $data;
    }

    /**
     * get private conversation message objs
     *
     * @return  Mixed
     */
    public function getObjsMbqEtPcMsg($var, $mbqOpt) {
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NEED_ACHIEVE_IN_INHERITED_CLASSE);
    }

    /**
     * init one private conversation message by condition
     *
     * @return  Mixed
     */
    public function initOMbqEtPcMsg($var, $mbqOpt) {
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NEED_ACHIEVE_IN_INHERITED_CLASSE);
    }

    /**
     * get_quote_conversation
     *
     * @return  Mixed
     */
    public function getQuoteConversation($oMbqEtPcMsg) {
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NEED_ACHIEVE_IN_INHERITED_CLASSE);
    }
    /**
     * This function should return the real url of the private message following any seo rules forum have
     *
     * @param mixed $oMbqEtPcMsg
     */
    public function getUrl($oMbqEtPcMsg)
    {
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NEED_ACHIEVE_IN_INHERITED_CLASSE);
    }
}
