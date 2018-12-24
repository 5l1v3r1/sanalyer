<?php

namespace XFMG\Service\Album;

use XF\Service\AbstractService;

class Creator extends AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/**
	 * @var \XFMG\XF\Entity\User
	 */
	protected $user;

	/**
	 * @var \XFMG\Entity\Album
	 */
	protected $album;

	/**
	 * @var Preparer
	 */
	protected $albumPreparer;

	protected $addUsers;
	protected $viewUsers;

	protected $logIp = true;

	public function __construct(\XF\App $app)
	{
		parent::__construct($app);
		$this->setAlbum();
	}

	protected function setAlbum()
	{
		$this->album = $this->em()->create('XFMG:Album');

		$this->albumPreparer = $this->service('XFMG:Album\Preparer', $this->album);

		$this->setUser(\XF::visitor());
		$this->setAlbumDefaults();
	}

	public function setUser(\XF\Entity\User $user)
	{
		$this->user = $user;

		$this->album->user_id = $user->user_id;
		$this->album->username = $user->username;
	}

	public function getAlbum()
	{
		return $this->album;
	}

	public function getAlbumPreparer()
	{
		return $this->albumPreparer;
	}

	public function logIp($logIp)
	{
		$this->albumPreparer->logIp($logIp);
	}

	protected function setAlbumDefaults()
	{
		$this->album->album_state = 'visible';
	}

	public function setTitle($title, $description = '')
	{
		$this->album->title = $title;

		if ($description)
		{
			$this->albumPreparer->setDescription($description);
		}
	}

	public function setCategory(\XFMG\Entity\Category $category)
	{
		$this->album->category_id = $category->category_id;

		$this->setViewPrivacy('inherit');
	}

	public function setAddPrivacy($value, $addUsers = null)
	{
		$album = $this->album;
		$album->add_privacy = $value;
		$this->addUsers = $addUsers;
	}

	public function setViewPrivacy($value, $viewUsers = null)
	{
		$album = $this->album;
		$album->view_privacy = $value;
		$this->viewUsers = $viewUsers;
	}

	public function checkForSpam()
	{
		if ($this->album->album_state == 'visible' && \XF::visitor()->isSpamCheckRequired())
		{
			$this->albumPreparer->checkForSpam();
		}
	}

	protected function finalSetup()
	{
		$this->album->create_date = time();
	}

	public function _validate()
	{
		$this->finalSetup();

		$album = $this->album;
		$album->preSave();

		return $album->getErrors();
	}

	public function _save()
	{
		$album = $this->album;

		$db = $this->db();
		$db->beginTransaction();

		$album->save();

		if ($this->viewUsers)
		{
			/** @var SharedUserManager $shareManager */
			$shareManager = $this->service('XFMG:Album\SharedUserManager', $album, $this->viewUsers, 'view');
			$album->fastUpdate('view_users', $shareManager->saveSharedUsers());
			$shareManager->notifyUsers();
		}
		if ($this->addUsers)
		{
			/** @var SharedUserManager $shareManager */
			$shareManager = $this->service('XFMG:Album\SharedUserManager', $album, $this->addUsers, 'add');
			$album->fastUpdate('add_users', $shareManager->saveSharedUsers());
			$shareManager->notifyUsers();
		}

		$this->albumPreparer->afterInsert();

		$db->commit();

		return $album;
	}

	public function sendNotifications()
	{
		/** @var \XFMG\Service\Album\Notifier $notifier */
		$notifier = $this->service('XFMG:Album\Notifier', $this->album);
		$notifier->setMentionedUserIds($this->albumPreparer->getMentionedUserIds());
		$notifier->notifyAndEnqueue(3);
	}
}