<?php
/**
 * @license
 * Copyright 2017 TruongLuu. All Rights Reserved.
 */
namespace Truonglv\ProfileCover\Service;

use Truonglv\ProfileCover\Helpers\Option;
use XF\Util\File;
use XF\Entity\User;
use XF\Service\AbstractService;

class Cover extends AbstractService
{
    const USER_FIELD_ID  = 'tl_profile_cover';
    const SIZE_FULL      = 'f';
    const SIZE_CROPPED   = 'c';

    const PERMISSION_GROUP = 'general';
    const PERMISSION_PREFIX = 'profileCover_';

    /**
     * @var \XF\Entity\User
     */
    protected $user;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @var array
     */
    protected $allowedTypes = [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG];

    protected $width;
    protected $height;
    protected $imageType;

    public function __construct(\XF\App $app, User $user)
    {
        parent::__construct($app);
        $this->setUser($user);
    }

    public function canDeleteCover(&$error = null)
    {
        $fieldValue = $this->user->Profile->custom_fields->getFieldValue(self::USER_FIELD_ID);
        if (empty($fieldValue)) {
            return false;
        }

        if (\XF::visitor()->hasPermission(self::PERMISSION_GROUP, self::PERMISSION_PREFIX . 'deleteAny')) {
            return true;
        }

        return (\XF::visitor()->user_id == $this->user->user_id
            && $this->user->hasPermission(self::PERMISSION_GROUP, self::PERMISSION_PREFIX . 'deleteSelf')
        );
    }

    public function canUploadCover(&$error = null)
    {
        if (\XF::visitor()->hasPermission(self::PERMISSION_GROUP, self::PERMISSION_PREFIX . 'uploadAny')) {
            return true;
        }

        return (\XF::visitor()->user_id == $this->user->user_id
            && $this->user->hasPermission(self::PERMISSION_GROUP, self::PERMISSION_PREFIX . 'uploadSelf')
        );
    }

    public function canRepositionCover(&$error = null)
    {
        if (!$this->canUploadCover($error)) {
            return false;
        }

        $fieldValue = $this->user->Profile->custom_fields->getFieldValue(self::USER_FIELD_ID);
        return (!empty($fieldValue) && \XF::visitor()->user_id == $this->user->user_id);
    }

    public function setImage($fileName)
    {
        if (!file_exists($fileName)) {
            throw new \InvalidArgumentException('File not exists');
        }

        if (!is_readable($fileName)) {
            throw new \InvalidArgumentException('File (' . $fileName . ') is not readable.');
        }

        $this->fileName = $fileName;
    }

    public function upload(&$error = null)
    {
        if ($this->fileName === null) {
            throw new \InvalidArgumentException('Must be setImage');
        }

        if (!$this->validate($error)) {
            return false;
        }

        /** @var \XF\Entity\UserProfile $userProfile */
        $userProfile = $this->user->Profile;
        /** @var \XF\CustomField\Set $fieldSet */
        $fieldSet = $userProfile->custom_fields;

        $image = $this->app->imageManager()->imageFromFile($this->fileName);
        if (!$image) {
            throw new \InvalidArgumentException('Cannot create image process from (' . $this->fileName . ')');
        }

        $newTempFile = File::getTempFile();
        if ($newTempFile && $image->save($newTempFile)) {
            unset($image);
        } else {
            throw new \RuntimeException("Failed to save image to temporary file; check internal_data/data permissions");
        }

        foreach ($this->getOutputSizeHandlers() as $size => $callback) {
            if (!is_callable($callback)) {
                throw new \InvalidArgumentException('Callback for size (' . $size . ') is not callable.');
            }

            $image = $this->app->imageManager()->imageFromFile($newTempFile);
            call_user_func_array($callback, array($image));

            unset($image);
        }

        $fieldSet->set(self::USER_FIELD_ID, time(), 'admin');
        $userProfile->save();

        return true;
    }

    public function saveReposition()
    {
        if ($this->fileName === null) {
            throw new \InvalidArgumentException('Must be setImage');
        }

        $image = $this->app->imageManager()->imageFromFile($this->fileName);
        if (!$image) {
            throw new \InvalidArgumentException('Cannot create image process from (' . $this->fileName . ')');
        }

        $tempFile = File::getTempFile();
        if ($tempFile && $image->save($tempFile)) {
            unset($image);
        } else {
            throw new \RuntimeException("Failed to save image to temporary file; check internal_data/data permissions");
        }

        File::copyFileToAbstractedPath($tempFile, $this->getCoverPath(self::SIZE_CROPPED));

        $userProfile = $this->user->Profile;

        $userProfile->custom_fields->set(self::USER_FIELD_ID, time(), 'admin');
        $userProfile->save();

        return true;
    }

