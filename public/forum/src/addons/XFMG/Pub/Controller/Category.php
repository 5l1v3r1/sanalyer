<?php

namespace XFMG\Pub\Controller;

use XF\Mvc\ParameterBag;

class Category extends AbstractController
{
	public function actionIndex(ParameterBag $params)
	{
		$visitor = \XF::visitor();
		$category = $this->assertViewableCategory($params->category_id, [
			'Watch|' . $visitor->user_id
		]);

		if ($category->category_type == 'container')
		{
			/** @var \XFMG\ControllerPlugin\MediaList $mediaListPlugin */
			$mediaListPlugin = $this->plugin('XFMG:MediaList');

			$categoryParams = $mediaListPlugin->getCategoryListData($category);

			/** @var \XF\Mvc\Entity\ArrayCollection $descendents */
			$descendents = $categoryParams['descendents'];
			$categoryIds = $descendents->keys();

			$page = $this->filterPage($params->page);
			$perPage = null; // defined on a per type basis

			$this->assertCanonicalUrl($this->buildLink('media/categories', $category, ['page' => $page]));

			$mediaItems = null;
			$albums = null;
			$totalItems = 0;

			$filters = [];
			$ownerFilter = null;

			$canInlineModMediaItems = false;
			$canInlineModAlbums = false;

			if ($categoryParams['primaryType'] == 'album')
			{
				/** @var \XFMG\ControllerPlugin\AlbumList $albumListPlugin */
				$albumListPlugin = $this->plugin('XFMG:AlbumList');

				$perPage = $this->options()->xfmgAlbumsPerPage;

				$albumRepo = $this->getAlbumRepo();
				$albumList = $albumRepo
					->findAlbumsForIndex($categoryIds, [
						'includePersonalAlbums' => false,
						'allowOwnPending' => $this->hasContentPendingApproval()
					])
					->inCategory($categoryIds)
					->limitByPage($page, $perPage);

				$filters = $albumListPlugin->getFilterInput();
				$albumListPlugin->applyFilters($albumList, $filters);

				if (!empty($filters['owner_id']))
				{
					$ownerFilter = $this->em()->find('XF:User', $filters['owner_id']);
				}

				$albums = $albumList->fetch();
				$totalItems = $albumList->total();

				foreach ($albums AS $album)
				{
					if ($album->canUseInlineModeration())
					{
						$canInlineModAlbums = true;
						break;
					}
				}
			}
			else if ($categoryParams['primaryType'] == 'media')
			{
				$perPage = $this->options()->xfmgMediaPerPage;

				$mediaRepo = $this->getMediaRepo();
				$mediaList = $mediaRepo
					->findMediaForIndex($categoryIds, [
						'includePersonalAlbums' => false,
						'allowOwnPending' => $this->hasContentPendingApproval()
					])
					->limitByPage($page, $perPage);

				$filters = $mediaListPlugin->getFilterInput();
				$mediaListPlugin->applyFilters($mediaList, $filters);

				if (!empty($filters['owner_id']))
				{
					$ownerFilter = $this->em()->find('XF:User', $filters['owner_id']);
				}

				$mediaItems = $mediaList->fetch();
				$totalItems = $mediaList->total();

				foreach ($mediaItems AS $mediaItem)
				{
					if ($mediaItem->canUseInlineModeration())
					{
						$canInlineModMediaItems = true;
						break;
					}
				}
			}

			$this->assertValidPage($page, $perPage, $totalItems, 'media/categories', $category);

			$viewParams = $categoryParams + [
				'albums' => $albums,
				'mediaItems' => $mediaItems,

				'page' => $page,
				'perPage' => $perPage,
				'totalItems' => $totalItems,

				'filters' => $filters,
				'ownerFilter' => $ownerFilter,

				'canInlineModAlbums' => $canInlineModAlbums,
				'canInlineModMediaItems' => $canInlineModMediaItems,

				'viewType' => $categoryParams['primaryType']
			];
			return $this->view('XFMG:Category\Container', 'xfmg_category_container', $viewParams);
		}
		else
		{
			return $this->rerouteController(__CLASS__, 'view', $params);
		}
	}

