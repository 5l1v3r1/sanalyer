<?php

namespace XFMG\Job;

use XF\Job\AbstractJob;
use XFMG\Entity\MediaItem;

class MediaThumb extends AbstractJob
{
	/**
	 * @var \XF\Image\Manager
	 */
	protected $imageManager;

	protected $defaultData = [
		'start' => 0,
		'batch' => 100
	];

	public function run($maxRunTime)
	{
		$startTime = microtime(true);

		$db = $this->app->db();
		$em = $this->app->em();

		$ids = $db->fetchAllColumn($db->limit(
			"
				SELECT media_id
				FROM xf_mg_media_item
				WHERE media_id > ?
				ORDER BY media_id
			", $this->data['batch']
		), $this->data['start']);
		if (!$ids)
		{
			return $this->complete();
		}

		$mediaRepo = $this->app->repository('XFMG:Media');

		$done = 0;

		foreach ($ids AS $id)
		{
			$this->data['start'] = $id;

			/** @var MediaItem $mediaItem */
			$mediaItem = $em->getFinder('XFMG:MediaItem')
				->where('media_id', $id)
				->with('Attachment', true)
				->with('Attachment.Data', true)
				->fetchOne();

			if ($mediaItem)
			{
				$success = false;

				switch ($mediaItem->media_type)
				{
					case 'image':
						$success = $mediaRepo->rebuildImageThumb($mediaItem);
						break;

					case 'audio':
					case 'video':
						$success = $mediaRepo->rebuildFFmpegThumb($mediaItem);
						break;

					case 'embed':
						$success = $mediaRepo->rebuildEmbedThumb($mediaItem);
						break;
				}

				if ($success)
				{
					$mediaItem->thumbnail_date = time();
				}
				else
				{
					$mediaItem->thumbnail_date = 0;
				}

				if ($mediaItem->custom_thumbnail_date)
				{
					if ($mediaRepo->rebuildCustomThumbnail($mediaItem))
					{
						$mediaItem->custom_thumbnail_date = time();
					}
					else
					{
						$mediaItem->custom_thumbnail_date = 0;
					}
				}

				$mediaItem->saveIfChanged();
			}

			$done++;

			if (microtime(true) - $startTime >= $maxRunTime)
			{
				break;
			}
		}

		$this->data['batch'] = $this->calculateOptimalBatch($this->data['batch'], $done, $startTime, $maxRunTime, 1000);

		return $this->resume();
	}

	public function getStatusMessage()
	{
		$actionPhrase = \XF::phrase('rebuilding');
		$typePhrase = \XF::phrase('xfmg_media_thumbnails');
		return sprintf('%s... %s (%s)', $actionPhrase, $typePhrase, $this->data['start']);
	}

	public function canCancel()
	{
		return true;
	}

	public function canTriggerByChoice()
	{
		return true;
	}
}