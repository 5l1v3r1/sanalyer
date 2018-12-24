<?php

namespace XenGenTr\XGTForumistatistik\Widget;

use XF\Widget\AbstractWidget;

class XGTEnCokLikeKonular extends AbstractWidget
{
	protected $defaultOptions = [
		'style' => 'full',
		'filter' => 'latest',
		'node_ids' => []
	];

	protected function getDefaultTemplateParams($context)
	{
		$params = parent::getDefaultTemplateParams($context);
		if ($context == 'options')
		{
			$nodeRepo = $this->app->repository('XF:Node');
			$params['nodeTree'] = $nodeRepo->createNodeTree($nodeRepo->getFullNodeList());
		}
		return $params;
	}

  public function render()
	{
		$visitor = \XF::visitor();
		$options = $this->options;

        $isAddonEnabledGlobally = \XF::options()->offsetGet('XGT_istatistik_EnCokBegenilen_konu_ackapat');

         if(!$isAddonEnabledGlobally)
           {
             return false;
           }

        /*** gosterim limiti ***/

       $limit = \XF::options()->XGT_istatistik_EnCokBegeni_konu_limiti;
	   $filter = $options['filter'];

        /*** Haric forumlar ***/
	    $nodeIds = \XF::options()->XGT_istatistik_EnCokBegeni_konu_forumlari;

		if (!$visitor->user_id)
		{
			$filter = 'latest';
		}

		$router = $this->app->router('public');
		
		if ($headerUrl != '')
		{
			$link = $router->buildLink($headerUrl);
		}
		else
		{
			$link = false;
		}

		/** @var \XF\Finder\Thread $threadFinder */
		$threadFinder = $this->finder('XF:Thread');
		$threadFinder
		    ->with(['Forum', 'User'])
			->with('Forum.Node.Permissions|' . $visitor->permission_combination_id)
			->where('discussion_state', 'visible')
			->where('discussion_type', '<>', 'redirect')
			->where('first_post_likes', '>', 0)
			->order('first_post_likes', 'DESC')
			->limit(max($limit * 2, 10));
	

		if ($nodeIds && !in_array(0, $nodeIds))
		{
			$threadFinder->where('node_id', $nodeIds);
		}

		if ($style == 'full' || $style == 'expanded')
		{
			$threadFinder->forFullView(true);
			if ($style == 'expanded')
			{
				$threadFinder->with('FirstPost');
			}
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
			'style' => $options['style'],
			'minimal' => $options['minimal'],
			'showExpandedTitle' => $options['show_expanded_title']
		];
		return $this->renderer('XGT_istatistik_encoklike_konular', $viewParams);
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