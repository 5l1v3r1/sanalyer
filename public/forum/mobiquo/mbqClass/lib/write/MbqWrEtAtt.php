<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseWrEtAtt');

/**
 * attachment write class
 */
Class MbqWrEtAtt extends MbqBaseWrEtAtt {

    public function __construct() {
    }
    /**
     * upload attachment
     */
    public function uploadAttachment($oMbqEtForumOrConvPm, $groupId, $type) {
        $bridge = Tapatalk_Bridge::getInstance();
        $visitor = XenForo_Visitor::getInstance();

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

        if(empty($groupId))
            $hash = md5(uniqid('', true));
        else
            $hash = $groupId;

        $attachmentModel = $bridge->getAttachmentModel();
        $attachmentHandler = $attachmentModel->getAttachmentHandler($contentType);
        $contentId = $attachmentHandler->getContentIdFromContentData($contentData);

        $existingAttachments = ($contentId
            ? $attachmentModel->getAttachmentsByContentId($contentType, $contentId)
            : array()
        );

        $maxAttachments = $attachmentHandler->getAttachmentCountLimit();
        if ($maxAttachments !== true)
        {
            $remainingUploads = $maxAttachments - count($existingAttachments);
            if ($remainingUploads <= 0)
            {
                return new XenForo_Phrase(
                    'you_may_not_upload_more_files_with_message_allowed_x',
                    array('total' => $maxAttachments)
                );
            }
        }

        $attachmentConstraints = $attachmentModel->getAttachmentConstraints();

        $file = XenForo_Upload::getUploadedFile('attachment');
        if (!$file)
        {
            return new XenForo_Phrase('dark_upload_failed');
        }

        $file->setConstraints($attachmentConstraints);
        if (!$file->isValid())
        {
            $errors = $file->getErrors();
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
        $dataId = $attachmentModel->insertUploadedAttachmentData($file, XenForo_Visitor::getUserId());
        $attachmentId = $attachmentModel->insertTemporaryAttachment($dataId, $hash);

        $oMbqRdEtAtt = MbqMain::$oClk->newObj('MbqRdEtAtt');
        $oMbqEtAtt = $oMbqRdEtAtt->initOMbqEtAtt($attachmentId, array('case'=>'byAttId'));
        $oMbqEtAtt->groupId->setOriValue($hash);

        return $oMbqEtAtt;
    }

    /**
     * delete attachment
     */
    public function deleteAttachment($oMbqEtAtt) {

        $bridge = Tapatalk_Bridge::getInstance();
        $visitor = XenForo_Visitor::getInstance();

        $attachment = $bridge->getAttachmentModel()->getAttachmentById($oMbqEtAtt->attId->oriValue);
        $dw = XenForo_DataWriter::create('XenForo_DataWriter_Attachment');
        $dw->setExistingData($attachment, true);
        $dw->delete();

        return $oMbqEtAtt->groupId->oriValue;
    }
}