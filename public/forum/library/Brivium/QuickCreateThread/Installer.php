<?php
class Brivium_QuickCreateThread_Installer extends Brivium_BriviumHelper_Installer
{
	protected $_installerType = 2;

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

	public function getAlters()
	{
		$alters = array();

		$alters["xf_forum"] = array(
			"brqct_select" 			=> "varchar(25) NOT NULL DEFAULT 'useDefault'",
			"brqct_url_1" 			=> "text NULL",
			"brqct_url_2"			=> "text NULL",
			"brqct_icon_date_1" 	=> "int(10) unsigned DEFAULT NULL",
			"brqct_icon_date_2"		=> "int(10) unsigned DEFAULT NULL",
		);

		$alters["xf_page"] = array(
			"brqct_select" 			=> "varchar(25) NOT NULL DEFAULT 'useDefault'",
			"brqct_url"				=> "text NULL",
			"brqct_icon_date" 		=> "int(10) unsigned DEFAULT NULL",
		);

		$alters["xf_link_forum"] = array(
			"brqct_select" 			=> "varchar(25) NOT NULL DEFAULT 'useDefault'",
			"brqct_url"				=> "text NULL",
			"brqct_icon_date" 		=> "int(10) unsigned DEFAULT NULL",
		);
		$alters["xf_node"] = array(
			"brqct_select" 			=> "varchar(25) NOT NULL DEFAULT 'useDefault'",
			"brqct_url"				=> "text NULL",
			"brqct_icon_date" 		=> "int(10) unsigned DEFAULT NULL",
		);
		return $alters;
	}

	public function getQueryFinal()
	{
		$query = array();
		$query[] = "
			DELETE FROM `xf_brivium_listener_class` WHERE `addon_id` = 'Brivium_Tellurium';
		";
		if($this->_triggerType != "uninstall"){
			$query[] = "
				REPLACE INTO `xf_brivium_addon` 
					(`addon_id`, `title`, `version_id`, `copyright_removal`, `start_date`, `end_date`) 
				VALUES
					('Brivium_Tellurium', 'Brivium - Tellurium', '1010300', 0, 0, 0);
			";
			$query[] = "
				REPLACE INTO `xf_brivium_listener_class` 
					(`class`, `class_extend`, `event_id`, `addon_id`) 
				VALUES
					('XenForo_ControllerAdmin_Category', 'Brivium_QuickCreateThread_ControllerAdmin_Category', 'load_class_controller', 'Brivium_Tellurium'),
					('XenForo_ControllerAdmin_Forum', 'Brivium_QuickCreateThread_ControllerAdmin_Forum', 'load_class_controller', 'Brivium_Tellurium'),
					('XenForo_ControllerAdmin_LinkForum', 'Brivium_QuickCreateThread_ControllerAdmin_LinkForum', 'load_class_controller', 'Brivium_Tellurium'),
					('XenForo_ControllerAdmin_Page', 'Brivium_QuickCreateThread_ControllerAdmin_Page', 'load_class_controller', 'Brivium_Tellurium'),
					('XenForo_ControllerPublic_Forum', 'Brivium_QuickCreateThread_ControllerPublic_Forum', 'load_class_controller', 'Brivium_Tellurium'),
					('XenForo_DataWriter_Category', 'Brivium_QuickCreateThread_DataWriter_Category', 'load_class_datawriter', 'Brivium_Tellurium'),
					('XenForo_DataWriter_Forum', 'Brivium_QuickCreateThread_DataWriter_Forum', 'load_class_datawriter', 'Brivium_Tellurium'),
					('XenForo_DataWriter_LinkForum', 'Brivium_QuickCreateThread_DataWriter_LinkForum', 'load_class_datawriter', 'Brivium_Tellurium'),
					('XenForo_DataWriter_Page', 'Brivium_QuickCreateThread_DataWriter_Page', 'load_class_datawriter', 'Brivium_Tellurium'),
					('XenForo_NodeHandler_LinkForum', 'Brivium_QuickCreateThread_NodeHandler_LinkForum', 'load_class', 'Brivium_Tellurium'),
					('XenForo_NodeHandler_Page', 'Brivium_QuickCreateThread_NodeHandler_Page', 'load_class', 'Brivium_Tellurium');
			";
		}else{
			$query[] = "
				DELETE FROM `xf_brivium_addon` WHERE `addon_id` = 'Brivium_Tellurium';
			";
		}
		return $query;
	}
}

?>