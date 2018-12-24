<?php

class RainDD_UserActivity_Model_ThreadViewers extends XenForo_Model
{

	public function getUsersViewingThread($threadid)
	{
		$visitor = XenForo_Visitor::getInstance();
		$bypassUserPrivacy = $this->getModelFromCache('XenForo_Model_User')->canBypassUserPrivacy();
		$options = XenForo_Application::get('options');
		
		
		if ($options->RainDD_UA_ThreadViewTapatalk == 0 || $options->RainDD_UA_ThreadViewTapatalk == 1 && $bypassUserPrivacy)
		{
			$tapatalk = true;
		}
		else
		{
			$tapatalk = false;
		}
		
		if ($options->RainDD_UA_ThreadForceInclude == 0)
		{
		$forceinclude = ($visitor['user_id'] ? $visitor->toArray() : null);	
		}
		else
		{
		$forceinclude = null;
		}
		
		
		$sessionModel = $this->getModelFromCache('XenForo_Model_Session');

		return $this->getModelFromCache('RainDD_UserActivity_Model_ThreadSession')->getSessionActivityQuickList(
			$visitor->toArray(),
			array(
				'cutOff' 			=> array('>', $sessionModel->getOnlineStatusTimeout()),
				'version' 			=> $options->currentVersionId,
				'tapatalk'			=> $tapatalk,
				'threadid'			=> $threadid,
				'activityvisible'	=> $bypassUserPrivacy,
				'getInvisible'		=> $bypassUserPrivacy,
				'getUnconfirmed'	=> $bypassUserPrivacy,
			),
			$forceinclude
		);
	}
	
}