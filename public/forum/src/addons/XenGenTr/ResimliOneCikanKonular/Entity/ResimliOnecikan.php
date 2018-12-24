<?php

namespace XenGenTr\ResimliOneCikanKonular\Entity;

use XF\Mvc\Entity\Structure;

class ResimliOnecikan extends \XF\Mvc\Entity\Entity
{
	public function canEdit()
	{
		$thread = $this->Thread;
		$visitor = \XF::visitor();
		
		if ($visitor->hasPermission('ResimliKonular_izinler', 'resimlionecikan_yonet'))
		{
			return true;
		}
		
		if ($visitor->hasPermission('ResimliKonular_izinler', 'resimlionecikan_gonder')
			&& $visitor->user_id == $thread->user_id)
		{
			return true;
		}
		
		return false;
	}
	
	public function getImage()
	{
		$image = \XF::getRootDirectory() . '/data/XenGenTr/xengentr_resimlikonular/' . $this->thread_id . '.jpg';
		
		if (file_exists($image))
		{
			return 'data/XenGenTr/xengentr_resimlikonular/' . $this->thread_id . '.jpg?' . $this->resimlionecikan_saat;
		}
	
		return "styles/XenGenTr/xengentr_resimlikonular/xengentr_varsayilan.png";
	}
	
	protected function _preSave()
	{
		$this->resimlionecikan_saat = \XF::$time;
	}
	
	protected function _postDelete()
	{
		$image = \XF::getRootDirectory() . '/data/XenGenTr/xengentr_resimlikonular/' . $this->thread_id . '.jpg';
		if (file_exists($image)) { unlink($image); }
	}
	
	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xengentr_resimlionecikanlar';
		$structure->shortName = 'XenGenTr\ResimliOneCikanKonular:ResimliOnecikan';
		$structure->primaryKey = 'thread_id';
		$structure->columns = [
			'thread_id' =>				        ['type' => self::UINT, 'required' => true],
			'resimlionecikan_tarih' =>			['type' => self::UINT, 'required' => true],
			'resimlionecikan_saat' =>			['type' => self::UINT, 'required' => true],
			'resimlionecikan_baslik' =>	        ['type' => self::STR, 'required' => false, 'default' => ''],
			'resimlionecikan_icerik' =>		    ['type' => self::STR, 'required' => false, 'default' => ''],
		];
		$structure->getters = [
			'image' => true,
		];

		$structure->relations = [
			'Thread' => [
				'entity' => 'XF:Thread',
				'type' => self::TO_ONE,
				'conditions' => 'thread_id',
				'primary' => true,
			],
		];

		return $structure;
	}
}