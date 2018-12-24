<?php

namespace ThemeHouse\UIX\Listener;

use XF\Mvc\Entity\Entity;

class EntityStructure
{
    public static function style(\XF\Mvc\Entity\Manager $em, \XF\Mvc\Entity\Structure &$structure)
    {
        $structure->columns['th_product_id_uix'] = [
            'type' => Entity::UINT,
            'default' => 0,
        ];
        $structure->columns['th_product_version_uix'] = [
            'type' => Entity::STR,
            'default' => null,
        ];
        $structure->columns['th_primary_child_uix'] = [
            'type' => Entity::BOOL,
            'default' => 0,
        ];
        $structure->columns['th_child_style_xml_uix'] = [
            'type' => Entity::STR,
            'default' => '',
        ];
        $structure->columns['th_child_style_cache_uix'] = [
            'type' => Entity::JSON_ARRAY,
            'default' => [],
        ];
    }


    public static function userOption(\XF\Mvc\Entity\Manager $em, \XF\Mvc\Entity\Structure &$structure)
    {
//        $structure->columns['th_width_uix'] = [
//            'type' => Entity::STR,
//            'default' => 0
//        ];
//        $structure->columns['th_sidebar_collapsed_uix'] = [
//            'type' => Entity::UINT,
//            'default' => 0
//        ];
    }
}