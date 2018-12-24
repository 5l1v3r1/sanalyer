<?php

namespace XenGenTr\XGTOgimage;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Manager;
use XF\Mvc\Entity\Structure;

class Listener
{
    public static function entity_structur(\XF\Mvc\Entity\Manager $em, \XF\Mvc\Entity\Structure &$structure)
    {
        switch($structure->table)
        {
            case 'xf_thread':
                $structure->columns['xgt_ogdb'] = ['type' => Entity::SERIALIZED_ARRAY, 'default' => []];
                break;
        }
    }

    public static function CR(\XF\Template\Templater $templater, &$output)
    {
        do
        {
            $exists = isset($templater->XGT_footer);
          
            if (!$exists)
            {
                $templater->AF_footer = [];
            }

            $r = \XF::generateRandomString(2);

            $templater->XGT_footer["XenGenTr/XGTOgimage"] = "";

            asort($templater->XGT_footer);

            $escape = false;
            $linkColor = $templater->fnProperty($templater, $escape, 'publicFooterLink--color');

            if (!$linkColor) $linkColor = 'inherit';

            $str = '';

            if ($exists)
            {
                $re = '/<div data-xgt-cp.+?<\/div>/i';
                $output = preg_replace($re, $str, $output, 1);
            }
            else
            {
                $output .= $str;
            }
        }
        while (false);
    }

}