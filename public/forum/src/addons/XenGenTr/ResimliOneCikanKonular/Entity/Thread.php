<?php

namespace XenGenTr\ResimliOneCikanKonular\Entity;

use XF\Mvc\Entity\Structure;

class Thread extends XFCP_Thread
{
	
	public static function getStructure(Structure $structure)
	{
		$parent = parent::getStructure($structure);
		
		$structure->relations['ResimliOnecikan'] = [
			'entity' => 'XenGenTr\ResimliOneCikanKonular:ResimliOnecikan',
			'type' => self::TO_ONE,
			'conditions' => 'thread_id',
			'key' => 'resimlionecikan_id',
		];
		
		return $parent;
	}
}