<?php

namespace XFMG\ControllerPlugin;

class MediaList extends AbstractList
{
	public function getMediaListData(array $sourceCategoryIds, $page = 1, \XF\Entity\User $user = null)
	{
		$mediaRepo = $this->getMediaRepo();

		$allowOwnPending = is_callable([$this->controller, 'hasContentPendingApproval'])
			? $this->controller->hasContentPendingApproval()
			: true;

		if ($user)
		{
			$mediaFinder = $mediaRepo->findMediaForUser($user, $sourceCategoryIds, [
				'allowOwnPending' => $allowOwnPending
			]);
		}
		else
		{
			$mediaFinder = $mediaRepo->findMediaForIndex($sourceCategoryIds, [
				'allowOwnPending' => $allowOwnPending
			]);
		}

		if (!$user && $this->options()->xfmgMediaIndexLimit)
		{
			$mediaFinder->limitByDate($this->options()->xfmgMediaIndexLimit);
		}

		$filters = $this->getFilterInput();
		$this->applyFilters($mediaFinder, $filters);

		$totalItems = $mediaFinder->total();

		$page = $this->filterPage($page);
		$perPage = $this->options()->xfmgMediaPerPage;

		$mediaFinder->limitByPage($page, $perPage);
		$mediaItems = $mediaFinder->fetch()->filterViewable();

		if (!empty($filters['owner_id']) && !$user)
		{
			$ownerFilter = $this->em()->find('XF:User', $filters['owner_id']);
		}
		else
		{
			$ownerFilter = null;
		}

		$canInlineMod = ($user && \XF::visitor()->user_id == $user->user_id);
		if (!$canInlineMod)
		{
			foreach ($mediaItems AS $mediaItem)
			{
				/** @var \XFMG\Entity\MediaItem $mediaItem */
				if ($mediaItem->canUseInlineModeration())
				{
					$canInlineMod = true;
					break;
				}
			}
		}

		return [
			'mediaItems' => $mediaItems,
			'filters' => $filters,
			'ownerFilter' => $ownerFilter,
			'canInlineMod' => $canInlineMod,

			'totalItems' => $totalItems,
			'page' => $page,
			'perPage' => $perPage,

			'user' => $user
		] + $this->getMediaListMessages();
	}

	public function getMediaListMessages()
	{
		$transcoding = false;
		$pendingApproval = false;

		$session = $this->session();
		if ($session->keyExists('xfmgTranscoding'))
		{
			$session->remove('xfmgTranscoding');
			$transcoding = true;
		}
		if ($session->keyExists('xfmgPendingApproval'))
		{
			$session->remove('xfmgPendingApproval');
			$pendingApproval = true;
		}

		return [
			'transcoding' => $transcoding,
			'pendingApproval' => $pendingApproval,
		];
	}

	protected $defaultSort = 'media_date';

	public function getAvailableSorts()
	{
		$sorts = parent::getAvailableSorts();

		return ['media_date' => 'media_date'] + $sorts;
	}

	protected function apply(array $filters, \XFMG\Entity\Category $category = null, \XF\Entity\User $user = null)
	{
		if ($user)
		{
			return $this->redirect($this->buildLink(
				'media/users',
				$user,
				$filters
			));
		}
		else
		{
			return $this->redirect($this->buildLink(
				$category ? 'media/categories' : 'media',
				$category,
				$filters
			));
		}
	}

	public function getFilterInput()
	{
		$filters = parent::getFilterInput();

		$input = $this->filter([
			'type' => 'str'
		]);

		if ($input['type'] && in_array($input['type'], ['image', 'audio', 'video', 'embed']))
		{
			$filters['type'] = $input['type'];
		}

		return $filters;
	}

	public function applyFilters(\XF\Mvc\Entity\Finder $finder, array $filters)
	{
		parent::applyFilters($finder, $filters);

		if (!empty($filters['type']))
		{
			$finder->where('media_type', $filters['type']);
		}
	}

	public function actionFilters(\XFMG\Entity\Category $category = null, \XF\Entity\User $user = null)
	{
		$reply = parent::actionFilters($category, $user);

		if ($reply instanceof \XF\Mvc\Reply\View)
		{
			$reply->setParam('showTypeFilters', true);

			if ($user)
			{
				$reply->setParam('action', $this->buildLink('media/users/filters', $user));
			}
			else
			{
				$reply->setParam('action', $this->buildLink($category ? 'media/categories/filters' : 'media/filters', $category));
			}

			$reply->setParam('type', 'media');
		}

		return $reply;
	}

	/**
	 * @return \XFMG\Repository\Media
	 */
	protected function getMediaRepo()
	{
		return $this->repository('XFMG:Media');
	}

	/**
	 * @return \XFMG\Repository\Category
	 */
	protected function getCategoryRepo()
	{
		return $this->repository('XFMG:Category');
	}
}