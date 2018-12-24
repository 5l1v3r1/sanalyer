<?php

class Brivium_StoreProduct_ChangeUserTitle_ControllerPublic_Account extends XFCP_Brivium_StoreProduct_ChangeUserTitle_ControllerPublic_Account
{
	## ------------------------------------------
	##
	## Change User Title
	##

	public function actionChangeUserTitle()
	{
		$storeModel = $this->_getStoreModel();
		if (!$storeModel->canChangeUserTitle())
		{
			return $this->responseNoPermission();
		}
		$userId = XenForo_Visitor::getUserId();
		$conditions = array(
			'product_type_id'	=> 'ChangeUserTitle',
			'active'			=> true,
			'user_id'			=> $userId,
		);
		$productActives = $this->_getProductPurchaseModel()->getProductPurchaseRecords($conditions);
		
		if(!$productActives) return $this->responseError(new XenForo_Phrase('BRS_you_did_not_buy_any_product_of_this_type'));
		$purchased = reset($productActives);
		$purchased['extra'] = unserialize($purchased['extra']);
		$viewParams = array(
			'purchased' => $purchased,
		);
		
		return $this->_getWrapper(
			'account', 'BRS_changeUserTitle',
			$this->responseView(
				'XenForo_ViewPublic_Account_ChangeUserTitle',
				'BRSCUT_account_change_usertitle',
				$viewParams
			)
		);
	}

	public function actionChangeUserTitleSave()
	{
		$this->_assertPostOnly();
		$storeModel = $this->_getStoreModel();
		if (!$storeModel->canChangeUserTitle())
		{
			return $this->responseNoPermission();
		}
		$userId = XenForo_Visitor::getUserId();
		$customTitle = XenForo_Visitor::getInstance()->get('custom_title');
		$data = $this->_input->filter(array(
			'custom_title'   => XenForo_Input::STRING,
		));
		if($data['custom_title']!=$customTitle){
			$writer = XenForo_DataWriter::create('XenForo_DataWriter_User');
			if (!$writer = $this->_saveVisitorSettings($data, $errors))
			{
				return $this->responseError($errors);
			}else{
				if($writer->isChanged('custom_title')){
					$productPurchaseId = $this->_input->filterSingle('product_purchase_id', XenForo_Input::UINT);
					$purchased = $this->_getProductPurchaseModel()->getActiveProductPurchaseById($productPurchaseId);
					if(!$purchased || $purchased['user_id']!=$userId){
						return $this->responseError(new XenForo_Phrase('BRS_you_did_not_buy_any_product_of_this_type'));
					}
					$product = $this->getModelFromCache('Brivium_Store_Model_Product')->getProductById($purchased['product_id']);
					$product['purchase'] = $purchased;
					$this->_getProductPurchaseModel()->productUsing($userId,$product);
				}
			}
		}
		return $this->responseRedirect(
			XenForo_ControllerResponse_Redirect::SUCCESS,
			XenForo_Link::buildPublicLink('account/change-user-title')
		);
	}

	protected function _getStoreModel()
	{
		return $this->getModelFromCache('Brivium_Store_Model_Store');
	}
	
	/**
	 * Gets the product purchase model.
	 *
	 * @return Brivium_Store_Model_ProductPurchase
	 */
	protected function _getProductPurchaseModel()
	{
		return $this->getModelFromCache('Brivium_Store_Model_ProductPurchase');
	}
}