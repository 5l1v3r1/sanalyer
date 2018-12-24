<?php

namespace XFMG\Job;

use XF\Job\AbstractJob;
use XFMG\Entity\Album;

class AlbumThumb extends AbstractJob
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
				SELECT album_id
				FROM xf_mg_album
				WHERE album_id > ?
				ORDER BY album_id
			", $this->data['batch']
		), $this->data['start']);
		if (!$ids)
		{
			return $this->complete();
		}

		$done = 0;

		foreach ($ids AS $id)
		{
			$this->data['start'] = $id;

			/** @var Album $album */
			$album = $em->getFinder('XFMG:Album')
				->where('album_id', $id)
				->fetchOne();

			if ($album)
			{
				/** @var \XFMG\Service\Album\ThumbnailGenerator $generator */
				$generator = $this->app->service('XFMG:Album\ThumbnailGenerator', $album);
				if ($generator->createAlbumThumbnail())
				{
					$album->fastUpdate('thumbnail_date', time());
				}
				else
				{
					$album->fastUpdate('thumbnail_date', 0);
				}
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
		$typePhrase = \XF::phrase('xfmg_album_thumbnails');
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