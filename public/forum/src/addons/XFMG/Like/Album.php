<?php

namespace XFMG\Like;

use XF\Like\AbstractHandler;
use XF\Mvc\Entity\Entity;

class Album extends AbstractHandler
{
	public function likesCounted(Entity $entity)
	{
		return ($entity->album_state == 'visible');
	}
}