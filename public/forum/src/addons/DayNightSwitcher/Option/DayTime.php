<?php

namespace DayNightSwitcher\Option;

use XF\Option\AbstractOption;

class DayTime extends AbstractOption
{
    public static function verifyOption(&$dayTime)
    {
        $dayStartCorrect =  preg_match('/([01]?[0-9]|2[0-3]):[0-5][0-9]/', $dayTime['start']);
        $dayEndCorrect =    preg_match('/([01]?[0-9]|2[0-3]):[0-5][0-9]/', $dayTime['end']);

        if(!$dayStartCorrect || !$dayEndCorrect)
        {
            return false;
        }

        return true;
    }
}