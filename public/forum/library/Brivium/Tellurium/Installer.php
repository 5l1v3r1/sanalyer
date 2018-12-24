<?php
class Brivium_Tellurium_Installer extends Brivium_BriviumHelper_Installer
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
	
	public function getQueryBeforeTable()
	{
		$query = array();
		if(!empty($this->_existingVersionId) && $this->_existingVersionId < 1010300)
		{
			$query[] = "ALTER TABLE `xf_forum` CHANGE  `brqct_select`  `brt_select` varchar(25) NOT NULL DEFAULT 'useDefault';";
			$query[] = "ALTER TABLE `xf_forum` CHANGE  `brqct_url_1`  `brt_url_1` text NULL;";
			$query[] = "ALTER TABLE `xf_forum` CHANGE  `brqct_url_2`  `brt_url_2` text NULL;";
			$query[] = "ALTER TABLE `xf_forum` CHANGE  `brqct_icon_date_1`  `brt_icon_date_1` int(10) unsigned DEFAULT NULL';";
			$query[] = "ALTER TABLE `xf_forum` CHANGE  `brqct_icon_date_2`  `brt_icon_date_2` int(10) unsigned DEFAULT NULL';";
			
			$query[] = "ALTER TABLE `xf_page` CHANGE  `brqct_select`  `brt_select` varchar(25) NOT NULL DEFAULT 'useDefault'";
			$query[] = "ALTER TABLE `xf_page` CHANGE  `brqct_url`  `brt_url` text NULL";
			$query[] = "ALTER TABLE `xf_page` CHANGE  `brqct_icon_date`  `int(10) unsigned DEFAULT NULL";
			
			$query[] = "ALTER TABLE `xf_link_forum` CHANGE  `brqct_select`  `brt_select` varchar(25) NOT NULL DEFAULT 'useDefault'";
			$query[] = "ALTER TABLE `xf_link_forum` CHANGE  `brqct_url`  `brt_url` text NULL";
			$query[] = "ALTER TABLE `xf_link_forum` CHANGE  `brqct_icon_date`  `int(10) unsigned DEFAULT NULL";
			
			$query[] = "ALTER TABLE `xf_node` CHANGE  `brqct_select`  `brt_select` varchar(25) NOT NULL DEFAULT 'useDefault'";
			$query[] = "ALTER TABLE `xf_node` CHANGE  `brqct_url`  `brt_url` text NULL";
			$query[] = "ALTER TABLE `xf_node` CHANGE  `brqct_icon_date`  `int(10) unsigned DEFAULT NULL";
		}
		return $query;
	}

	public function getAlters()
	{
		$alters = array();

		$alters["xf_forum"] = array(
			"brt_select" 			=> "varchar(25) NOT NULL DEFAULT 'useDefault'",
			"brt_url_1" 			=> "text NULL",
			"brt_url_2"				=> "text NULL",
			"brt_icon_date_1" 		=> "int(10) unsigned DEFAULT NULL",
			"brt_icon_date_2"		=> "int(10) unsigned DEFAULT NULL",
		);

		$alters["xf_page"] = array(
			"brt_select" 			=> "varchar(25) NOT NULL DEFAULT 'useDefault'",
			"brt_url"				=> "text NULL",
			"brt_icon_date" 		=> "int(10) unsigned DEFAULT NULL",
		);

		$alters["xf_link_forum"] = array(
			"brt_select" 			=> "varchar(25) NOT NULL DEFAULT 'useDefault'",
			"brt_url"				=> "text NULL",
			"brt_icon_date" 		=> "int(10) unsigned DEFAULT NULL",
		);
		$alters["xf_node"] = array(
			"brt_select" 			=> "varchar(25) NOT NULL DEFAULT 'useDefault'",
			"brt_url"				=> "text NULL",
			"brt_icon_date" 		=> "int(10) unsigned DEFAULT NULL",
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
					('XenForo_ControllerAdmin_Category', 'Brivium_Tellurium_ControllerAdmin_Category', 'load_class_controller', 'Brivium_Tellurium'),
					('XenForo_ControllerAdmin_Forum', 'Brivium_Tellurium_ControllerAdmin_Forum', 'load_class_controller', 'Brivium_Tellurium'),
					('XenForo_ControllerAdmin_LinkForum', 'Brivium_Tellurium_ControllerAdmin_LinkForum', 'load_class_controller', 'Brivium_Tellurium'),
					('XenForo_ControllerAdmin_Page', 'Brivium_Tellurium_ControllerAdmin_Page', 'load_class_controller', 'Brivium_Tellurium'),
					('XenForo_ControllerPublic_Forum', 'Brivium_Tellurium_ControllerPublic_Forum', 'load_class_controller', 'Brivium_Tellurium'),
					('XenForo_DataWriter_Category', 'Brivium_Tellurium_DataWriter_Category', 'load_class_datawriter', 'Brivium_Tellurium'),
					('XenForo_DataWriter_Forum', 'Brivium_Tellurium_DataWriter_Forum', 'load_class_datawriter', 'Brivium_Tellurium'),
					('XenForo_DataWriter_LinkForum', 'Brivium_Tellurium_DataWriter_LinkForum', 'load_class_datawriter', 'Brivium_Tellurium'),
					('XenForo_DataWriter_Page', 'Brivium_Tellurium_DataWriter_Page', 'load_class_datawriter', 'Brivium_Tellurium'),
					('XenForo_NodeHandler_LinkForum', 'Brivium_Tellurium_NodeHandler_LinkForum', 'load_class', 'Brivium_Tellurium'),
					('XenForo_NodeHandler_Page', 'Brivium_Tellurium_NodeHandler_Page', 'load_class', 'Brivium_Tellurium');
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