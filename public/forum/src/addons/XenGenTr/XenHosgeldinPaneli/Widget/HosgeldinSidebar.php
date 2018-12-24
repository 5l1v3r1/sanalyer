<?php

namespace XenGenTr\XenHosgeldinPaneli\Widget;

use \XF\Widget\AbstractWidget;

class HosgeldinSidebar extends AbstractWidget
{

    public function render()
    {
		// Toplam mesaj sayýsýný al
		$finder = \XF::finder('XF:Thread');
		$threads = $finder->fetch();
		$threadsTotal = count($threads);

		// Toplam konu sayýsýný al
		$finder = \XF::finder('XF:Post');
		$posts = $finder->fetch();
		$postsTotal = count($posts);
		
		// Toplam kullanýcýyý al
		$finder = \XF::finder('XF:User');
		$users = $finder->fetch();
		$usersTotal = count($users);

		// Son kullaniciyi al
		$finder = \XF::finder('XF:User');
		$userLatest = $finder->order('register_date', 'DESC')->fetchOne();
		
		// viewParams
		$viewParams = [
			'threadsTotal' => $threadsTotal,
			'postsTotal' => $postsTotal,
			'usersTotal' => $usersTotal,
			'userLatest' => $userLatest
		];

		// gonder gitsin 
		return $this->renderer('HosgeldinPaneliSidebar', $viewParams);
    }

	public function getOptionsTemplate()
	{
	   return null;
	}
}