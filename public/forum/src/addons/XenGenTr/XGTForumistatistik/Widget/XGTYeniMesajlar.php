<?php

namespace XenGenTr\XGTForumistatistik\Widget;

use \XF\Widget\AbstractWidget;

class XGTYeniMesajlar extends AbstractWidget
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

        $isAddonEnabledGlobally = \XF::options()->offsetGet('XGT_istatistik_yeniMesaj_kapat_ac');

         if(!$isAddonEnabledGlobally)
           {
             return false;
           }

        /*** gosterim limiti ***/

       $limit = \XF::options()->XGT_istatistik_yeniMesaj_Limit;
	   $filter = $options['filter'];

        /*** Haric forumlar ***/
	    $nodeIds = \XF::options()->XGT_istatistik_yeniMesaj_forumlari;

		if (!$visitor->user_id)
		{
			$filter = 'latest';
		}

		$router = $this->app->router('public');

		/** @var \XF\Repository\Thread $threadRepo */
		$threadRepo = $this->repository('XF:Thread');

		switch ($filter)
		{
			default:
			case 'latest':
				$threadFinder = $threadRepo->findThreadsWithLatestPosts();
				$title = \XF::phrase('widget.latest_posts');
				$link = $router->buildLink('whats-new/posts', null, ['skip' => 1]);
				break;

		}

		$threadFinder
			->with('Forum.Node.Permissions|' . $visitor->permission_combination_id)
			->limit(max($limit * 2, 30));

		if ($nodeIds && !in_array(0, $nodeIds))
		{
			$threadFinder->where('node_id', $nodeIds);
		}
     
		###if ($options['style'] == 'full')
		###{
		###	$threadFinder->forFullView(full);
		###}

		/** @var \XF\Entity\Thread $thread */
		foreach ($threads = $threadFinder->fetch() AS $threadId => $thread)
		{
			if (!$thread->canView()
				|| $visitor->isIgnoring($thread->user_id)
				|| $visitor->isIgnoring($thread->last_post_user_id)
			)
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
			'filter' => $filter,
			'hasMore' => $total > $threads->count()
		];
		return $this->renderer('XGT_istatistik_yenimesajlar', $viewParams);
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
