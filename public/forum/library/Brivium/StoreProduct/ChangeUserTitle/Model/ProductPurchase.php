<?php

/**
 * Model for ProductPurchase.
 *
 * @package Brivium_Store
 */
class Brivium_StoreProduct_ChangeUserTitle_Model_ProductPurchase extends XFCP_Brivium_StoreProduct_ChangeUserTitle_Model_ProductPurchase
{
	public function getCreditActionId($productTypeId)
	{
		if($productTypeId=='ChangeUserTitle'){
			return 'BRS_ChangeUserTitle';
		}
		return parent::getCreditActionId($productTypeId);
	}
	
	protected function _processProductChange($user, array $product, $productTypeId, $existingPurchased = null){
		if($productTypeId=='ChangeUserTitle' && !empty($product['permissions'])){
			$permissionModel = $this->_getPermissionModel();
			$productPermissions = unserialize($product['permissions']);
			if(!$existingPurchased){
				$oldPermissions = array();
				$userPermissions = $permissionModel->getAllPermissionsWithValues(0, $user['user_id']);
				foreach ($userPermissions AS $permission)
				{
					if($permission['permission_type']=='flag' && isset($productPermissions[$permission['permission_group_id']][$permission['permission_id']])){
						$oldPermissions[$permission['permission_group_id']][$permission['permission_id']] = $permission['permission_value']?$permission['permission_value']:'unset';
					}
				}
				$this->logProductChange($user['user_id'], $product['product_id'], $oldPermissions);
			}
			$this->_getPermissionModel()->updateGlobalPermissionsForUserCollection($productPermissions, 0, $user['user_id']);
			return true;
		}
		return parent::_processProductChange($user, $product, $productTypeId);
	}
	
	protected function _removeProductChange($purchase){
		if(!empty($purchase['product_id']) && $purchase['product_type_id'] == 'ChangeUserTitle'){
			$product = XenForo_Application::get('brsProducts')->$purchase['product_id'];
			if(!$product) return;
			$productChange = $this->getLogProductChange($purchase['user_id'], $purchase['product_id']);
			if(!empty($productChange['change_data'])){
				$this->_getPermissionModel()->updateGlobalPermissionsForUserCollection($productChange['change_data'], 0, $purchase['user_id']);
				$this->deleteProductChange($purchase['user_id'], $purchase['product_id']);
			}
		}
		return parent::_removeProductChange($purchase);
	}
	
	/**
	 * Gets the permission model.
	 *
	 * @return XenForo_Model_Permission
	 */
	protected function _getPermissionModel()
	{
		return $this->getModelFromCache('XenForo_Model_Permission');
	}
}

?>