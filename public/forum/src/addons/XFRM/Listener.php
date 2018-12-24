<?php

namespace XFRM;

class Listener
{
	public static function appSetup(\XF\App $app)
	{
		$container = $app->container();

		$container['prefixes.resource'] = $app->fromRegistry('xfrmPrefixes',
			function(\XF\Container $c) { return $c['em']->getRepository('XFRM:ResourcePrefix')->rebuildPrefixCache(); }
		);

		$container['customFields.resources'] = $app->fromRegistry('xfrmResourceFields',
			function(\XF\Container $c) { return $c['em']->getRepository('XFRM:ResourceField')->rebuildFieldCache(); },
			function(array $fields)
			{
				return new \XF\CustomField\DefinitionSet($fields);
			}
		);
	}

	public static function criteriaUser($rule, array $data, \XF\Entity\User $user, &$returnValue)
	{
		switch ($rule)
		{
			case 'resource_count':
				if (isset($user->xfrm_resource_count) && $user->xfrm_resource_count >= $data['resources'])
				{
					$returnValue = true;
				}
				break;
		}
	}

	public static function postDispatchThread(
		\XF\Mvc\Controller $controller, $action, \XF\Mvc\ParameterBag $params, \XF\Mvc\Reply\AbstractReply &$reply
	)
	{
		if (!($reply instanceof \XF\Mvc\Reply\View))
		{
			return;
		}

		$template = $reply->getTemplateName();

		/** @var \XF\Entity\Thread $thread */
		$thread = $reply->getParam('thread');

		if ($template != 'thread_view' || !$thread || $thread->discussion_type != 'resource')
		{
			return;
		}

		/** @var \XFRM\Entity\ResourceItem $resource */
		$resource = \XF::repository('XFRM:ResourceItem')->findResourceForThread($thread)->fetchOne();
		if (!$resource || !$resource->canView())
		{
			return;
		}

		$reply->setParam('resource', $resource);
	}

	public static function templaterSetup(\XF\Container $container, \XF\Template\Templater &$templater)
	{
		$templater->addFunction('resource_icon', [__CLASS__, 'templaterFnResourceIcon']);
	}

	public static function templaterFnResourceIcon(
		$templater, &$escape, \XFRM\Entity\ResourceItem $resource, $size = 'm', $href = ''
	)
	{
		$escape = false;

		if ($href)
		{
			$tag = 'a';
			$hrefAttr = 'href="' . htmlspecialchars($href) . '"';
		}
		else
		{
			$tag = 'span';
			$hrefAttr = '';
		}

		if (!$resource->icon_date)
		{
			return "<{$tag} {$hrefAttr} class=\"avatar avatar--{$size} avatar--resourceIconDefault\"><span></span></{$tag}>";
		}
		else
		{
			$src = $resource->getIconUrl($size);

			return "<{$tag} {$hrefAttr} class=\"avatar avatar--{$size}\">"
				. '<img src="' . htmlspecialchars($src) . '" alt="' . htmlspecialchars($resource->title) . '" />'
				. "</{$tag}>";
		}
	}

	public static function userContentChangeInit(\XF\Service\User\ContentChange $changeService, array &$updates)
	{
		$updates['xf_rm_category_watch'] = ['user_id', 'emptyable' => false];
		$updates['xf_rm_resource'] = ['user_id', 'username'];
		$updates['xf_rm_resource_download'] = ['user_id', 'emptyable' => false];
		$updates['xf_rm_resource_rating'] = ['user_id', 'emptyable' => false];
		$updates['xf_rm_resource_watch'] = ['user_id', 'emptyable' => false];
	}

	public static function userDeleteCleanInit(\XF\Service\User\DeleteCleanUp $deleteService, array &$deletes)
	{
		$deletes['xf_rm_category_watch'] = 'user_id = ?';
		$deletes['xf_rm_resource_download'] = 'user_id = ?';
		$deletes['xf_rm_resource_watch'] = 'user_id = ?';
	}

	public static function userMergeCombine(
		\XF\Entity\User $target, \XF\Entity\User $source, \XF\Service\User\Merge $mergeService
	)
	{
		$target->xfrm_resource_count += $source->xfrm_resource_count;
	}

	public static function userSearcherOrders(\XF\Searcher\User $userSearcher, array &$sortOrders)
	{
		$sortOrders['xfrm_resource_count'] = \XF::phrase('xfrm_resource_count');
	}
}