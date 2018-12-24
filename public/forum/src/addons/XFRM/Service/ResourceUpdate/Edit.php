<?php

namespace XFRM\Service\ResourceUpdate;

use XFRM\Entity\ResourceUpdate;

class Edit extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/**
	 * @var ResourceUpdate
	 */
	protected $update;

	/**
	 * @var \XFRM\Service\ResourceUpdate\Preparer
	 */
	protected $updatePreparer;

	protected $oldMessage;

	protected $alert = false;
	protected $alertReason = '';

	public function __construct(\XF\App $app, ResourceUpdate $update)
	{
		parent::__construct($app);

		$this->update = $update;
		$this->updatePreparer = $this->service('XFRM:ResourceUpdate\Preparer', $update);
	}

	public function getUpdate()
	{
		return $this->update;
	}

	public function getUpdatePreparer()
	{
		return $this->updatePreparer;
	}

	public function setMessage($message, $format = true)
	{
		return $this->updatePreparer->setMessage($message, $format);
	}

	public function setTitle($title)
	{
		$this->update->title = $title;
	}

	public function setAttachmentHash($hash)
	{
		$this->updatePreparer->setAttachmentHash($hash);
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
		if ($this->update->message_state == 'visible' && \XF::visitor()->isSpamCheckRequired())
		{
			$this->updatePreparer->checkForSpam();
		}
	}

	protected function finalSetup()
	{

	}

	protected function _validate()
	{
		$this->finalSetup();

		$this->update->preSave();
		return $this->update->getErrors();
	}

	protected function _save()
	{
		$update = $this->update;
		$visitor = \XF::visitor();

		$db = $this->db();
		$db->beginTransaction();

		$update->save(true, false);

		$this->updatePreparer->afterUpdate();

		if ($update->message_state == 'visible' && $this->alert && $update->Resource->user_id != $visitor->user_id)
		{
			/** @var \XFRM\Repository\ResourceUpdate $updateRepo */
			$updateRepo = $this->repository('XFRM:ResourceUpdate');
			$updateRepo->sendModeratorActionAlert($this->update, 'edit', $this->alertReason);
		}

		$db->commit();

		return $update;
	}
}