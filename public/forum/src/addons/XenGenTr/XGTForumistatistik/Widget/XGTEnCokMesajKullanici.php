<?php

namespace XenGenTr\XGTForumistatistik\Widget;

use \XF\Widget\AbstractWidget;

class XGTEnCokMesajKullanici extends AbstractWidget
{
	protected $defaultOptions = [
		'member_stat_key' => 'most_messages',
		'limit' => 10
	];

	protected function getDefaultTemplateParams($context)
	{
		$params = parent::getDefaultTemplateParams($context);
		if ($context == 'options')
		{
			/** @var \XF\Repository\MemberStat $memberStatRepo */
			$memberStatRepo = $this->repository('XF:MemberStat');
			$memberStats = $memberStatRepo->findMemberStatsForList()
				->where('active', 1)
				->pluckFrom('title', 'member_stat_key');

			$params['memberStats'] = $memberStats;
		}
		return $params;
	}

	public function render()
	{
		if (!\XF::visitor()->canViewMemberList())
		{
			return '';
		}

		$options = $this->options;

        $isAddonEnabledGlobally = \XF::options()->offsetGet('XGT_istatistik_Kullanici_kapat');

        if(!$isAddonEnabledGlobally)
           {
            return false;
           }

		/** @var \XF\Entity\MemberStat $memberStat */
		$memberStat = $this->findOne('XF:MemberStat', [
			'member_stat_key' => $this->options['member_stat_key']
		]);
		if (!$memberStat || !$memberStat->canView())
		{
			return '';
		}

		$results = $memberStat->getResults(true);
		$userIds = array_keys($results);

		/** @var \XF\Finder\User $userFinder */
		$userFinder = $this->finder('XF:User');

		$users = $userFinder
			->with('Option', true)
			->with('Profile', true)
			->where('user_id', array_unique($userIds))
			->isValidUser()
			->fetch();

		$count = 0;
		$resultsData = [];
		foreach ($results AS $userId => $value)
		{
            /*** gosterim limiti ***/
			if ($count == \XF::options()->XGT_istatistik_Kullanici_limiti)
			 {
			 	// we have enough for this stat
				break;
		  	}

			if (!isset($users[$userId]))
			{
				// no valid user record found
				continue;
			}

			$resultsData[$userId] = [
				'user' => $users[$userId],
				'value' => $value
			];

			$count++;
		}

		$viewParams = [
			'title' => $this->getTitle() ?: $memberStat->title,
			'memberStat' => $memberStat,
			'results' => $resultsData
		];
		return $this->renderer('XGT_istatistik_encokcevap_kullanici', $viewParams);
	}

	public function verifyOptions(\XF\Http\Request $request, array &$options, &$error = null)
	{
		$options = $request->filter([
			'member_stat_key' => 'str',
			'limit' => 'uint'
		]);

		$memberStat = $this->findOne('XF:MemberStat', [
			'member_stat_key' => $options['member_stat_key']
		]);
		if (!$memberStat)
		{
			$error = \XF::phrase('no_member_stat_could_be_found_for_id_provided');
		}

		if ($options['limit'] < 1)
		{
			$options['limit'] = 1;
		}

		return true;
	}
}