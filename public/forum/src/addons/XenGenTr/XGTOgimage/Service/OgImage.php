<?php

namespace XenGenTr\XGTOgimage\Service;

use \XF\Mvc\Entity\Entity;
use \XF\Entity\Node;
use \XF\Entity\Thread;

class OgImage extends \XF\Service\AbstractService
{
    /** @var \XF\Entity\Node */
    protected $node;

    /** @var \XF\Entity\Thread */
    protected $thread;

    /** @var \XF\Http\Upload */
    protected $upload;

    protected $fileName;

    protected $type;

    protected $mode;

    protected $error = null;

    protected $allowedTypes = [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG];

    public function __construct(\XF\App $app, Node $node, Thread $thread = null, $mode = 'node')
    {
        parent::__construct($app);
        $this->setNode($node);

        if($thread !== null)
            $this->setThread($thread);

        $this->mode = $mode;
    }

    protected function setNode(Node $node)
    {
        $this->node = $node;
    }

    protected function setThread(Thread $thread)
    {
        if ($thread instanceof \XF\Entity\Thread)
        {
            $this->thread = $thread;
        }
    }

    public function getError()
    {
        return $this->error;
    }

    public function setImage($fileName)
    {
        if (!$this->validateImage($fileName, $error))
        {
            $this->error = $error;
            $this->fileName = null;
            return false;
        }

        $this->fileName = $fileName;
        return true;
    }

    public function validateImage($fileName, &$error = null)
    {
        $error = null;

        if (!file_exists($fileName))
        {
            throw new \InvalidArgumentException("Gecersiz dosya hizmeti");
        }
        if (!is_readable($fileName))
        {
            throw new \InvalidArgumentException("Bu hizmet okunmuyor");
        }

        $imageInfo = filesize($fileName) ? @getimagesize($fileName) : false;
        if (!$imageInfo)
        {
            $error = \XF::phrase('provided_file_is_not_valid_image');
            return false;
        }

        $type = $imageInfo[2];
        if (!in_array($type, $this->allowedTypes))
        {
            $error = \XF::phrase('provided_file_is_not_valid_image');
            return false;
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];

        if (!$this->app->imageManager()->canResize($width, $height))
        {
            $error = \XF::phrase('uploaded_image_is_too_big');
            return false;
        }

        $this->type = $type;

        return true;
    }

    public function setImageFromUpload(\XF\Http\Upload $upload)
    {
        $upload->requireImage();

        if (!$upload->isValid($errors))
        {
            $this->error = reset($errors);
            return false;
        }

        $this->upload = $upload;

        return $this->setImage($upload->getTempFile());
    }

    public function guncelleOgImage($OgImageMevcut)
    {
        if($OgImageMevcut)
            return $OgImageMevcut;

        return \XF\Util\Random::getRandomString(10) . '.' . $this->upload->getExtension();
    }

    public function nakletOgImage($xgt_ogdb, Node $node)
    {
        if (!$this->fileName)
        {
            throw new \LogicException;
        }

        if ($this->thread !== null && !$this->thread->exists())
        {
            throw new \LogicException;
        }

        $dataFile = $node->getXgtOgImageYol($xgt_ogdb, $this->mode);

        \XF\Util\File::copyFileToAbstractedPath($this->fileName, $dataFile);
    }

    public function silOgImage($fileName)
    {
        $xgt_ogdb = [];
        $xgt_ogdb['xgtOg_yukle'] = $fileName;

        \XF\Util\File::deleteFromAbstractedPath($this->node->getXgtOgImageYol($xgt_ogdb, $this->mode));

        return true;
    }
}