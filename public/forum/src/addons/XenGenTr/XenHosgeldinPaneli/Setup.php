<?php

namespace XenGenTr\XenHosgeldinPaneli;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;

class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;

    public function installStep1()
    {
        $this->createWidget('XenHosgeldin', 'hosgeldinpaneli', [
            'positions' => ['forum_list_above_nodes' => 1]

        ]);
    }

    public function upgrade1010100Step1()
    {

         $this->createWidget('XenHosgeldinSidebar', 'hosgeldinpaneli_sidebar', [
            'positions' => ['forum_list_sidebar' => 1]

        ]);
     }


}