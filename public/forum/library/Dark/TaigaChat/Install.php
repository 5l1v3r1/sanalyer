<?php

class Dark_TaigaChat_Install
{
	/**
	 * Instance manager.
	 *
	 * @var Dark_TaigaChat_Install
	 */
	private static $_instance;

	/**
	 * Database object
	 *
	 * @var Zend_Db_Adapter_Abstract
	 */
	protected $_db;

	/**
	 * Gets the installer instance.
	 *
	 * @return Dark_TaigaChat_Install
	 */
	public static final function getInstance()
	{
		if (!self::$_instance)
		{
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Helper method to get the database object.
	 *
	 * @return Zend_Db_Adapter_Abstract
	 */
	protected function _getDb()
	{
		if ($this->_db === null)
		{
			$this->_db = XenForo_Application::get('db');
		}

		return $this->_db;
	}

	/**
	 * Begins the installation process and picks the proper install routine.
	 *
	 * See see XenForo_Model_Addon::installAddOnXml() for more details about
	 * the arguments passed to this method.
	 *
	 * @param array Information about the existing version (if upgrading)
	 * @param array Information about the current version being installed
	 *
	 * @return void
	 */
	public static function install($existingAddOn, $addOnData)
	{
		// the version IDs from which we should start/end the install process
		$startVersionId = 1;
		$endVersionId = $addOnData['version_id'];

		if ($existingAddOn)
		{
			// we are upgrading, run every install method since last upgrade
			$startVersionId = $existingAddOn['version_id'] + 1;
		}

		// create our install object
		$install = self::getInstance();

		for ($i = $startVersionId; $i <= $endVersionId; $i++)
		{
			$method = '_installVersion' . $i;
			if (method_exists($install, $method) === false)
			{
				continue;
			}

			$install->$method();
		}

	}

	/**
	 * Install routine for version ID 1 
	 *
	 * @return void
	 */
	protected function _installVersion1()
	{
		$db = $this->_getDb();

		$db->query( "
			CREATE TABLE `dark_taigachat` (
				`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`user_id` INT(10) UNSIGNED NOT NULL,
				`username` VARCHAR(50) NOT NULL,
				`date` INT(10) UNSIGNED NOT NULL,
				`message` TEXT NOT NULL,
				PRIMARY KEY (`id`),
				INDEX `date` (`date`),
				INDEX `user_id` (`user_id`)
			)
			COLLATE='utf8_general_ci'
			ENGINE=InnoDB
		");
	}

}
