<?php

namespace XenGenTr\XGTForumistatistik;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;

class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;

    /**
     * ----------------
     *     Kuruluma basla
     * ----------------
     */

    /* 2.0.0 Alpha */
    public function installStep1()
    {
        $this->createWidget('XGT_istatistik_yenimesajlar_widget', 'XGT_YeniMesajlar_widget', [
            'positions' => []

        ]);

        $this->createWidget('XGT_istatistik_sonkonular_widget', 'XGT_YeniKonular_widget', [
            'positions' => []

        ]);

         $this->createWidget('XGT_istatistik_encokgrtkonular_widget', 'XGT_EnCokGrtKonu_widget', [
            'positions' => []
        ]);


        $this->createWidget('XGT_istatistik_encokcevap_konular_widget', 'XGT_EnCokCevapKonu_widget', [
            'positions' => []
        ]);


        $this->createWidget('XGT_istatistik_encoklike_konular_widget', 'XGT_EnCokBegenKonu_widget', [
            'positions' => []
        ]);


        $this->createWidget('XGT_istatistik_encokmesaj_kullanici', 'XGT_encokmesaj_kullanici', [
            'positions' => []
        ]);
    }


}