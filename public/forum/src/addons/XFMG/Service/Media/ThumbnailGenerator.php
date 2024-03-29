<?php

namespace XFMG\Service\Media;

use XF\Service\AbstractService;

class ThumbnailGenerator extends AbstractService
{
	public function createTempThumbnailFromAttachment(\XF\Entity\Attachment $attachment, $abstractedDestination, $mediaType)
	{
		$data = $attachment->Data;

		$dataPath = $data->getAbstractedDataPath();

		if ($mediaType == 'image')
		{
			$sourceFile = \XF\Util\File::copyAbstractedPathToTempFile($dataPath);

			$width = $data->width;
			$height = $data->height;

			return $this->getTempThumbnailFromImage($sourceFile, $abstractedDestination, $width, $height);
		}
		else if ($mediaType == 'video' || $mediaType == 'audio')
		{
			$ffmpegOptions = \XF::options()->xfmgFfmpeg;
			if (!$ffmpegOptions['ffmpegPath'] || !$ffmpegOptions['thumbnail'])
			{
				return false;
			}

			$sourceFile = \XF\Util\File::copyAbstractedPathToTempFile($dataPath);

			return $this->getTempThumbnailFromFfmpeg($sourceFile, $abstractedDestination, $mediaType);
		}
		else
		{
			return false;
		}
	}

	public function getTempThumbnailFromImage($sourceFile, $abstractedDestination, $width = null, $height = null)
	{
		$tempThumbFile = null;

		if ($width === null || $height === null)
		{
			$imageInfo = getimagesize($sourceFile);
			if (!$imageInfo)
			{
				return false;
			}

			$imageType = $imageInfo[2];
			switch ($imageType)
			{
				case IMAGETYPE_GIF:
				case IMAGETYPE_JPEG:
				case IMAGETYPE_PNG:
					break;

				default:
					return false;
			}

			$width = $imageInfo[0];
			$height = $imageInfo[1];
		}

		if ($width && $height && $this->app->imageManager()->canResize($width, $height))
		{
			$tempThumbFile = $this->generateThumbnailFromFile($sourceFile);
		}

		if (!$tempThumbFile)
		{
			return false;
		}

		try
		{
			\XF\Util\File::copyFileToAbstractedPath($tempThumbFile, $abstractedDestination);
		}
		catch (\Exception $e)
		{
			\XF\Util\File::deleteFromAbstractedPath($abstractedDestination);

			throw $e;
		}

		return true;
	}

	public function getTempThumbnailFromFfmpeg($sourceFile, $abstractedDestination, $mediaType)
	{
		$tempThumbFile = null;

		$ffmpegOptions = $this->app->options()->xfmgFfmpeg;

		$class = '\XFMG\Ffmpeg\Runner';
		$class = \XF::extendClass($class);

		/** @var \XFMG\Ffmpeg\Runner $ffmpeg */
		$ffmpeg = new $class($ffmpegOptions['ffmpegPath']);
		$ffmpeg->setFileName($sourceFile);
		$ffmpeg->setType($mediaType);

		$frame = $ffmpeg->getKeyFrame();
		if (!$frame)
		{
			return false;
		}

		$imageInfo = @getimagesize($frame);
		if (!$imageInfo)
		{
			return false;
		}

		$width = $imageInfo[0];
		$height = $imageInfo[1];

		if ($width && $height && $this->app->imageManager()->canResize($width, $height))
		{
			$tempThumbFile = $this->generateThumbnailFromFile($frame);
		}

		if (!$tempThumbFile)
		{
			return false;
		}

		try
		{
			\XF\Util\File::copyFileToAbstractedPath($tempThumbFile, $abstractedDestination);
		}
		catch (\Exception $e)
		{
			\XF\Util\File::deleteFromAbstractedPath($abstractedDestination);

			throw $e;
		}

		return true;
	}

	public function generateThumbnailFromFile($sourceFile, &$width = null, &$height = null)
	{
		$image = $this->app->imageManager()->imageFromFile($sourceFile);
		if (!$image)
		{
			return null;
		}

		if ($image instanceof \XF\Image\Imagick)
		{
			// Workaround to only use the first frame of a multi-frame image for the thumb
			foreach ($image->getImage() AS $imagick)
			{
				$image->setImage($imagick->getImage());
				break;
			}
		}

		$thumbDimensions = $this->app->options()->xfmgThumbnailDimensions;
		$thumbWidth = $thumbDimensions['width'];
		$thumbHeight = $thumbDimensions['height'];

		$image->resizeAndCrop($thumbWidth, $thumbHeight)
			->unsharpMask();

		$newTempFile = \XF\Util\File::getTempFile();
		if ($newTempFile && $image->save($newTempFile))
		{
			$width = $image->getWidth();
			$height = $image->getHeight();

			return $newTempFile;
		}
		else
		{
			return null;
		}
	}
}