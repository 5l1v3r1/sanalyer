<?php

namespace DayNightSwitcher;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Manager;
use XF\Mvc\Entity\Structure;

class EntityStructureExtender
{
    public static function extendUserOptionStructure(Manager $em, Structure &$structure)
    {
        $structure->columns['daynightswitcher_enabled'] = ['type' => Entity::BOOL, 'default' => true];
    }
}