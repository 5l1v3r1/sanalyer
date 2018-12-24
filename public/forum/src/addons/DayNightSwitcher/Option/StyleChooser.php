<?php

namespace DayNightSwitcher\Option;

use XF\Entity\Option;
use XF\Option\AbstractOption;
use XF\Repository\Style;

class StyleChooser extends AbstractOption
{
    public static function renderOption(Option $option, array $htmlParams)
    {
        /** @var Style $styleRep */
        $styleRep = \XF::repository('XF:Style');

        $styles = $styleRep->getSelectableStyles();

        return self::getTemplate('admin:daynightswitcher_style_chooser', $option, $htmlParams, [
            'styles' => $styles
        ]);
    }

    public static function verifyOption(&$style_id)
    {
        // Checking that selected style exists

        /** @var Style $styleRep */
        $styleRep = \XF::repository('XF:Style');

        $stylesIds = array_column($styleRep->getSelectableStyles(), 'style_id');
        if(!in_array($style_id, $stylesIds))
        {
            return false;
        }

        return true;
    }
}