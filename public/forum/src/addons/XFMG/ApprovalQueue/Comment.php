<?php

namespace XFMG\ApprovalQueue;

use XF\ApprovalQueue\AbstractHandler;
use XF\Mvc\Entity\Entity;

class Comment extends AbstractHandler
{
	protected function canActionContent(Entity $content, &$error = null)
	{
		/** @var $content \XFMG\Entity\Comment */
		return $content->canApproveUnapprove($error);
	}

	public function getEntityWith()
	{
		return ['Album', 'Media', 'User'];
	}

	public function actionApprove(\XFMG\Entity\Comment $comment)
	{
		$this->quickUpdate($comment, 'comment_state', 'visible');
	}

	public function actionDelete(\XFMG\Entity\Comment $comment)
	{
		$this->quickUpdate($comment, 'comment_state', 'deleted');
	}
}