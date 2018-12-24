<?php

namespace XenGenTr\XGTForumistatistik\Widget;

use XF\Widget\AbstractWidget;

class XGTEnCokGrnKonular extends AbstractWidget
{
	protected $defaultOptions = [
		'style' => 'full',
		'node_ids' => []
	];

	public function render()
	{
        
		$visitor = \XF::visitor();
		$options = $this->options;

        $isAddonEnabledGlobally = \XF::options()->offsetGet('XGT_istatistik_EnCokGoruntulenen_konu_ackapat');

        if(!$isAddonEnabledGlobally)
        {
            return false;
        }

        /*** gosterim limiti ***/
        $limit = \XF::options()->XGT_istatistik_EnCokGoruntulenen_konu_limiti;
		$filter = $options['filter'];

        /*** Haric forumlar ***/
		$nodeIds = \XF::options()->XGT_istatistik_EnCokGoruntulenen_konu_forumlari;

	    $router = $this->app->router('public');


		/** @var \XF\Finder\Thread $threadFinder */
		$threadFinder = $this->finder('XF:Thread');
		$threadFinder
		    ->with(['Forum', 'User'])
			->with('Forum.Node.Permissions|' . $visitor->permission_combination_id)
			->where('discussion_state', 'visible')
			->where('discussion_type', '<>', 'redirect')
			->where('view_count', '>', 1)
			->order('view_count', 'DESC')
			->limit(max($limit * 2, 10));


		if ($nodeIds && !in_array(0, $nodeIds))
		{
			$threadFinder->where('node_id', $nodeIds);
		}

		    switch ($timeLapse)
		    {

                default:
			    case 'alltime':
					$title = \XF::phrase('ml_most_viewed_threads_all_time');
				break;
				
				case 'custom':
					$date = new \DateTime();
					$now = $date->getTimestamp();
					$optionsDays = $custom_time_frame_days * 86400;
					$days = $now - $optionsDays;
					$threadFinder->where('post_date', '>=', $days);
					$title = \XF::phrase('ml_most_viewed_threads_last_x_days', ['days' => $custom_time_frame_days]);
				break;
		    }
	
				 
		/** @var \XF\Entity\Thread $thread */
		foreach ($threads = $threadFinder->fetch() AS $threadId => $thread)
		{
			if (!$thread->canView()
				|| $visitor->isIgnoring($thread->user_id)
			)
			{
				unset($threads[$threadId]);
			}

			if ($options['style'] != 'expanded' && $visitor->isIgnoring($thread->last_post_user_id))
			{
				unset($threads[$threadId]);
			}
		}
		
		$total = $threads->count();
		$threads = $threads->slice(0, $limit, true);

		$viewParams = [
			'title' => $this->getTitle() ?: $title,
			'link' => $link,
			'threads' => $threads,
		];
		return $this->renderer('XGT_istatistik_encokgrt_konular', $viewParams);
	}

	public function verifyOptions(\XF\Http\Request $request, array &$options, &$error = null)
	{
		$options = $request->filter([
			'limit' => 'uint',
			'filter' => 'str',
			'node_ids' => 'array-uint'
		]);
		if ($options['limit'] < 1)
		{
			$options['limit'] = 1;
		}

		return true;
	}
}