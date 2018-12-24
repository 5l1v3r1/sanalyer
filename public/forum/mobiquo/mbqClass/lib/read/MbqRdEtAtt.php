<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseRdEtAtt');

/**
 * attachment read class
 */
Class MbqRdEtAtt extends MbqBaseRdEtAtt {

    public function __construct() {
    }

    public function makeProperty(&$oMbqEtAtt, $pName, $mbqOpt = array()) {
        switch ($pName) {
            default:
            MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_PNAME . ':' . $pName . '.');
            break;
        }
    }
    public function initOMbqEtAtt($var = null, $mbqOpt = array()) {
        if ($mbqOpt['case'] == 'byAttId') {
            $bridge = Tapatalk_Bridge::getInstance();
            $attachmentModel = $bridge->getAttachmentModel();
            $attachment = $attachmentModel->getAttachmentById($var);
            if($attachment)
            {
                $oMbqAttr = $this->initOMbqEtAtt($attachment, array('case' => 'byRow'));
                return $oMbqAttr;
            }
            return null;
        }
        else if ($mbqOpt['case'] == 'byRow') {
            $bridge = Tapatalk_Bridge::getInstance();
            $attachment = $var;

            $type = isset($attachment['extension']) ? $attachment['extension'] : pathinfo($attachment['filename'], PATHINFO_EXTENSION);;

            switch($type){
                case 'gif':
                case 'jpg':
                case 'png':
                    $type = MbqBaseFdt::getFdt('MbqFdtAtt.MbqEtAtt.contentType.range.image');;
                    break;
                case 'pdf':
                    $type =  MbqBaseFdt::getFdt('MbqFdtAtt.MbqEtAtt.contentType.range.pdf');
                    break;
            }
            $attachmentModel = $bridge->getAttachmentModel();
            $canViewUrl = $attachmentModel->canViewAttachment($attachment);
            $thumbnail = '';
            if(isset($attachment['thumbnailUrl']) && !empty($attachment['thumbnailUrl']))
            {
                $thumbnail = XenForo_Link::convertUriToAbsoluteUri($attachment['thumbnailUrl'], true);
            }

            $oMbqEtAtt = MbqMain::$oClk->newObj('MbqEtAtt');
            $oMbqEtAtt->attId->setOriValue($attachment['attachment_id']);
            $oMbqEtAtt->filtersSize->setOriValue($attachment['file_size']);
            $oMbqEtAtt->uploadFileName->setOriValue($attachment['filename']);
            $oMbqEtAtt->contentType->setOriValue($type);
            $oMbqEtAtt->url->setOriValue(XenForo_Link::convertUriToAbsoluteUri(XenForo_Link::buildPublicLink('attachments', $attachment), true));
            $oMbqEtAtt->thumbnailUrl->setOriValue($thumbnail);
            $oMbqEtAtt->canViewThumbnailUrl->setOriValue($oMbqEtAtt->url->oriValue == $oMbqEtAtt->thumbnailUrl->oriValue ? $canViewUrl : $oMbqEtAtt->thumbnailUrl->oriValue != '');
            $oMbqEtAtt->canViewUrl->setOriValue($canViewUrl);
            $oMbqEtAtt->mbqBind = $attachment;
            return $oMbqEtAtt;
        }
    }
}