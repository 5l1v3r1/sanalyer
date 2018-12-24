<?php

namespace XFMG\Alert;

use XF\Alert\AbstractHandler;

class Comment extends AbstractHandler
{
	public function getOptOutActions()
	{
		return [
			'insert',
			'quote',
			'mention',
			'like'
		];
	}

	public function getOptOutDisplayOrder()
	{
		return 210;
	}
}