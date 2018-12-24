<?php

namespace XFMG\Attachment;

use XF\Attachment\AbstractHandler;
use XF\Entity\Attachment;
use XF\Mvc\Entity\Entity;

class Media extends AbstractHandler
{
	public function canView(Attachment $attachment, Entity $container, &$error = null)
	{
		/** @var \XFMG\Entity\MediaItem $container */
		return $container->canView();
	}

	public function canManageAttachments(array $context, &$error = null)
	{
		$em = \XF::em();

		/** @var \XFMG\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		if (!$visitor->canAddMedia())
		{
			return false;
		}

		if (!empty($context['media_id']))
		{
			/** @var \XFMG\Entity\MediaItem $mediaItem */
			$mediaItem = $em->find('XFMG:MediaItem', intval($context['media_id']));
			if (!$mediaItem || !$mediaItem->canView() || !$mediaItem->canEdit())
			{
				return false;
			}
		}
		else if (!empty($context['category_id']))
		{
			/** @var \XFMG\Entity\Category $category */
			$category = $em->find('XFMG:Category', intval($context['category_id']));
			if (!$category || !$category->canView() || !$category->canAddMedia())
			{
				return false;
			}
		}
		else if (!empty($context['album_id']))
		{
			/** @var \XFMG\Entity\Album $album */
			$album = $em->find('XFMG:Album', intval($context['album_id']));
			if (!$album || !$album->canView() || !$album->canAddMedia())
			{
				return false;
			}
		}
		else
		{
			// Uploading but not created an album yet.
			return $visitor->canCreateAlbum();
		}

