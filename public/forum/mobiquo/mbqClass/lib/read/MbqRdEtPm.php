<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseRdEtPm');

/**
 * private message read class
 */
Class MbqRdEtPm extends MbqBaseRdEtPm {
    
    public function __construct() {
    }
    
    public function makeProperty(&$oMbqEtPm, $pName, $mbqOpt = array()) {
        switch ($pName) {
            default:
            MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_PNAME . ':' . $pName . '.');
            break;
        }
    }
  
}