	public function actionView(ParameterBag $params)
	{
		$category = $this->assertViewableCategory($params->category_id, [
			'Watch|' . \XF::visitor()->user_id
		]);

		$page = $this->filterPage($params->page);
		$perPage = null; // defined on a per type basis

		$this->assertCanonicalUrl($this->buildLink('media/categories', $category, ['page' => $page]));

		$mediaItems = null;
		$albums = null;
		$totalItems = 0;

		$categoryParams = [];
		$mediaListMessages = [];

		$filters = [];
		$ownerFilter = null;

		$canInlineModMediaItems = false;
		$canInlineModAlbums = false;

		if ($category->category_type == 'album')
		{
			/** @var \XFMG\ControllerPlugin\AlbumList $albumListPlugin */
			$albumListPlugin = $this->plugin('XFMG:AlbumList');
			$categoryParams = $albumListPlugin->getCategoryListData($category);

			$albumRepo = $this->getAlbumRepo();

			$perPage = $this->options()->xfmgAlbumsPerPage;

			$albumList = $albumRepo
				->findAlbumsForCategory($category->category_id)
				->limitByPage($page, $perPage);

			$filters = $albumListPlugin->getFilterInput();
			$albumListPlugin->applyFilters($albumList, $filters);

			if (!empty($filters['owner_id']))
			{
				$ownerFilter = $this->em()->find('XF:User', $filters['owner_id']);
			}

			$albums = $albumList->fetch();
			$totalItems = $albumList->total();

			foreach ($albums AS $album)
			{
				if ($album->canUseInlineModeration())
				{
					$canInlineModAlbums = true;
					break;
				}
			}
		}
		else if ($category->category_type == 'media')
		{
			/** @var \XFMG\ControllerPlugin\MediaList $mediaListPlugin */
			$mediaListPlugin = $this->plugin('XFMG:MediaList');
			$categoryParams = $mediaListPlugin->getCategoryListData($category);
			$mediaListMessages = $mediaListPlugin->getMediaListMessages();

			$mediaRepo = $this->getMediaRepo();

			$perPage = $this->options()->xfmgMediaPerPage;

			/** @var \XFMG\Finder\MediaItem $mediaList */
			$mediaList = $mediaRepo
				->findMediaInCategory($category->category_id, [
					'allowOwnPending' => $this->hasContentPendingApproval()
				])
				->limitByPage($page, $perPage);

			$filters = $mediaListPlugin->getFilterInput();
			$mediaListPlugin->applyFilters($mediaList, $filters);

			if (!empty($filters['owner_id']))
			{
				$ownerFilter = $this->em()->find('XF:User', $filters['owner_id']);
			}

			$mediaItems = $mediaList->fetch();
			$totalItems = $mediaList->total();

			foreach ($mediaItems AS $mediaItem)
			{
				if ($mediaItem->canUseInlineModeration())
				{
					$canInlineModMediaItems = true;
					break;
				}
			}
		}

		$this->assertValidPage($page, $perPage, $totalItems, 'media/categories', $category);

		$viewParams = $categoryParams + [
			'albums' => $albums,
			'mediaItems' => $mediaItems,

			'page' => $page,
			'perPage' => $perPage,
			'totalItems' => $totalItems,

			'filters' => $filters,
			'ownerFilter' => $ownerFilter,

			'canInlineModAlbums' => $canInlineModAlbums,
			'canInlineModMediaItems' => $canInlineModMediaItems
		] + $mediaListMessages;
		return $this->view('XFMG:Category\View', 'xfmg_category_view', $viewParams);
	}

	public function actionFilters(ParameterBag $params)
	{
		$category = $this->assertViewableCategory($params->category_id);

		if ($category->category_type == 'container')
		{
			/** @var \XFMG\ControllerPlugin\MediaList $mediaListPlugin */
			$mediaListPlugin = $this->plugin('XFMG:MediaList');
			$categoryParams = $mediaListPlugin->getCategoryListData($category);

			$type = $categoryParams['primaryType'];
		}
		else
		{
			$type = $category->category_type;
		}

		if ($type == 'media')
		{
			/** @var \XFMG\ControllerPlugin\MediaList $mediaListPlugin */
			$mediaListPlugin = $this->plugin('XFMG:MediaList');

			return $mediaListPlugin->actionFilters($category);
		}
		else
		{
			/** @var \XFMG\ControllerPlugin\AlbumList $albumListPlugin */
			$albumListPlugin = $this->plugin('XFMG:AlbumList');

			return $albumListPlugin->actionFilters($category);
		}
	}

