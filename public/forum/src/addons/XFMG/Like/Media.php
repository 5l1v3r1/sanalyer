<?php

namespace XFMG\Like;

use XF\Like\AbstractHandler;
use XF\Mvc\Entity\Entity;

class Media extends AbstractHandler
{
	public function likesCounted(Entity $entity)
	{
		return ($entity->media_state == 'visible');
	}
}