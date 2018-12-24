<?php

namespace XenGenTr\XGTForumistatistik\EventListener;

class MakroOlustur
{
    public static function preRender(\XF\Template\Templater $templater, &$type, &$template, &$name, array &$arguments, array &$globalVars)
    {
        if ($arguments['group']->group_id == 'XGT_istatistik_secenekleri')
        {
            $template = 'XGT_istatistik_acp_tab_menu';
        }
    }
}