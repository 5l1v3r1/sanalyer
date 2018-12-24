<?php

namespace XFMG\Service\Media;

use XFMG\Entity\MediaItem;

class Editor extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/**
	 * @var MediaItem
	 */
	protected $mediaItem;

	/**
	 * @var \XFMG\Service\Media\Preparer
	 */
	protected $mediaItemPreparer;

	protected $alert = false;
	protected $alertReason = '';

	public function __construct(\XF\App $app, MediaItem $mediaItem)
	{
		parent::__construct($app);
		$this->setMediaItem($mediaItem);
	}

	public function setMediaItem(MediaItem $mediaItem)
	{
		$this->mediaItem = $mediaItem;
		$this->mediaItemPreparer = $this->service('XFMG:Media\Preparer', $this->mediaItem);
	}

	public function getMediaItem()
	{
		return $this->mediaItem;
	}

	public function getMediaItemPreparer()
	{
		return $this->mediaItemPreparer;
	}

	public function setTitle($title, $description = '')
	{
		$this->mediaItem->title = $title;

		if ($description)
		{
			$this->mediaItem->description = $description;
		}
	}

	public function setCustomFields(array $customFields)
	{
		$mediaItem = $this->mediaItem;

		$editMode = $mediaItem->getFieldEditMode();

		/** @var \XF\CustomField\Set $fieldSet */
		$fieldSet = $mediaItem->custom_fields;
		$fieldDefinition = $fieldSet->getDefinitionSet()
			->filterEditable($fieldSet, $editMode)
			->filterOnly($mediaItem->category_id ? $mediaItem->Category->field_cache : $mediaItem->Album->field_cache);

		$customFieldsShown = array_keys($fieldDefinition->getFieldDefinitions());

		if ($customFieldsShown)
		{
			$fieldSet->bulkSet($customFields, $customFieldsShown, $editMode);
		}
	}

	public function setSendAlert($alert, $reason = null)
	{
		$this->alert = (bool)$alert;
		if ($reason !== null)
		{
			$this->alertReason = $reason;
		}
	}

	public function checkForSpam()
	{
		if ($this->mediaItem->media_state == 'visible' && \XF::visitor()->isSpamCheckRequired())
		{
			$this->mediaItemPreparer->checkForSpam();
		}
	}

	protected function finalSetup() {}

	protected function _validate()
	{
		$this->finalSetup();

		$this->mediaItem->preSave();
		return $this->mediaItem->getErrors();
	}

	protected function _save()
	{
		$mediaItem = $this->mediaItem;
		$visitor = \XF::visitor();

		$db = $this->db();
		$db->beginTransaction();

		$mediaItem->save(true, false);

		$this->mediaItemPreparer->afterUpdate();

		if ($mediaItem->media_state == 'visible' && $this->alert && $mediaItem->user_id != $visitor->user_id)
		{
			/** @var \XFMG\Repository\Media $mediaRepo */
			$mediaRepo = $this->repository('XFMG:Media');
			$mediaRepo->sendModeratorActionAlert($mediaItem, 'edit', $this->alertReason);
		}

		$db->commit();

		return $mediaItem;
	}
}