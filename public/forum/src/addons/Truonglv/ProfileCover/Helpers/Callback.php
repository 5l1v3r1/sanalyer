<?php
/**
 * @license
 * Copyright 2017 TruongLuu. All Rights Reserved.
 */
namespace Truonglv\ProfileCover\Helpers;

use Truonglv\ProfileCover\Service\Cover;
use XF\Entity\User;
use XF\Template\Templater;

class Callback
{
    protected static $services = [];

    public static function getCover(
        /** @noinspection PhpUnusedParameterInspection */
        $value,
        array $params,
        Templater $templater
    ) {
        if (empty($params['user'])) {
            throw new \InvalidArgumentException('Missing user in params');
        }

        $size = !empty($params['size']) ? $params['size'] : 'cropped';

        /** @var \Truonglv\ProfileCover\Service\Cover $cover */
        $cover = self::getCoverService($params['user']);

        $coverUrl = $cover->getCoverUrl($size == 'source' ? Cover::SIZE_FULL : Cover::SIZE_CROPPED);
        $defaultCover = Option::get('defaultCover');

        if ($coverUrl || !empty($defaultCover)) {
            return '<img src="'. ($coverUrl ?: $defaultCover) .'" alt="'. htmlspecialchars($params['user']->username) .'"
                class="profileCover--img" />';
        }

        return '<div class="profileCover--noimg"></div>';
    }

    public static function hasPermission(
        /** @noinspection PhpUnusedParameterInspection */
        $value,
        array $params,
        Templater $templater
    ) {
        /** @var \Truonglv\ProfileCover\Service\Cover $cover */
        $cover = self::getCoverService(\XF::visitor());
        return $cover->canUploadCover();
    }

    protected static function getCoverService(User $user)
    {
        if (!isset(self::$services[$user->user_id])) {
            self::$services[$user->user_id] = \XF::service('Truonglv\ProfileCover:Cover', $user);
        }

        return self::$services[$user->user_id];
    }
}