<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseAclEtAtt');

/**
 * attachment acl class
 */
Class MbqAclEtAtt extends MbqBaseAclEtAtt {

    public function __construct() {
    }
    /**
     * judge can upload attachment
     *
     * @param  Object  $oMbqEtForum
     * @return  Boolean
     */
    public function canAclUploadAttach($oMbqEtForumOrConvPm, $groupId, $type) {
        $bridge = Tapatalk_Bridge::getInstance();

        $contentType = 'post';
        $contentData=array();
        if(isset($type) && $type == 'pm'){
            $contentType = 'conversation_message';
            if(is_a($oMbqEtForumOrConvPm,'MbqEtPc'))
            {
                $contentData['conversation_id'] = $oMbqEtForumOrConvPm->convId->oriValue;
            }
        }else {
            $contentData ['node_id'] = $oMbqEtForumOrConvPm->forumId->oriValue;
        }

        $attachmentModel = $bridge->getAttachmentModel();

        $attachmentHandler = $attachmentModel->getAttachmentHandler($contentType);
        if (!$attachmentHandler || !$attachmentHandler->canUploadAndManageAttachments($contentData))
        {
            return TT_GetPhraseString('do_not_have_permission');
        }
        return true;
    }

    /**
     * judge can remove attachment
     *
     * @param  Object  $oMbqEtAtt
     * @param  Object  $oMbqEtForum
     * @return  Boolean
     */
    public function canAclRemoveAttachment($oMbqEtAtt, $oMbqEtForum) {
        $bridge = Tapatalk_Bridge::getInstance();
        $visitor = XenForo_Visitor::getInstance();

        $attachment = $bridge->getAttachmentModel()->getAttachmentById($oMbqEtAtt->attId->oriValue);
        if (!$attachment)
        {
            return TT_GetPhraseString('requested_attachment_not_found');
        }

        if (!$bridge->getAttachmentModel()->canDeleteAttachment($attachment, $oMbqEtAtt->groupId->oriValue))
        {
            return TT_GetPhraseString('do_not_have_permission');
        }
        return true;
    }
}