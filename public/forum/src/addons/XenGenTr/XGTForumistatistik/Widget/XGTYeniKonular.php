<?php

namespace XenGenTr\XGTForumistatistik\Widget;

use \XF\Widget\AbstractWidget;

class XGTYeniKonular extends AbstractWidget
{
	protected $defaultOptions = [
		'node_ids' => '',
		'style' => 'full',
		'show_expanded_title' => false
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

        $isAddonEnabledGlobally = \XF::options()->offsetGet('XGT_istatistik_yeniKonu_kapat_ac');

         if(!$isAddonEnabledGlobally)
            {
              return false;
            }

		/*** gosterim limiti ***/
        $limit = \XF::options()->XGT_istatistik_yeniKonu_Limit;
		$style = $options['style'];
		
        /*** Haric forumlar ***/
	    $nodeIds = \XF::options()->XGT_istatistik_yeniKonular_forumlari;

		$router = $this->app->router('public');

		/** @var \XF\Repository\Thread $threadRepo */
		$threadRepo = $this->repository('XF:Thread');

		$threadFinder = $threadRepo->findLatestThreads();
		$title = \XF::phrase('latest_threads');
		$link = $router->buildLink('whats-new/posts', null, ['skip' => 1]);

		$threadFinder
			->with('Forum.Node.Permissions|' . $visitor->permission_combination_id)
			->limit(max($limit * 2, 30));

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
			'hasMore' => $total > $threads->count(),
			'showExpandedTitle' => $options['show_expanded_title']
		];
		return $this->renderer('XGT_istatistik_yenikonular', $viewParams);
	}

	public function verifyOptions(\XF\Http\Request $request, array &$options, &$error = null)
	{
		$options = $request->filter([
			'limit' => 'uint',
			'node_ids' => 'array-uint',
			'show_expanded_title' => 'bool'
		]);

		if ($options['limit'] < 1)
		{
			$options['limit'] = 1;
		}

		
		return true;
	}
}