<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseActMDeletePost');

/**
 * m_delete_post action
 */
Class MbqActMDeletePost extends MbqBaseActMDeletePost {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * action implement
     */
    public function actionImplement($in) {
        parent::actionImplement($in);
    }
  
}