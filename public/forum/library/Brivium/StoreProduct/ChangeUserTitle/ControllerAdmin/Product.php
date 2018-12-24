<?php

class Brivium_StoreProduct_ChangeUserTitle_ControllerAdmin_Product extends XFCP_Brivium_StoreProduct_ChangeUserTitle_ControllerAdmin_Product
{
	
	protected function _getProductAddEditResponse(array $viewParams, $editTemplate, $productTypeId)
	{
		if($productTypeId == 'ChangeUserTitle'){
			if(!$viewParams['noCreditPremium']){
				$viewParams['currencies']  = $this->_getCurrencies('BRS_ChangeUserTitle');
				if(!$viewParams['currencies']){
					$viewParams['noCreditPremium'] = true;
					$viewParams['needCreditEvent'] = true;
				}
			}
			return $this->responseView('Brivium_Store_ViewAdmin_Product_Edit', 'BRS_product_edit_change_user_title',$viewParams);
		}
		return parent::_getProductAddEditResponse($viewParams, $editTemplate, $productTypeId);
	}
	protected function _processProductWriter(Brivium_Store_DataWriter_Product $writer, $writerData, $productTypeId)
	{
		if($productTypeId == 'ChangeUserTitle'){
			$permissions = $this->_input->filterSingle('permissions', XenForo_Input::ARRAY_SIMPLE);
			$productPermission = array();
			foreach($permissions AS $permission){
				list($groupId, $permissionId) = explode("-", $permission);
				if(!isset($productPermission[$groupId])){
					$productPermission[$groupId] = array();
				}
				$productPermission[$groupId][$permissionId] = 'allow';
			}
			$writerData['permissions'] = $productPermission;
		}
		return parent::_processProductWriter($writer, $writerData, $productTypeId);
	}
}