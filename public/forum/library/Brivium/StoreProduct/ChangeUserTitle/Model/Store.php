<?php

/**
 * Model for Store.
 *
 * @package Brivium_Store
 */
class Brivium_StoreProduct_ChangeUserTitle_Model_Store extends XFCP_Brivium_StoreProduct_ChangeUserTitle_Model_Store
{
	public function canChangeUserTitle()
	{
		return (XenForo_Visitor::getUserId() && XenForo_Visitor::getInstance()->hasPermission('general', 'editCustomTitle'));
	}	
}

?>