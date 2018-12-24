<?php
class Brivium_StoreProduct_ChangeUserTitle_Installer extends Brivium_BriviumHelper_Installer
{
	protected $_installerType = 1;

	public static function install($existingAddOn, $addOnData)
	{
		self::$_addOnInstaller = __CLASS__;
		if (self::$_addOnInstaller && class_exists(self::$_addOnInstaller))
		{
			$installer = self::create(self::$_addOnInstaller);
			$installer->installAddOn($existingAddOn, $addOnData);
		}
		return true;
	}

	public static function uninstall($addOnData)
	{
		self::$_addOnInstaller = __CLASS__;
		if (self::$_addOnInstaller && class_exists(self::$_addOnInstaller))
		{
			$installer = self::create(self::$_addOnInstaller);
			$installer->uninstallAddOn($addOnData);
		}
	}

	protected function _getPrerequisites()
	{
        return array(
			'Brivium_Store'	=>	array(
				'title'	=>	'Brivium - Store',
				'version_id'	=>	0,
			),
		);
	}

	protected function _postInstall()
	{
		$this->rebuildCache();
	}

	protected function _postUninstall()
	{
		$dw = XenForo_DataWriter::create('Brivium_Store_DataWriter_ProductType', XenForo_DataWriter::ERROR_SILENT);
		if ($dw->setExistingData('ChangeUserTitle'))
		{
			$dw->delete();
		}
		try
		{
			$addOns = XenForo_Application::get('addOns');
			if (!empty($addOns['Brivium_Credits']) && $addOns['Brivium_Credits'] < 2000000)
			{
				$actionDw = XenForo_DataWriter::create('Brivium_Credits_DataWriter_Action', XenForo_DataWriter::ERROR_SILENT);
				if ($actionDw->setExistingData('BRS_ChangeUserTitle'))
				{
					$actionDw->delete();
				}
			}
		}
		catch (Zend_Db_Exception $e) {}
		try
		{
			$this->rebuildCache();
		}
		catch (Zend_Db_Exception $e) {}

	}
	public function rebuildCache()
	{
		XenForo_Model::create('Brivium_Store_Model_Product')->rebuildProductCache();
		XenForo_Model::create('Brivium_Store_Model_ProductType')->rebuildProductTypeCache();
	}

	public function getData()
	{
		$data = array();
		$data['xf_store_product_type'] = "
			INSERT IGNORE INTO `xf_store_product_type`
				(`product_type_id`, `title`, `purchase_type`, `active`)
			VALUES
				('ChangeUserTitle', 'Change User Title', 'only_one', 1);
		";
		return $data;
	}

	public function getQueryFinal()
	{
		$query = array();
		$query[] = "
			DELETE FROM `xf_brivium_listener_class` WHERE `addon_id` = 'BRS_ChangeUserTitle';
		";
		if($this->_triggerType != "uninstall"){
			$query[] = "
				REPLACE INTO `xf_brivium_addon`
					(`addon_id`, `title`, `version_id`, `copyright_removal`, `start_date`, `end_date`)
				VALUES
					('BRS_ChangeUserTitle', 'Brivium - Store Product Change User Title', '1000400', 0, 0, 0);
			";
			$query[] = "
				REPLACE INTO `xf_brivium_listener_class`
					(`class`, `class_extend`, `event_id`, `addon_id`)
				VALUES
					('Brivium_Store_ControllerAdmin_Product', 'Brivium_StoreProduct_ChangeUserTitle_ControllerAdmin_Product', 'load_class_controller', 'BRS_ChangeUserTitle'),
					('Brivium_Store_Model_ProductPurchase', 'Brivium_StoreProduct_ChangeUserTitle_Model_ProductPurchase', 'load_class_model', 'BRS_ChangeUserTitle'),
					('Brivium_Store_Model_Store', 'Brivium_StoreProduct_ChangeUserTitle_Model_Store', 'load_class_model', 'BRS_ChangeUserTitle'),
					('XenForo_ControllerPublic_Account', 'Brivium_StoreProduct_ChangeUserTitle_ControllerPublic_Account', 'load_class_controller', 'BRS_ChangeUserTitle');
			";
		}else{
			$query[] = "
				DELETE FROM `xf_brivium_addon` WHERE `addon_id` = 'BRS_ChangeUserTitle';
			";
		}
		return $query;
	}
}