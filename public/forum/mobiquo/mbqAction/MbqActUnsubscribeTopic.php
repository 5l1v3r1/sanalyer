<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseActUnsubscribeTopic');

/**
 * unsubscribe_topic action
 */
Class MbqActUnsubscribeTopic extends MbqBaseActUnsubscribeTopic {
    
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