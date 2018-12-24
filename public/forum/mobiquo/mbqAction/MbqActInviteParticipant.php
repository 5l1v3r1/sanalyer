<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseActInviteParticipant');

/**
 * invite_participant action
 */
Class MbqActInviteParticipant extends MbqBaseActInviteParticipant {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * action implement
     */
    public function actionImplement($in) {
        parent::actionImplement($in);

        if (isset($this->data['result']) && $this->data['result']){
            $invited_user_ids = '';
            $oMbqRdEtUser = MbqMain::$oClk->newObj('MbqRdEtUser');
            foreach ($in->usernames as $userName) {
                if ($oMbqEtUser = $oMbqRdEtUser->initOMbqEtUser($userName, array('case' => 'byLoginName'))) {
                    $oMbqEtPcInviteParticipant->objsMbqEtUser[] = $oMbqEtUser;
                    $invited_user_ids .= $oMbqEtUser->userId->oriValue . ',';
                }
            }
            $invited_user_ids = trim($invited_user_ids, ' ,');
            $_REQUEST['tapatalk_invited_user_ids'] = $invited_user_ids;
        }
    }
  
}
