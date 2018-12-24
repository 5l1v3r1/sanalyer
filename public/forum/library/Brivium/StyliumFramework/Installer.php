<?php
class Brivium_StyliumFramework_Installer extends Brivium_BriviumHelper_Installer
{
	protected $_installerType = 1;
	protected $_styliumStyleId = null;
	
	protected $_frameworkId = null;
	protected $_frameworkXml = null;
	protected $_styleXml = null;
	
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
	
	protected function _preInstall()
	{
		$styliumModel = $this->getModelFromCache('Brivium_StyliumFramework_Model_Stylium');
		$existingFramework = $styliumModel->getStyleByStyliumIdOrTitle('stylium', 'Stylium Framework');
		$this->_frameworkId = 0;
		if(!empty($existingFramework['style_id'])){
			$this->_frameworkId = $existingFramework['style_id'];
		}
		$styleDataDir = XenForo_Application::getInstance()->getRootDir().'/library/Brivium/StyliumFramework/StyleData/';
		$frameworkFileName = $styleDataDir . 'style-Stylium-Framework.xml';
		if (!file_exists($frameworkFileName) || !is_readable($frameworkFileName))
		{
			throw new XenForo_Exception('Make sure <b>library/Brivium/StyliumFramework/StyleData/style-Stylium-Framework.xml</b> is existing and readable', true);
		}
		try
		{
			$this->_frameworkXml = new SimpleXMLElement($frameworkFileName, 0, true);
		}
		catch (Exception $e)
		{
			throw new XenForo_Exception(
				new XenForo_Phrase('provided_file_was_not_valid_xml_file'), true
			);
		}
	}
	protected function _postInstall()
	{
		$styleModel = $this->getModelFromCache('XenForo_Model_Style');
		$importedStyle = false;
		if($this->_frameworkXml){
			$styleModel->importStyleXml($this->_frameworkXml, 0, $this->_frameworkId);
			$styliumModel = $this->getModelFromCache('Brivium_StyliumFramework_Model_Stylium');
			$styleFramework = $styliumModel->getStyleByStyliumIdOrTitle('stylium', 'Stylium Framework');
			$this->_frameworkId = 0;
			if(!empty($styleFramework['style_id'])){
				$this->_frameworkId = $styleFramework['style_id'];
				$importedStyle = true;
			}
			
		}
		if($this->_frameworkId){
			$db = $this->_getDb();
			$db->update('xf_style', array(
				'brsf_style_id' => 'stylium'
			), 'style_id = ' . $db->quote($this->_frameworkId));
		}
	}

	protected function _postInstallAfterTransaction()
	{
		
	}
	
	public function getTables()
	{
		$tables = array();
		$tables["table_name"] = "
			CREATE TABLE IF NOT EXISTS `xf_brivium_stylium_setting` (
			  `stylium_setting_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `user_id` int(10) unsigned NOT NULL,
			  `style_id` int(11) unsigned NOT NULL,
			  `setting_param` mediumblob NOT NULL,
			  `last_update` int(10) unsigned NOT NULL DEFAULT '0',
			  PRIMARY KEY (`stylium_setting_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
		";
		return $tables;
	}
	
	public function getAlters()
	{
		$alters = array();
		$alters["xf_style"] = array(
			"brsf_style_id"	=> "VARCHAR( 25 ) NOT NULL DEFAULT  ''"
		);
		return $alters;
	}
	
	public function getQueryFinal()
	{
		$query = array();
		if($this->_triggerType != "uninstall"){
			$query[] = "
				REPLACE INTO `xf_brivium_addon` 
					(`addon_id`, `title`, `version_id`, `copyright_removal`, `start_date`, `end_date`) 
				VALUES
					('Brivium_StyliumFramework', 'Brivium - Stylium Framework', '1010000', 0, 0, 0);
			";
			$query[] = "
				REPLACE INTO `xf_brivium_listener_class` 
					(`class`, `class_extend`, `event_id`, `addon_id`) 
				VALUES
					('XenForo_DataWriter_Style', 'Brivium_StyliumFramework_DataWriter_Style', 'load_class_datawriter', 'Brivium_StyliumFramework'),
					('XenForo_Model_StyleProperty', 'Brivium_StyliumFramework_Model_StyleProperty', 'load_class_model', 'Brivium_StyliumFramework');
			";
		}else{
			$query[] = "
				DELETE FROM `xf_brivium_listener_class` WHERE `addon_id` = 'Brivium_StyliumFramework';
			";
			$query[] = "
				DELETE FROM `xf_brivium_addon` WHERE `addon_id` = 'Brivium_StyliumFramework';
			";
		}
		return $query;
	}
}
?>