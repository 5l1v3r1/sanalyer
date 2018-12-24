<?php

namespace XenGenTr\XGTOgimage;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Alter;
use XF\Db\Schema\Create;

class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;
	
   /**
    * ----------------
    *   Eklenti kurulumuna basla 1.0.0 Beta4
    * ----------------
    */

	public function installStep1()
	{
        $sm = $this->schemaManager();

        $sm->alterTable('xf_thread', function(Alter $table)
        {
            $table->addColumn('xgt_ogdb', 'mediumblob')->nullable();
        });
    }

	public function installStep2()
	{
		$target = \XF::getRootDirectory().'/data/XenGenTr';
		if (!is_dir($target)) { mkdir($target, 0775); }
	}
	
	public function installStep3()
	{
		$target = \XF::getRootDirectory().'/data/XenGenTr/xgt_og_images';
		if (!is_dir($target)) { mkdir($target, 0775); }
	}

    /**
    * ----------------
    *     Eklentiyi kaldir
    * ----------------
    */
    public function uninstallStep1()
    {
        $sm = $this->schemaManager();

        $sm->alterTable('xf_thread', function(Alter $table)
        {
            $table->dropColumns('xgt_ogdb');
        });
     }
   
	public function uninstallStep2()
	{	
		$target = glob(\XF::getRootDirectory().'/data/XenGenTr/xgt_og_images/*');
		foreach ($target AS $file) { unlink($file); }
		
		$target = \XF::getRootDirectory().'/data/XenGenTr/xgt_og_images';
		if (is_dir($target)) { rmdir($target); }      
	}
}