    public function delete()
    {
        foreach ([self::SIZE_FULL, self::SIZE_CROPPED] as $size) {
            $path = $this->getCoverPath($size);
            File::deleteFromAbstractedPath($path);
        }

        $userProfile = $this->user->Profile;
        $userProfile->custom_fields->removeFieldValue(self::USER_FIELD_ID);
        $userProfile->save();
    }

    public function getError()
    {
        return $this->error;
    }

    public function getMinWidth()
    {
        return Option::get('minWidth');
    }

    public function getMinHeight()
    {
        return Option::get('minHeight');
    }

    public function getCoverPath($size)
    {
        $userId = $this->user->user_id;

        return sprintf('data://covers/%s/%d/%d.jpg',
            $size,
            floor($userId / 1000),
            $userId
        );
    }

    public function getCoverUrl($size)
    {
        $date = $this->user->Profile->custom_fields->getFieldValue(self::USER_FIELD_ID);
        if (empty($date)) {
            return false;
        }

        return $this->app->applyExternalDataUrl(sprintf('covers/%s/%d/%d.jpg?_ts=%d',
            $size,
            floor($this->user->user_id / 1000),
            $this->user->user_id,
            $date
        ));
    }

    protected function saveCoverSizeFull(\XF\Image\AbstractDriver $image)
    {
        $tempFile = File::getTempFile();
        if ($tempFile && $image->save($tempFile)) {
            // good
        } else {
            throw new \RuntimeException("Failed to save image to temporary file; check internal_data/data permissions");
        }

        File::copyFileToAbstractedPath($tempFile, $this->getCoverPath(self::SIZE_FULL));
    }

    protected function saveCoverSizeCropped(\XF\Image\AbstractDriver $image)
    {
        $minWidth = $this->getMinWidth();
        $minHeight = $this->getMinHeight();

        $width = $image->getWidth();
        $height = $image->getHeight();

        $ratio = min($width/$minWidth, $height / $minHeight);

        $newWidth = floor($minWidth * $ratio);
        $newHeight = floor($minHeight * $ratio);

        $image->resizeTo($newWidth, $newHeight);
        $image->crop(
            $minWidth,
            $minHeight,
            max(0, ($image->getWidth() - $minWidth) / 2),
            max(0, ($image->getHeight() - $minHeight) / 2)
        );

        $tempFile = File::getTempFile();
        if ($tempFile && $image->save($tempFile)) {
            // good
        } else {
            throw new \RuntimeException("Failed to save image to temporary file; check internal_data/data permissions");
        }

        File::copyFileToAbstractedPath($tempFile, $this->getCoverPath(self::SIZE_CROPPED));
    }

    protected function getOutputSizeHandlers()
    {
        return [
            self::SIZE_FULL => [$this, 'saveCoverSizeFull'],
            self::SIZE_CROPPED => [$this, 'saveCoverSizeCropped']
        ];
    }

    protected function validate(&$error = null)
    {
        $fileSize = filesize($this->fileName);
        $imageInfo = $fileSize ? getimagesize($this->fileName) : false;
        if (!$imageInfo) {
            $error = \XF::phrase('provided_file_is_not_valid_image');
            return false;
        }

        $type = $imageInfo[2];
        if (!in_array($type, $this->allowedTypes)) {
            $error = \XF::phrase('provided_file_is_not_valid_image');
            return false;
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];

        if (!$this->app->imageManager()->canResize($width, $height)) {
            $error = \XF::phrase('uploaded_image_is_too_big');
            return false;
        }

        $maxSize = Option::get('maxSize') * 1024;
        if ($fileSize > $maxSize) {
            $error = \XF::phrase('tl_profile_cover.please_use_image_that_no_more_than_x', [
                'size' => \XF::app()->templater()->fn('number', [$maxSize / 1024])
            ]);

            return false;
        }

        // require 2:1 aspect ratio or squarer
        if ($width < $this->getMinWidth() || $height < $this->getMinHeight()) {
            $error = \XF::phrase('tl_profile_cover.please_use_image_that_at_least_x_pixels', [
                'width' => $this->getMinWidth(),
                'height' => $this->getMinHeight()
            ]);

            return false;
        }

        $this->width = $width;
        $this->height = $height;
        $this->imageType = $type;

        return true;
    }

    protected function setUser(User $user)
    {
        if (!$user->exists()) {
            throw new \LogicException('User must be exists.');
        }

        $this->user = $user;
    }
}