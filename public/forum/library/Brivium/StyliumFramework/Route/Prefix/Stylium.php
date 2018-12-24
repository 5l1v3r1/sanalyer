<?php
class Brivium_StyliumFramework_Route_Prefix_Stylium implements XenForo_Route_Interface
{
	public function match($routePath, Zend_Controller_Request_Http $request, XenForo_Router $router)
	{
		return $router->getRouteMatch('Brivium_StyliumFramework_ControllerPublic_Stylium', $routePath, 'BR_StyliumFramework');
	}
}