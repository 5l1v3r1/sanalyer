<?php

class Dark_TaigaChat_EventListener_FrontControllerPreView
{
	public static function listen(XenForo_FrontController $fc, XenForo_ControllerResponse_Abstract &$controllerResponse, XenForo_ViewRenderer_Abstract &$viewRenderer, array &$containerParams)
	{		
		$options = XenForo_Application::get('options');
		
		if($options->dark_taigachat_globalhook || $controllerResponse->controllerName == 'Dark_TaigaChat_ControllerPublic_TaigaChat' || $controllerResponse->controllerName == 'XenForo_ControllerPublic_Index'){
			$action = $controllerResponse->controllerAction;
			$action[0] = strtolower($action[0]);
			Dark_TaigaChat_Helper_Global::getTaigaChatStuff($controllerResponse, $action);
		}
	}
}