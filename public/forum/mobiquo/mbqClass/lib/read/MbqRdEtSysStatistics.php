<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseRdEtSysStatistics');

/**
 * system statistics read class
 */
Class MbqRdEtSysStatistics extends MbqBaseRdEtSysStatistics {
    
    public function __construct() {
    }
    
    public function makeProperty(&$oMbqEtSysStatistics, $pName, $mbqOpt = array()) {
        switch ($pName) {
            default:
            MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_PNAME . ':' . $pName . '.');
            break;
        }
    }
    public function initOMbqEtSysStatistics() {
        $oMbqEtSysStatistics = MbqMain::$oClk->newObj('MbqEtSysStatistics');
        $stats = array();
        $bridge = Tapatalk_Bridge::getInstance();
        
        $boardTotals = $bridge->getModelFromCache('XenForo_Model_DataRegistry')->get('boardTotals');
        if (!$boardTotals)
        {
            $boardTotals = $bridge->getModelFromCache('XenForo_Model_Counters')->rebuildBoardTotalsCounter();
        }
        
        $visitor = XenForo_Visitor::getInstance();
        
        $sessionModel = $bridge->getModelFromCache('XenForo_Model_Session');
        
        $onlineUsers = $sessionModel->getSessionActivityQuickList(
            $visitor->toArray(),
            array('cutOff' => array('>', $sessionModel->getOnlineStatusTimeout())),
            ($visitor['user_id'] ? $visitor->toArray() : null)
        );
        
        $oMbqEtSysStatistics->forumTotalThreads->setOriValue($boardTotals['discussions']);
        $oMbqEtSysStatistics->forumTotalPosts->setOriValue($boardTotals['messages']);
        $oMbqEtSysStatistics->forumTotalMembers->setOriValue($boardTotals['users']);
        $oMbqEtSysStatistics->forumActiveMembers->setOriValue($boardTotals['users']);
        $oMbqEtSysStatistics->forumTotalOnline->setOriValue($onlineUsers['members'] + $onlineUsers['guests']);
        $oMbqEtSysStatistics->forumGuestOnline->setOriValue($onlineUsers['guests']);
        return $oMbqEtSysStatistics;
    }
}