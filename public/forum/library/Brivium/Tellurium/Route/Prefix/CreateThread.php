<?php
class Brivium_Tellurium_Route_Prefix_CreateThread implements XenForo_Route_Interface
{
	public function match($routePath, Zend_Controller_Request_Http $request, XenForo_Router $router)
	{
		return $router->getRouteMatch('Brivium_Tellurium_ControllerPublic_Index', $routePath, 'forums');
	}
}