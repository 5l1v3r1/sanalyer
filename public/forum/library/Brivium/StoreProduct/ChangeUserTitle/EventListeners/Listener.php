<?php

class Brivium_StoreProduct_ChangeUserTitle_EventListeners_Listener extends Brivium_BriviumHelper_EventListeners
{
	public static function templateHook($hookName, &$contents, array $hookParams, XenForo_Template_Abstract $template)
    {
		$param = array();
		switch($hookName){
			case 'account_wrapper_sidebar_settings':
				$storeModel = XenForo_Model::create('Brivium_Store_Model_Store');
				$param['canChangeUserTitle'] = $storeModel->canChangeUserTitle();
				$newTemplate = $template->create('BRSCUT_' . $hookName,$template->getParams());
				$newTemplate->setParams($hookParams);
				$newTemplate->setParams($param);
				$contents .= $newTemplate->render();
				break;
			case 'brs_product_tool_menu':
				$newTemplate = $template->create('BRSCUT_' . $hookName,$template->getParams());
				$newTemplate->setParams($hookParams);
				$contents .= $newTemplate->render();
				break;

		}
    }

	public static function brcActionHandler(array &$actions)
	{
		$actions['BRS_ChangeUserTitle'] = 'Brivium_StoreProduct_ChangeUserTitle_ActionHandler';
	}
}