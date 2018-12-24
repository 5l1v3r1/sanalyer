<?php
/**
 * @license
 * Copyright 2017 TruongLuu. All Rights Reserved.
 */
namespace Truonglv\ProfileCover\Observer;

use Truonglv\ProfileCover\Service\Cover;
use XF\Mvc\Entity\Entity;

class UserField
{
    public static $allowDeleteField = false;

    public static function preSave(Entity $entity)
    {
        /** @var \XF\Entity\UserField $entity */
        if ($entity->field_id === Cover::USER_FIELD_ID && $entity->isUpdate()) {
            if ($entity->hasChanges()) {
                $entity->error(
                    'This field has been taken by add-on [tl] Profile Cover. Please do not change.',
                    Cover::USER_FIELD_ID
                );
            }
        }
    }

    public static function preDelete(Entity $entity)
    {
        /** @var \XF\Entity\UserField $entity */
        if ($entity->field_id === Cover::USER_FIELD_ID && !self::$allowDeleteField) {
            $entity->error(
                'This field has been taken by add-on [tl] Profile Cover. Delete the add-on also delete this field.',
                Cover::USER_FIELD_ID
            );
        }
    }
}