		return true;
	}

	public function validateAttachmentUpload(\XF\Http\Upload $upload, \XF\Attachment\Manipulator $manipulator)
	{
		$extension = $upload->getExtension();

		/** @var \XFMG\Repository\Media $repo */
		$repo = \XF::repository('XFMG:Media');

		$mediaType = $repo->getMediaTypeFromExtension($extension);

		if ($mediaType == 'video')
		{
			$videoInfo = new \XFMG\VideoInfo\Preparer($upload->getTempFile());
			$result = $videoInfo->getInfo();

			$requiresTranscoding = (!$result->isValid() || $result->requiresTranscoding());
		}
		else if ($mediaType == 'audio')
		{
			/** @var \XFMG\Service\Media\MP3Detector $MP3Detector */
			$MP3Detector = \XF::service('XFMG:Media\MP3Detector', $upload->getTempFile());

			$requiresTranscoding = ($MP3Detector->isValidMP3() ? false : true);
		}
		else
		{
			$requiresTranscoding = false;
		}

		if ($requiresTranscoding)
		{
			$ffmpegOptions = \XF::options()->xfmgFfmpeg;
			if (!$ffmpegOptions['ffmpegPath'] || !$ffmpegOptions['transcode'])
			{
				$upload->logError('xfmgCannotTranscode', \XF::phrase('xfmg_this_file_is_not_encoded_in_supported_format_and_cannot_be_transcoded'));
			}
		}

		if (in_array($mediaType, ['audio', 'image', 'video']))
		{
			$visitor = \XF::visitor();
			$constraints = $manipulator->getConstraints();

			$runningTotal = $upload->getFileSize();
			$newAttachments = $manipulator->getNewAttachments();
			if (count($newAttachments))
			{
				foreach ($newAttachments AS $attachment)
				{
					/** @var \XF\Entity\Attachment $attachment */
					$runningTotal += intval($attachment->getFileSize());
				}
			}

			$totalSize = $constraints['total'];
			$userQuota = $visitor->xfmg_media_quota;

			if ($totalSize !== PHP_INT_MAX && ($userQuota + $runningTotal > $totalSize))
			{
				$fileSize = \XF::language()->fileSizeFormat($upload->getFileSize());
				$remaining = \XF::language()->fileSizeFormat($totalSize - $runningTotal);
				$upload->logError('xfmgStorageExceeded', \XF::phrase('xfmg_file_you_have_uploaded_is_x_which_exceeds_your_storage_quota', ['size' => $fileSize, 'remaining' => $remaining]));
			}
		}
	}

	public function onNewAttachment(Attachment $attachment, \XF\FileWrapper $file)
	{
		/** @var \XFMG\Service\Media\TempCreator $tempCreator */
		$tempCreator = \XF::app()->service('XFMG:Media\TempCreator');
		$tempCreator->setAttachment($attachment);
		if ($file->getExif())
		{
			$tempCreator->setExif($file->getExif());
		}
		$tempCreator->save();
	}

	public function prepareAttachmentJson(Attachment $attachment, array $context, array $json)
	{
		$em = \XF::em();

		/** @var \XFMG\Entity\MediaTemp $tempMedia */
		$tempMedia = $em->findOne('XFMG:MediaTemp', ['attachment_id' => $attachment->attachment_id]);

		if (!$tempMedia)
		{
			return $json;
		}

		$json['attachment'] += [
			'temp_media_id' => $tempMedia->temp_media_id,
			'media_hash' => $tempMedia->media_hash,
			'media_type' => $tempMedia->media_type,
			'temp_thumbnail_url' => $tempMedia->temp_thumbnail_url,
			'thumbnail_date' => $tempMedia->thumbnail_date,
			'title' => $tempMedia->title,
			'description' => $tempMedia->description,
			'requires_transcoding' => $tempMedia->requires_transcoding
		];
		return $json;
	}

	public function onAssociation(Attachment $attachment, Entity $container = null)
	{
		/** @var \XFMG\Entity\MediaTemp $tempMedia */
		$tempMedia = \XF::em()->findOne('XFMG:MediaTemp', ['attachment_id' => $attachment->attachment_id]);

		/** @var \XFMG\Entity\MediaItem $container */
		if (!$container)
		{
			$tempMedia->delete();
			return;
		}

		if ($tempMedia->thumbnail_date)
		{
			$tempThumbPath = $tempMedia->getAbstractedTempThumbnailPath();
			$mediaThumbPath = $container->getAbstractedThumbnailPath();

			$tempFile = \XF\Util\File::copyAbstractedPathToTempFile($tempThumbPath);
			\XF\Util\File::copyFileToAbstractedPath($tempFile, $mediaThumbPath);

			$container->fastUpdate('thumbnail_date', $tempMedia->thumbnail_date);

			\XF\Util\File::deleteFromAbstractedPath($tempThumbPath);
		}

		$updatePath = false;

		if ($container->media_type == 'video')
		{
			$updatePath = 'data://xfmg/video/%FLOOR%/%DATA_ID%-%HASH%.mp4';
		}
		else if ($container->media_type == 'audio')
		{
			$updatePath = 'data://xfmg/audio/%FLOOR%/%DATA_ID%-%HASH%.mp3';
		}

		if ($updatePath)
		{
			$oldPath = $container->getAbstractedDataPath();
			$attachment->Data->fastUpdate('file_path', $updatePath);
			$newPath = $container->getAbstractedDataPath();

			\XF::fs()->move($oldPath, $newPath);
		}

		$tempMedia->delete();

		$options = \XF::options();
		if ($container->canAddWatermark(false) && !$container->canAddMediaWithoutWatermark())
		{
			$mediaRepo = \XF::repository('XFMG:Media');
			$watermarkPath = $mediaRepo->getAbstractedWatermarkPath($options->xfmgWatermarking['watermark_hash']);
			if (\XF::app()->fs()->has($watermarkPath))
			{
				$tempWatermark = \XF\Util\File::copyAbstractedPathToTempFile($watermarkPath);

				/** @var \XFMG\Service\Media\Watermarker $watermarker */
				$watermarker = \XF::service('XFMG:Media\Watermarker', $container, $tempWatermark);
				$watermarker->watermark();
			}
		}
	}

	public function onAttachmentDelete(Attachment $attachment, Entity $container = null) {}

	public function getConstraints(array $context)
	{
		$em = \XF::em();

		if (!empty($context['media_id']))
		{
			/** @var \XFMG\Entity\MediaItem $mediaItem */
			$mediaItem = $em->find('XFMG:MediaItem', intval($context['media_id']));
			return $mediaItem->getAttachmentConstraints();
		}
		else if (!empty($context['category_id']))
		{
			/** @var \XFMG\Entity\Category $category */
			$category = $em->find('XFMG:Category', intval($context['category_id']));
			return $category->getAttachmentConstraints();
		}
		else if (!empty($context['album_id']))
		{
			/** @var \XFMG\Entity\Album $album */
			$album = $em->find('XFMG:Album', intval($context['album_id']));
			return $album->getAttachmentConstraints();
		}
		else
		{
			// no context will mean we're in the proces of creating an album

			/** @var \XFMG\Entity\Album $album */
			$album = $em->create('XFMG:Album');
			return $album->getAttachmentConstraints();
		}
	}

	public function getContainerIdFromContext(array $context)
	{
		return isset($context['media_id']) ? intval($context['media_id']) : null;
	}

	public function getContainerLink(Entity $container, array $extraParams = [])
	{
		return \XF::app()->router('public')->buildLink('media', $container, $extraParams);
	}

	public function getContext(Entity $entity = null, array $extraContext = [])
	{
		if ($entity instanceof \XFMG\Entity\MediaItem)
		{
			$extraContext['media_id'] = $entity->media_id;
		}
		else if ($entity instanceof \XFMG\Entity\Album)
		{
			$extraContext['album_id'] = $entity->album_id;
		}
		else if ($entity instanceof \XFMG\Entity\Category)
		{
			$extraContext['category_id'] = $entity->category_id;
		}
		else if (!$entity)
		{
			// need nothing
		}
		else
		{
			throw new \InvalidArgumentException("Entity must be media, album or category");
		}

		return $extraContext;
	}
}