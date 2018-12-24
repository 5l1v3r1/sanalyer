<?php

namespace XenGenTr\ResimliOneCikanKonular;

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
     *   Ekelenti kurulumuna baþla
     * ----------------
     */

	public function installStep1()
	{
		$this->schemaManager()->createTable(
                 'xengentr_resimlionecikanlar', function(Create $table)
		{
			         $table->checkExists(true);
		             $table->addColumn('thread_id', 			 'int', 10);
			         $table->addColumn('resimlionecikan_tarih',	 'int', 10);
			         $table->addColumn('resimlionecikan_saat',	 'int', 10);
			         $table->addColumn('resimlionecikan_baslik', 'varchar', 255);
			         $table->addColumn('resimlionecikan_icerik', 'text');
			         $table->addPrimaryKey('thread_id');
		});
	}
	
	public function installStep2()
	{
		$target = \XF::getRootDirectory().'/data/XenGenTr';
		if (!is_dir($target)) { mkdir($target, 0777); }
	}
	
	public function installStep3()
	{
		$target = \XF::getRootDirectory().'/data/XenGenTr/xengentr_resimlikonular';
		if (!is_dir($target)) { mkdir($target, 0777); }
	}
	
    public function installStep4()
    {
        $this->createWidget(
              'XenGenTr_ResimliOneCikan_Widget', 
              'ResimliOneCikanWidget', [
                  	'positions' => []
        ]);
    }

    /**
     * ----------------
     *     Eklentiyi kaldýr
     * ----------------
     */

	public function uninstallStep1()
	{
		$this->schemaManager()->dropTable(
                   'xengentr_resimlionecikanlar'
        );
	}
	
	public function uninstallStep2()
	{
	
		$target = glob(\XF::getRootDirectory().'/data/XenGenTr/xengentr_resimlikonular/*.jpg');
		foreach ($target AS $file) { unlink($file); }

		$target = \XF::getRootDirectory().'/data/XenGenTr/xengentr_resimlikonular';
		if (is_dir($target)) { rmdir($target); }

	    $target = \XF::getRootDirectory().'/data/XenGenTr';
	    if (is_dir($target)) { rmdir($target); }
	}


}