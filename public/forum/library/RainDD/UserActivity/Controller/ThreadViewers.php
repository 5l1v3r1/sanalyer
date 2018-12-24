<?php

class RainDD_UserActivity_Controller_ThreadViewers extends XFCP_RainDD_UserActivity_Controller_ThreadViewers
{

	public function actionIndex()
	{
		$response = parent::actionIndex();
		
		if ($response instanceof XenForo_ControllerResponse_View)
		{
			$visitor = XenForo_Visitor::getInstance();
			$versionId = XenForo_Application::$versionId;
			$hasPermission =  $visitor->hasPermission('RainDD_UA_PermissionsMain', 'RainDD_UA_ThreadViewers');
			if ($response instanceof XenForo_ControllerResponse_View && $hasPermission)
			{
				$threadId = $response->params['thread']['thread_id'];
				$viewing = $this->getModelFromCache('RainDD_UserActivity_Model_ThreadViewers');
				$response->params += array('RainDD_UA_ThreadUsersViewing' => $viewing->getUsersViewingThread($threadId, $versionId),
											'RainDD_UA_ThreadViewerPermission' => $hasPermission,
				);
			}
			return $response;         
		}
		else
		{
			return $response;
		}				        
	}
}