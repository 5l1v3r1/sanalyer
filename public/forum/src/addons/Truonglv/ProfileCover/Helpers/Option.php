<?php
/**
 * @license
 * Copyright 2017 TruongLuu. All Rights Reserved.
 */
namespace Truonglv\ProfileCover\Helpers;

class Option
{
    const OPTION_PREFIX = 'tl_ProfileCover_';

    public static function get($key)
    {
        $key = self::OPTION_PREFIX . $key;
        $options = \XF::options();

        if ($options->offsetExists($key)) {
            return $options->offsetGet($key);
        }

        return null;
    }
}