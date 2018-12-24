<?php

namespace DayNightSwitcher;

use XF\Entity\User;
use XF\Repository\Option;
use \ArrayObject;

class Switcher
{
    public static function shouldSwitchTheme(User $visitor)
    {
        $isAddonEnabledGlobally = \XF::options()->offsetGet('daynightswitcher_globalEnabled');

        if(!$isAddonEnabledGlobally)
        {
            return false;
        }

        if($visitor->user_id)
        {
            $canUserDisableAddon = $visitor->hasPermission('general', 'daynightswitcher_disable');

            if(!$canUserDisableAddon)
            {
                return true;
            }

            $isAddonEnabled = $visitor->Option->daynightswitcher_enabled;

            return $isAddonEnabled;
        }
        else
        {
            $isAddonEnabledForGuests = \XF::options()->offsetGet('daynightswitcher_guestsEnabled');

            return $isAddonEnabledForGuests;
        }
    }

    /**
     * @param User $visitor
     * @param ArrayObject $options
     * @param string $dayOrNight
     */
    private static function _switchTheme(User $visitor, $options, $dayOrNight = 'day')
    {
        if($visitor->user_id)
        {
            // Registered user
            if(self::shouldSwitchTheme($visitor))
            {
                $visitor->style_id = $options->offsetGet('daynightswitcher_' . $dayOrNight . 'Theme');
            }
        }
        else
        {
            // Guest
            if($options->offsetGet('daynightswitcher_guestsEnabled'))
            {
                $options->offsetSet('defaultStyleId', $options->offsetGet('daynightswitcher_' . $dayOrNight . 'Theme'));
            }
        }
    }

    public static function switchTheme(User $visitor)
    {
        /** @var Option $optionRep */
        $optionRep = \XF::repository('XF:Option');

        $globalEnabled = $optionRep->options()->offsetGet('daynightswitcher_globalEnabled');

        if($globalEnabled)
        {
            // Getting needed time variables
            if($visitor->user_id)
            {
                $tz = new \DateTimeZone($visitor->timezone);
            }
            else
            {
                $tz = new \DateTimeZone($optionRep->options()->guestTimeZone);
            }

            $dayStartTime = new \DateTime(
                'today ' . $optionRep->options()->offsetGet('daynightswitcher_dayTime')['start'],
                $tz
            );
            $dayEndTime = new \DateTime(
                'today ' . $optionRep->options()->offsetGet('daynightswitcher_dayTime')['end'],
                $tz
            );

            $currentTime = new \DateTime('now', $tz);

            // Defining what part of day is it
            if(($currentTime >= $dayStartTime) && ($currentTime <= $dayEndTime))
            {
                // Day theme
                self::_switchTheme($visitor, $optionRep->options(), 'day');
            }
            else
            {
                // Night theme
                self::_switchTheme($visitor, $optionRep->options(), 'night');
            }
        }
    }
}