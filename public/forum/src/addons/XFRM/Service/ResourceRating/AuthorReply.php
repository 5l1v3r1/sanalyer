<?php

namespace XFRM\Service\ResourceRating;

use XFRM\Entity\ResourceRating;

class AuthorReply extends \XF\Service\AbstractService
{
	/**
	 * @var ResourceRating
	 */
	protected $rating;

	protected $sendAlert = true;

	public function __construct(\XF\App $app, ResourceRating $rating)
	{
		parent::__construct($app);
		$this->rating = $rating;
	}

	public function getRating()
	{
		return $this->rating;
	}

	public function reply($message, &$error = null)
	{
		if (!$message)
		{
			$error = \XF::phrase('please_enter_valid_message');
			return false;
		}

		$hasExistingResponse = ($this->rating->author_response ? true : false);

		$this->rating->author_response = $message;
		$this->rating->save();

		if (!$hasExistingResponse && $this->sendAlert)
		{
			$this->repository('XFRM:ResourceRating')->sendAuthorReplyAlert($this->rating);
		}

		return true;
	}
}