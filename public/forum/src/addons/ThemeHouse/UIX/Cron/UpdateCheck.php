<?php

namespace ThemeHouse\UIX\Cron;

class UpdateCheck
{
    public static function run()
    {
        if (!\XF::options()->th_updateCheck_uix) {
            return;
        }

        $registryKey = 'uix_updateStatus';
        $updateStatus = \XF::registry()[$registryKey];

        if (empty($updateStatus)) {
            $updateStatus = [];
        }
        $unresolvedKeys = array_keys($updateStatus);

        // First we'll check to see if an add-on update is avaialable
        $update = \XF::service('ThemeHouse\UIX:UpdateCheck', 217, \XF::repository('XF:AddOn')->inferVersionStringFromId(\XF::app()->registry()['addOns']['ThemeHouse/UIX']));
        $check = $update->check();
        $key = array_search('addon', $unresolvedKeys);
        if ($key !== false) {
            unset($unresolvedKeys[$key]);
        }

        if ($check) {
            $updateStatus['addon'] = $check;
        }

        // Now let's check the styles
        $styles = \XF::finder('XF:Style')->where('th_product_id_uix', '!=', 0)->fetch();
        foreach ($styles as $style) {
            $update = \XF::service('ThemeHouse\UIX:UpdateCheck', $style->th_product_id_uix, $style->th_product_version_uix);
            $check = $update->checK();
            if ($check) {
                $updateStatus[$style->th_product_id_uix] = $check;
            }

            $key = array_search($style->th_product_id_uix, $unresolvedKeys);
            if ($key !== false) {
                unset($unresolvedKeys[$key]);
            }
        }

        foreach ($unresolvedKeys as $key) {
            unset($updateStatus[$key]);
        }

        \XF::registry()->set($registryKey, $updateStatus);
    }
}