	public function actionAdd(ParameterBag $params)
	{
		$category = $this->assertViewableCategory($params->category_id);

		if (!$category->canAddMedia($error))
		{
			return $this->noPermission($error);
		}

		$album = null;
		if ($category->category_type == 'album')
		{
			$album = $this->em()->create('XFMG:Album');
			$album->category_id = $category->category_id;
		}

		/** @var \XF\Repository\Attachment $attachmentRepo */
		$attachmentRepo = $this->repository('XF:Attachment');
		$attachmentData = $attachmentRepo->getEditorData('xfmg_media', $category);

		$viewParams = [
			'category' => $category,
			'album' => $album,
			'attachmentData' => $attachmentData
		];
		return $this->view('XFMG:Category\Add', 'xfmg_category_add', $viewParams);
	}

	public function actionMarkViewed(ParameterBag $params)
	{
		$category = $this->assertViewableCategory($params->category_id);

		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return $this->noPermission();
		}

		$markDate = $this->filter('date', 'uint');
		if (!$markDate)
		{
			$markDate = \XF::$time;
		}

		if ($this->isPost())
		{
			$mediaRepo = $this->getMediaRepo();
			$mediaRepo->markMediaViewedByVisitor($category->category_id, $markDate);
			$mediaRepo->markAllMediaCommentsReadByVisitor($category->category_id, $markDate);

			$albumRepo = $this->getAlbumRepo();
			$albumRepo->markAllAlbumCommentsReadByVisitor($category->category_id);

			return $this->redirect(
				$this->buildLink('media/categories', $category),
				\XF::phrase('xfmg_category_x_marked_as_viewed', ['title' => $category->title])
			);
		}
		else
		{
			$viewParams = [
				'category' => $category,
				'date' => $markDate
			];
			return $this->view('XFMG:Category\MarkViewed', 'xfmg_category_mark_viewed', $viewParams);
		}
	}

	public function actionWatch(ParameterBag $params)
	{
		$category = $this->assertViewableCategory($params->category_id);
		if (!$category->canWatch($error))
		{
			return $this->noPermission($error);
		}

		$visitor = \XF::visitor();

		if ($this->isPost())
		{
			if ($this->filter('stop', 'bool'))
			{
				$action = 'delete';
				$config = [];
			}
			else
			{
				$action = 'watch';
				$config = $this->filter([
					'notify_on' => 'str',
					'send_alert' => 'bool',
					'send_email' => 'bool',
					'include_children' => 'bool'
				]);
			}

			/** @var \XFMG\Repository\CategoryWatch $watchRepo */
			$watchRepo = $this->repository('XFMG:CategoryWatch');
			$watchRepo->setWatchState($category, $visitor, $action, $config);

			$redirect = $this->redirect($this->buildLink('media/categories', $category));
			$redirect->setJsonParam('switchKey', $action == 'delete' ? 'watch' : 'unwatch');
			return $redirect;
		}
		else
		{
			$viewParams = [
				'category' => $category,
				'isWatched' => !empty($category->Watch[$visitor->user_id])
			];
			return $this->view('XFMG:Category\Watch', 'xfmg_category_watch', $viewParams);
		}
	}

	public static function getActivityDetails(array $activities)
	{
		return self::getActivityDetailsForContent(
			$activities, \XF::phrase('xfmg_viewing_media_category'), 'category_id',
			function(array $ids)
			{
				$categories = \XF::em()->findByIds(
					'XFMG:Category',
					$ids,
					['Permissions|' . \XF::visitor()->permission_combination_id]
				);

				$router = \XF::app()->router('public');
				$data = [];

				foreach ($categories->filterViewable() AS $id => $category)
				{
					$data[$id] = [
						'title' => $category->title,
						'url' => $router->buildLink('media/categories', $category)
					];
				}

				return $data;
			}
		);
	}
}