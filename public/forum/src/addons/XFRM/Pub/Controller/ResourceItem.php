<?php

namespace XFRM\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;

class ResourceItem extends AbstractController
{
	protected function preDispatchController($action, ParameterBag $params)
	{
		/** @var \XFRM\XF\Entity\User $visitor */
		$visitor = \XF::visitor();

		if (!$visitor->canViewResources($error))
		{
			throw $this->exception($this->noPermission($error));
		}
	}

	public function actionIndex(ParameterBag $params)
	{
		if ($params->resource_id)
		{
			return $this->rerouteController(__CLASS__, 'view', $params);
		}

		/** @var \XFRM\ControllerPlugin\Overview $overviewPlugin */
		$overviewPlugin = $this->plugin('XFRM:Overview');

		$categoryParams = $overviewPlugin->getCategoryListData();
		$viewableCategoryIds = $categoryParams['categories']->keys();

		$listParams = $overviewPlugin->getCoreListData($viewableCategoryIds);

		$this->assertValidPage($listParams['page'], $listParams['perPage'], $listParams['total'], 'resources');
		$this->assertCanonicalUrl($this->buildLink('resources', null, ['page' => $listParams['page']]));

		$viewParams = $categoryParams + $listParams;

		return $this->view('XFRM:Overview', 'xfrm_overview', $viewParams);
	}

	public function actionFilters()
	{
		/** @var \XFRM\ControllerPlugin\Overview $overviewPlugin */
		$overviewPlugin = $this->plugin('XFRM:Overview');

		return $overviewPlugin->actionFilters();
	}

	public function actionFeatured()
	{
		/** @var \XFRM\ControllerPlugin\Overview $overviewPlugin */
		$overviewPlugin = $this->plugin('XFRM:Overview');

		return $overviewPlugin->actionFeatured();
	}

	public function actionLatestReviews()
	{
		$viewableCategoryIds = $this->getCategoryRepo()->getViewableCategoryIds();

		$ratingRepo = $this->repository('XFRM:ResourceRating');
		$finder = $ratingRepo->findLatestReviews($viewableCategoryIds);

		$total = $finder->total();
		$page = $this->filterPage();
		$perPage = $this->options()->xfrmReviewsPerPage;

		$this->assertValidPage($page, $perPage, $total, 'resources/latest-reviews');
		$this->assertCanonicalUrl($this->buildLink('resources/latest-reviews', null, ['page' => $page]));

		$reviews = $finder->limitByPage($page, $perPage)->fetch();
		$reviews = $reviews->filterViewable();

		$viewParams = [
			'reviews' => $reviews,
			'page' => $page,
			'perPage' => $perPage,
			'total' => $total
		];
		return $this->view('XFRM:LatestReviews', 'xfrm_latest_reviews', $viewParams);
	}

	public function actionAdd()
	{
		/** @var \XFRM\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		if (!$visitor->canAddResource($error))
		{
			return $this->noPermission($error);
		}

		$this->assertCanonicalUrl($this->buildLink('resources/add'));

		$categoryRepo = $this->getCategoryRepo();

		$categories = $categoryRepo->getViewableCategories();
		$canAdd = false;

		foreach ($categories AS $category)
		{
			/** @var \XFRM\Entity\Category $category */
			if ($category->canAddResource())
			{
				$canAdd = true;
				break;
			}
		}

		if (!$canAdd)
		{
			return $this->noPermission();
		}

		$categoryTree = $categoryRepo->createCategoryTree($categories);
		$categoryTree = $categoryTree->filter(null, function($id, \XFRM\Entity\Category $category, $depth, $children)
		{
			if ($children)
			{
				return true;
			}
			if ($category->canAddResource())
			{
				return true;
			}

			return false;
		});

		$categoryExtras = $categoryRepo->getCategoryListExtras($categoryTree);

		$viewParams = [
			'categoryTree' => $categoryTree,
			'categoryExtras' => $categoryExtras
		];
		return $this->view('XFRM:ResourceItem\AddChooser', 'xfrm_resource_add_chooser', $viewParams);
	}

	public function actionView(ParameterBag $params)
	{
		$resource = $this->assertViewableResource($params->resource_id, $this->getResourceViewExtraWith());

		$this->assertCanonicalUrl($this->buildLink('resources', $resource));

		$latestUpdates = $this->em()->getEmptyCollection();

		if ($resource->real_update_count)
		{
			$recentUpdatesMax = $this->options()->xfrmRecentUpdatesCount;
			if ($recentUpdatesMax)
			{
				/** @var \XFRM\Repository\ResourceUpdate $updateRepo */
				$updateRepo = $this->repository('XFRM:ResourceUpdate');
				$latestUpdates = $updateRepo->findUpdatesInResource($resource)->fetch($recentUpdatesMax);
			}
		}

		$latestReviews = $this->em()->getEmptyCollection();

		if ($resource->real_review_count)
		{
			$recentReviewsMax = $this->options()->xfrmRecentReviewsCount;
			if ($recentReviewsMax)
			{
				/** @var \XFRM\Repository\ResourceRating $ratingRepo */
				$ratingRepo = $this->repository('XFRM:ResourceRating');
				$latestReviews = $ratingRepo->findReviewsInResource($resource)->forFullView()->fetch($recentReviewsMax);
			}
		}

		if ($this->options()->xfrmAuthorOtherResourcesCount && $resource->User)
		{
			$authorOthers = $this->getResourceRepo()
				->findOtherResourcesByAuthor($resource)
				->fetch($this->options()->xfrmAuthorOtherResourcesCount);
			$authorOthers = $authorOthers->filterViewable();
		}
		else
		{
			$authorOthers = $this->em()->getEmptyCollection();
		}

		$trimmedDescription = null;

		if (!$resource->canViewFullDescription())
		{
			$snippet = $this->app->stringFormatter()->wholeWordTrim(
				$resource->Description->message,
				$this->options()->xfrmFilelessViewFull['length']
			);
			if (strlen($snippet) < strlen($resource->Description->message))
			{
				// just cleaning BB code, so not passing the content
				$trimmedDescription = $this->app->bbCode()->render($snippet, 'bbCodeClean', 'resource_update', null);
			}
		}

		$viewParams = [
			'resource' => $resource,
			'category' => $resource->Category,
			'description' => $resource->Description,
			'trimmedDescription' => $trimmedDescription,
			'latestUpdates' => $latestUpdates,
			'latestReviews' => $latestReviews,
			'authorOthers' => $authorOthers
		];
		return $this->view('XFRM:ResourceItem\View', 'xfrm_resource_view', $viewParams);
	}

	protected function getResourceViewExtraWith()
	{
		$extraWith = ['Featured'];
		$userId = \XF::visitor()->user_id;
		if ($userId)
		{
			$extraWith[] = 'Watch|' . $userId;
			$extraWith[] = 'CurrentVersion.Downloads|' . $userId;
			$extraWith[] = 'Description.Likes|' . $userId;
		}

		return $extraWith;
	}

	public function actionExtra(ParameterBag $params)
	{
		$resource = $this->assertViewableResource($params->resource_id);
		if (!$resource->hasExtraInfoTab())
		{
			return $this->redirect($this->buildLink('resources', $resource));
		}

		$viewParams = [
			'resource' => $resource,
			'category' => $resource->Category
		];
		return $this->view('XFRM:ResourceItem\Extra', 'xfrm_resource_extra', $viewParams);
	}

	public function actionField(ParameterBag $params)
	{
		$resource = $this->assertViewableResource($params->resource_id);

		$fieldId = $this->filter('field', 'str');
		$tabFields = $resource->getExtraFieldTabs();

		if (!isset($tabFields[$fieldId]))
		{
			return $this->redirect($this->buildLink('resources', $resource));
		}

		/** @var \XF\CustomField\Set $fieldSet */
		$fieldSet = $resource->custom_fields;
		$definition = $fieldSet->getDefinition($fieldId);
		$fieldValue = $fieldSet->getFieldValue($fieldId);

		$viewParams = [
			'resource' => $resource,
			'category' => $resource->Category,

			'fieldId' => $fieldId,
			'fieldDefinition' => $definition,
			'fieldValue' => $fieldValue
		];
		return $this->view('XFRM:ResourceItem\Field', 'xfrm_resource_field', $viewParams);
	}

	public function actionUpdates(ParameterBag $params)
	{
		$resource = $this->assertViewableResource($params->resource_id);

		$this->assertCanonicalUrl($this->buildLink('resources/updates', $resource));

		$page = $this->filterPage();
		$perPage = $this->options()->xfrmUpdatesPerPage;

		/** @var \XFRM\Repository\ResourceUpdate $updateRepo */
		$updateRepo = $this->repository('XFRM:ResourceUpdate');
		$updateFinder = $updateRepo->findUpdatesInResource($resource);

		$total = $resource->real_update_count;
		if (!$total)
		{
			return $this->redirect($this->buildLink('resources', $resource));
		}

		$this->assertValidPage($page, $perPage, $total, 'resources/updates', $resource);
		$this->assertCanonicalUrl($this->buildLink('resources/updates', $resource, ['page' => $page]));

		$updateFinder->limitByPage($page, $perPage);
		$updates = $updateFinder->fetch();

		$viewParams = [
			'resource' => $resource,
			'updates' => $updates,

			'page' => $page,
			'perPage' => $perPage,
			'total' => $total
		];
		return $this->view('XFRM:ResourceItem\Updates', 'xfrm_resource_updates', $viewParams);
	}

	public function actionReviews(ParameterBag $params)
	{
		if (!$params->resource_id)
		{
			return $this->redirectPermanently($this->buildLink('resources/latest-reviews'));
		}

		$resource = $this->assertViewableResource($params->resource_id);

		$this->assertCanonicalUrl($this->buildLink('resources/reviews', $resource));

		$reviewId = $this->filter('resource_rating_id', 'uint');
		if ($reviewId)
		{
			/** @var \XFRM\Entity\ResourceRating|null $review */
			$review = $this->em()->find('XFRM:ResourceRating', $reviewId);
			if (!$review || $review->resource_id != $resource->resource_id || !$review->is_review)
			{
				return $this->noPermission();
			}
			if (!$review->canView($error))
			{
				return $this->noPermission($error);
			}

			return $this->redirectPermanently($this->buildLink('resources/review', $review));
		}

		$page = $this->filterPage();
		$perPage = $this->options()->xfrmReviewsPerPage;

		/** @var \XFRM\Repository\ResourceRating $ratingRepo */
		$ratingRepo = $this->repository('XFRM:ResourceRating');
		$reviewFinder = $ratingRepo->findReviewsInResource($resource);

		$total = $resource->real_review_count;
		if (!$total)
		{
			return $this->redirect($this->buildLink('resources', $resource));
		}

		$this->assertValidPage($page, $perPage, $total, 'resources/reviews', $resource);
		$this->assertCanonicalUrl($this->buildLink('resources/reviews', $resource, ['page' => $page]));

		$reviewFinder->forFullView()->limitByPage($page, $perPage);
		$reviews = $reviewFinder->fetch();

		$viewParams = [
			'resource' => $resource,
			'reviews' => $reviews,

			'page' => $page,
			'perPage' => $perPage,
			'total' => $total
		];
		return $this->view('XFRM:ResourceItem\Reviews', 'xfrm_resource_reviews', $viewParams);
	}

	public function actionHistory(ParameterBag $params)
	{
		$resource = $this->assertViewableResource($params->resource_id);

		if (!$resource->isVersioned())
		{
			return $this->redirect($this->buildLink('resources', $resource));
		}

		$this->assertCanonicalUrl($this->buildLink('resources/history', $resource));

		/** @var \XFRM\Repository\ResourceVersion $versionRepo */
		$versionRepo = $this->repository('XFRM:ResourceVersion');
		$versionFinder = $versionRepo->findVersionsInResource($resource);

		$versions = $versionFinder->fetch();

		$hasDelete = false;
		$hasDownload = false;
		foreach ($versions AS $version)
		{
			/** @var \XFRM\Entity\ResourceVersion $version */
			if ($version->canDelete())
			{
				$hasDelete = true;
			}
			if ($version->isDownloadable())
			{
				$hasDownload = true;
			}
		}

		$viewParams = [
			'resource' => $resource,
			'versions' => $versions,
			'hasDelete' => $hasDelete,
			'hasDownload' => $hasDownload
		];
		return $this->view('XFRM:ResourceItem\History', 'xfrm_resource_history', $viewParams);
	}

	public function actionDownload(ParameterBag $params)
	{
		$resource = $this->assertViewableResource($params->resource_id);
		$version = $resource->CurrentVersion;

		$error = null;
		if (!$version || !$version->canDownload($error))
		{
			return $this->noPermission($error);
		}

		return $this->rerouteController('XFRM:ResourceVersion', 'download', [
			'resource_id' => $resource->resource_id,
			'resource_version_id' => $version->resource_version_id
		]);
	}

	/**
	 * @param \XFRM\Entity\ResourceItem $resource
	 *
	 * @return \XFRM\Service\ResourceItem\Rate
	 */
	protected function setupResourceRate(\XFRM\Entity\ResourceItem $resource)
	{
		/** @var \XFRM\Service\ResourceItem\Rate $rater */
		$rater = $this->service('XFRM:ResourceItem\Rate', $resource);

		$input = $this->filter([
			'rating' => 'uint',
			'message' => 'str',
			'is_anonymous' => 'bool'
		]);

		$rater->setRating($input['rating'], $input['message']);

		if ($this->options()->xfrmAllowAnonReview && $input['is_anonymous'])
		{
			$rater->setIsAnonymous();
		}

		return $rater;
	}

	public function actionRate(ParameterBag $params)
	{
		$visitorUserId = \XF::visitor()->user_id;

		$extraWith = [
			'CurrentVersion.Downloads|' . $visitorUserId,
			'CurrentVersion.Ratings|' . $visitorUserId,
		];
		$resource = $this->assertViewableResource($params->resource_id, $extraWith);
		if (!$resource->canRate(true, $error))
		{
			return $this->noPermission($error);
		}

		/** @var \XFRM\Entity\ResourceRating|null $existingRating */
		$existingRating = $resource->CurrentVersion->Ratings[$visitorUserId];
		if ($existingRating && !$existingRating->canUpdate($error))
		{
			return $this->noPermission($error);
		}

		if ($this->isPost())
		{
			$rater = $this->setupResourceRate($resource);
			$rater->checkForSpam();

			if (!$rater->validate($errors))
			{
				return $this->error($errors);
			}

			$rating = $rater->save();

			return $this->redirect($this->buildLink(
				$rating->is_review ? 'resources/reviews' : 'resources',
				$resource
			));
		}
		else
		{
			$viewParams = [
				'resource' => $resource,
				'category' => $resource->Category,
				'existingRating' => $existingRating
			];
			return $this->view('XF:ResourceItem\Rate', 'xfrm_resource_rate', $viewParams);
		}
	}

	/**
	 * @param \XFRM\Entity\ResourceItem $resource
	 *
	 * @return \XFRM\Service\ResourceItem\CreateVersionUpdate
	 */
	protected function setupResourcePostUpdate(\XFRM\Entity\ResourceItem $resource)
	{
		/** @var \XFRM\Service\ResourceItem\CreateVersionUpdate $creator */
		$creator = $this->service('XFRM:ResourceItem\CreateVersionUpdate', $resource);

		if ($this->filter('new_version', 'bool') && $resource->hasUpdatableVersionData())
		{
			$versionCreator = $creator->getVersionCreator();

			$versionCreator->setVersionString($this->filter('version_string', 'str'), true);

			if ($resource->isDownloadable())
			{
				$category = $resource->Category;

				if ($this->filter('version_type', 'str') == 'local')
				{
					if ($category->allow_local || $resource->getResourceTypeDetailed() == 'download_local')
					{
						$versionCreator->setAttachmentHash($this->filter('version_attachment_hash', 'str'));
					}
				}
				else
				{
					if ($category->allow_external || $resource->getResourceTypeDetailed() == 'download_external')
					{
						$versionCreator->setDownloadUrl($this->filter('external_download_url', 'str'));
					}
				}
			}

			if ($resource->isExternalPurchasable())
			{
				$purchaseFields = $this->filter([
					'price' => 'num',
					'currency' => 'str',
					'external_purchase_url' => 'str',
				]);
				$creator->addResourceChanges($purchaseFields);
			}
		}

		if ($this->filter('new_update', 'bool'))
		{
			$updateCreator = $creator->getUpdateCreator();

			$message = $this->plugin('XF:Editor')->fromInput('update_message');
			$updateCreator->setMessage($message);
			$updateCreator->setTitle($this->filter('update_title', 'str'));

			/** @var \XFRM\Entity\Category $category */
			$category = $resource->Category;
			if ($category->canUploadAndManageUpdateImages())
			{
				$updateCreator->setAttachmentHash($this->filter('attachment_hash', 'str'));
			}
		}

		return $creator;
	}

	protected function finalizeResourcePostUpdate(\XFRM\Service\ResourceItem\CreateVersionUpdate $updater)
	{
		$updater->sendNotifications();

		$updater->getResource()->draft_update->delete();
	}

	public function actionPostUpdate(ParameterBag $params)
	{
		$resource = $this->assertViewableResource($params->resource_id);
		if (!$resource->canReleaseUpdate($error))
		{
			return $this->noPermission($error);
		}

		$category = $resource->Category;

		if ($this->isPost())
		{
			$updater = $this->setupResourcePostUpdate($resource);
			$updater->checkForSpam();

			if (!$updater->validate($errors))
			{
				return $this->error($errors);
			}

			$updater->save();
			$this->finalizeResourcePostUpdate($updater);

			if ($updater->hasUpdateCreator())
			{
				return $this->redirect($this->buildLink('resources/updates', $resource));
			}
			else
			{
				return $this->redirect($this->buildLink('resources', $resource));
			}
		}
		else
		{
			/** @var \XF\Repository\Attachment $attachmentRepo */
			$attachmentRepo = $this->repository('XF:Attachment');

			$draft = $resource->draft_update;

			if ($category && $category->canUploadAndManageUpdateImages())
			{
				$updateAttachData = $attachmentRepo->getEditorData('resource_update', $resource, $draft->attachment_hash);
			}
			else
			{
				$updateAttachData = null;
			}

			$versionAttachData = $attachmentRepo->getEditorData('resource_version', $resource);

			$viewParams = [
				'resource' => $resource,
				'category' => $category,
				'versionAttachData' => $versionAttachData,
				'updateAttachData' => $updateAttachData
			];
			return $this->view('XF:ResourceItem\PostUpdate', 'xfrm_resource_post_update', $viewParams);
		}
	}

	public function actionPostUpdateDraft(ParameterBag $params)
	{
		$resource = $this->assertViewableResource($params->resource_id);
		if (!$resource->canReleaseUpdate($error))
		{
			return $this->noPermission($error);
		}

		$extraData = $this->filter([
			'update_title' => 'str',
			'attachment_hash' => 'str'
		]);

		/** @var \XF\ControllerPlugin\Draft $draftPlugin */
		$draftPlugin = $this->plugin('XF:Draft');
		return $draftPlugin->actionDraftMessage($resource->draft_update, $extraData, 'update_message');
	}

	public function actionPostUpdatePreview(ParameterBag $params)
	{
		$resource = $this->assertViewableResource($params->resource_id);
		if (!$resource->canReleaseUpdate($error))
		{
			return $this->noPermission($error);
		}

		/** @var \XFRM\Service\ResourceUpdate\Create $updateCreator */
		$updateCreator = $this->service('XFRM:ResourceUpdate\Create', $resource);

		$message = $this->plugin('XF:Editor')->fromInput('update_message');
		$updateCreator->setMessage($message);
		$updateCreator->setTitle($this->filter('update_title', 'str'));

		if (!$updateCreator->validate($errors))
		{
			return $this->error($errors);
		}

		$attachments = [];
		$tempHash = $this->filter('attachment_hash', 'str');

		if ($resource->Category->canUploadAndManageUpdateImages())
		{
			/** @var \XF\Repository\Attachment $attachmentRepo */
			$attachmentRepo = $this->repository('XF:Attachment');
			$attachmentData = $attachmentRepo->getEditorData('resource_update', $resource, $tempHash);
			$attachments = $attachmentData['attachments'];
		}

		return $this->plugin('XF:BbCodePreview')->actionPreview(
			$message, 'resource_update', $resource->User, $attachments, $resource->canViewUpdateImages()
		);
	}

	/**
	 * @param \XFRM\Entity\ResourceItem $resource
	 *
	 * @return \XFRM\Service\ResourceItem\Edit
	 */
	protected function setupResourceEdit(\XFRM\Entity\ResourceItem $resource)
	{
		/** @var \XFRM\Service\ResourceItem\Edit $editor */
		$editor = $this->service('XFRM:ResourceItem\Edit', $resource);

		$prefixId = $this->filter('prefix_id', 'uint');
		if ($prefixId != $resource->prefix_id && !$resource->Category->isPrefixUsable($prefixId))
		{
			$prefixId = 0; // not usable, just blank it out
		}
		$editor->setPrefix($prefixId);

		$editor->setTitle($this->filter('title', 'str'));

		$customFields = $this->filter('custom_fields', 'array');
		$editor->setCustomFields($customFields);

		$basicFields = $this->filter([
			'tag_line' => 'str',
			'external_url' => 'str',
			'alt_support_url' => 'str',
		]);
		$resource->bulkSet($basicFields);

		if ($resource->isExternalPurchasable())
		{
			$purchaseFields = $this->filter([
				'price' => 'num',
				'currency' => 'str',
				'external_purchase_url' => 'str',
			]);
			$editor->setExternalPurchaseData(
				$purchaseFields['price'],
				$purchaseFields['currency'],
				$purchaseFields['external_purchase_url']
			);
		}
		else if ($resource->isExternalDownload())
		{
			$editor->setExternalDownloadUrl($this->filter('external_download_url', 'str'));
		}

		$currentVersion = $resource->CurrentVersion;
		if ($currentVersion && $currentVersion->canEditVersionString())
		{
			$editor->setVersionString($this->filter('version_string', 'str'));
		}

		$descriptionEditor = $editor->getDescriptionEditor();
		$descriptionEditor->setMessage($this->plugin('XF:Editor')->fromInput('description'));

		if ($resource->Category->canUploadAndManageUpdateImages())
		{
			$descriptionEditor->setAttachmentHash($this->filter('attachment_hash', 'str'));
		}

		if ($this->filter('author_alert', 'bool') && $resource->canSendModeratorActionAlert())
		{
			$editor->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
		}

		return $editor;
	}

	public function actionEdit(ParameterBag $params)
	{
		$resource = $this->assertViewableResource($params->resource_id);
		if (!$resource->canEdit($error))
		{
			return $this->noPermission($error);
		}

		$category = $resource->Category;

		if ($this->isPost())
		{
			$editor = $this->setupResourceEdit($resource);
			$editor->checkForSpam();

			if (!$editor->validate($errors))
			{
				return $this->error($errors);
			}

			$editor->save();

			return $this->redirect($this->buildLink('resources', $resource));
		}
		else
		{
			if ($category && $category->canUploadAndManageUpdateImages())
			{
				/** @var \XF\Repository\Attachment $attachmentRepo */
				$attachmentRepo = $this->repository('XF:Attachment');
				$attachmentData = $attachmentRepo->getEditorData('resource_update', $resource->Description);
			}
			else
			{
				$attachmentData = null;
			}

			$viewParams = [
				'resource' => $resource,
				'category' => $category,
				'attachmentData' => $attachmentData,
				'prefixes' => $category->getUsablePrefixes($resource->prefix_id)
			];
			return $this->view('XF:ResourceItem\Edit', 'xfrm_resource_edit', $viewParams);
		}
	}

	public function actionPreview(ParameterBag $params)
	{
		$this->assertPostOnly();

		$resource = $this->assertViewableResource($params->resource_id);
		if (!$resource->canEdit($error))
		{
			return $this->noPermission($error);
		}

		$editor = $this->setupResourceEdit($resource);

		if (!$editor->validate($errors))
		{
			return $this->error($errors);
		}

		$description = $resource->Description;

		$attachments = [];
		$tempHash = $this->filter('attachment_hash', 'str');

		$category = $resource->Category;
		if ($category && $category->canUploadAndManageUpdateImages())
		{
			/** @var \XF\Repository\Attachment $attachmentRepo */
			$attachmentRepo = $this->repository('XF:Attachment');
			$attachmentData = $attachmentRepo->getEditorData('resource_update', $description, $tempHash);
			$attachments = $attachmentData['attachments'];
		}

		return $this->plugin('XF:BbCodePreview')->actionPreview(
			$description->message, 'resource_update', $resource->User, $attachments, $resource->canViewUpdateImages()
		);
	}

	public function actionEditIcon(ParameterBag $params)
	{
		$resource = $this->assertViewableResource($params->resource_id);
		if (!$resource->canEditIcon($error))
		{
			return $this->noPermission($error);
		}

		if ($this->isPost())
		{
			/** @var \XFRM\Service\ResourceItem\Icon $iconService */
			$iconService = $this->service('XFRM:ResourceItem\Icon', $resource);

			$action = $this->filter('icon_action', 'str');

			if ($action == 'delete')
			{
				$iconService->deleteIcon();
			}
			else if ($action == 'custom')
			{
				$upload = $this->request->getFile('upload', false, false);
				if ($upload)
				{
					if (!$iconService->setImageFromUpload($upload))
					{
						return $this->error($iconService->getError());
					}

					if (!$iconService->updateIcon())
					{
						return $this->error(\XF::phrase('xfrm_new_icon_could_not_be_applied_try_later'));
					}
				}
			}

			return $this->redirect($this->buildLink('resources', $resource));
		}
		else
		{
			$viewParams = [
				'resource' => $resource,
				'category' => $resource->Category
			];
			return $this->view('XF:ResourceItem\EditIcon', 'xfrm_resource_edit_icon', $viewParams);
		}
	}

	/**
	 * @param \XFRM\Entity\ResourceItem $resource
	 *
	 * @return \XFRM\Service\ResourceItem\ChangeType
	 */
	protected function setupResourceChangeType(\XFRM\Entity\ResourceItem $resource)
	{
		/** @var \XFRM\Service\ResourceItem\ChangeType $typeChanger */
		$typeChanger = $this->service('XFRM:ResourceItem\ChangeType', $resource);

		$versionString = $this->filter('version_string', 'str');
		$typeChanger->setVersionString($versionString, true);

		$category = $resource->Category;

		switch ($this->filter('resource_type', 'str'))
		{
			case 'download_local':
				if ($category->allow_local)
				{
					$typeChanger->setLocalDownload(
						$this->filter('version_attachment_hash', 'str')
					);
				}
				break;

			case 'download_external':
				if ($category->allow_external)
				{
					$typeChanger->setExternalDownload($this->filter('external_download_url', 'str'));
				}
				break;

			case 'external_purchase':
				if ($category->allow_commercial_external)
				{
					$purchaseInput = $this->filter([
						'price' => 'str',
						'currency' => 'str',
						'external_purchase_url' => 'str'
					]);

					$typeChanger->setExternalPurchasable(
						$purchaseInput['price'], $purchaseInput['currency'], $purchaseInput['external_purchase_url']
					);
				}
				break;

			case 'fileless':
				if ($category->allow_fileless)
				{
					$typeChanger->setFileless();
				}
				break;
		}

		return $typeChanger;
	}

	public function actionChangeType(ParameterBag $params)
	{
		$resource = $this->assertViewableResource($params->resource_id);
		if (!$resource->canReleaseUpdate($error))
		{
			return $this->noPermission($error);
		}

		if ($this->isPost())
		{
			$typeChanger = $this->setupResourceChangeType($resource);

			if (!$typeChanger->validate($errors))
			{
				return $this->error($errors);
			}

			$typeChanger->save();

			return $this->redirect($this->buildLink('resources', $resource));
		}
		else
		{
			/** @var \XF\Repository\Attachment $attachmentRepo */
			$attachmentRepo = $this->repository('XF:Attachment');
			$versionAttachData = $attachmentRepo->getEditorData('resource_version', $resource);

			$viewParams = [
				'resource' => $resource,
				'category' => $resource->Category,
				'versionAttachData' => $versionAttachData
			];
			return $this->view('XF:ResourceItem\ChangeType', 'xfrm_resource_change_type', $viewParams);
		}
	}

	/**
	 * @param \XFRM\Entity\ResourceItem $resource
	 * @param \XFRM\Entity\Category $category
	 *
	 * @return \XFRM\Service\ResourceItem\Move
	 */
	protected function setupResourceMove(\XFRM\Entity\ResourceItem $resource, \XFRM\Entity\Category $category)
	{
		$options = $this->filter([
			'notify_watchers' => 'bool',
			'author_alert' => 'bool',
			'author_alert_reason' => 'str',
			'prefix_id' => 'uint'
		]);

		/** @var \XFRM\Service\ResourceItem\Move $mover */
		$mover = $this->service('XFRM:ResourceItem\Move', $resource);

		if ($options['author_alert'])
		{
			$mover->setSendAlert(true, $options['author_alert_reason']);
		}

		if ($options['notify_watchers'])
		{
			$mover->setNotifyWatchers();
		}

		if ($options['prefix_id'] !== null)
		{
			$mover->setPrefix($options['prefix_id']);
		}

		$mover->addExtraSetup(function($resource, $category)
		{
			$resource->title = $this->filter('title', 'str');
		});

		return $mover;
	}

	public function actionMove(ParameterBag $params)
	{
		$resource = $this->assertViewableResource($params->resource_id);
		if (!$resource->canMove($error))
		{
			return $this->noPermission($error);
		}

		$category = $resource->Category;

		if ($this->isPost())
		{
			$targetCategoryId = $this->filter('target_category_id', 'uint');

			/** @var \XFRM\Entity\Category $targetCategory */
			$targetCategory = $this->app()->em()->find('XFRM:Category', $targetCategoryId);
			if (!$targetCategory || !$targetCategory->canView())
			{
				return $this->error(\XF::phrase('requested_category_not_found'));
			}

			$this->setupResourceMove($resource, $targetCategory)->move($targetCategory);

			return $this->redirect($this->buildLink('resources', $resource));
		}
		else
		{
			$categoryRepo = $this->getCategoryRepo();
			$categories = $categoryRepo->getViewableCategories();

			$viewParams = [
				'resource' => $resource,
				'category' => $category,
				'prefixes' => $category->getUsablePrefixes(),
				'categoryTree' => $categoryRepo->createCategoryTree($categories)
			];
			return $this->view('XFRM:ResourceItem\Move', 'xfrm_resource_move', $viewParams);
		}
	}

	public function actionTags(ParameterBag $params)
	{
		$resource = $this->assertViewableResource($params->resource_id);
		if (!$resource->canEditTags($error))
		{
			return $this->noPermission($error);
		}

		/** @var \XF\Service\Tag\Changer $tagger */
		$tagger = $this->service('XF:Tag\Changer', 'resource', $resource);

		if ($this->isPost())
		{
			$tagger->setEditableTags($this->filter('tags', 'str'));
			if ($tagger->hasErrors())
			{
				return $this->error($tagger->getErrors());
			}

			$tagger->save();

			return $this->redirect($this->buildLink('resources', $resource));
		}
		else
		{
			$grouped = $tagger->getExistingTagsByEditability();

			$viewParams = [
				'resource' => $resource,
				'category' => $resource->Category,
				'editableTags' => $grouped['editable'],
				'uneditableTags' => $grouped['uneditable']
			];
			return $this->view('XFRM:ResourceItem\Tags', 'xfrm_resource_tags', $viewParams);
		}
	}

	public function actionWatch(ParameterBag $params)
	{
		$resource = $this->assertViewableResource($params->resource_id);
		if (!$resource->canWatch($error))
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
				$config = [
					'email_subscribe' => $this->filter('email_subscribe', 'bool')
				];
			}

			/** @var \XFRM\Repository\ResourceWatch $watchRepo */
			$watchRepo = $this->repository('XFRM:ResourceWatch');
			$watchRepo->setWatchState($resource, $visitor, $action, $config);

			$redirect = $this->redirect($this->buildLink('resources', $resource));
			$redirect->setJsonParam('switchKey', $action == 'delete' ? 'watch' : 'unwatch');
			return $redirect;
		}
		else
		{
			$viewParams = [
				'resource' => $resource,
				'isWatched' => !empty($resource->Watch[$visitor->user_id]),
				'category' => $resource->Category
			];
			return $this->view('XFRM:ResourceItem\Watch', 'xfrm_resource_watch', $viewParams);
		}
	}

	public function actionReassign(ParameterBag $params)
	{
		$resource = $this->assertViewableResource($params->resource_id);
		if (!$resource->canReassign($error))
		{
			return $this->noPermission($error);
		}

		if ($this->isPost())
		{
			$user = $this->em()->findOne('XF:User', ['username' => $this->filter('username', 'str')]);
			if (!$user)
			{
				return $this->error(\XF::phrase('requested_user_not_found'));
			}

			$canTargetView = \XF::asVisitor($user, function() use ($resource)
			{
				return $resource->canView();
			});
			if (!$canTargetView)
			{
				return $this->error(\XF::phrase('xfrm_new_owner_must_be_able_to_view_this_resource'));
			}

			/** @var \XFRM\Service\ResourceItem\Reassign $reassigner */
			$reassigner = $this->service('XFRM:ResourceItem\Reassign', $resource);

			if ($this->filter('alert', 'bool'))
			{
				$reassigner->setSendAlert(true, $this->filter('alert_reason', 'str'));
			}

			$reassigner->reassignTo($user);

			return $this->redirect($this->buildLink('resources', $resource));
		}
		else
		{
			$viewParams = [
				'resource' => $resource,
				'category' => $resource->Category
			];
			return $this->view('XF:ResourceItem\Reassign', 'xfrm_resource_reassign', $viewParams);
		}
	}

	public function actionQuickFeature(ParameterBag $params)
	{
		$this->assertPostOnly();

		$resource = $this->assertViewableResource($params->resource_id);
		if (!$resource->canFeatureUnfeature($error))
		{
			return $this->error($error);
		}

		/** @var \XFRM\Service\ResourceItem\Feature $featurer */
		$featurer = $this->service('XFRM:ResourceItem\Feature', $resource);

		if ($resource->Featured)
		{
			$featurer->unfeature();
			$featured = false;
			$text = \XF::phrase('xfrm_resource_quick_feature');
		}
		else
		{
			$featurer->feature();
			$featured = true;
			$text = \XF::phrase('xfrm_resource_quick_unfeature');
		}

		$reply = $this->redirect($this->getDynamicRedirect());
		$reply->setJsonParams([
			'text' => $text,
			'featured' => $featured
		]);
		return $reply;
	}

	public function actionDelete(ParameterBag $params)
	{
		$resource = $this->assertViewableResource($params->resource_id);
		if (!$resource->canDelete('soft', $error))
		{
			return $this->noPermission($error);
		}

		if ($this->isPost())
		{
			$type = $this->filter('hard_delete', 'bool') ? 'hard' : 'soft';
			$reason = $this->filter('reason', 'str');

			if (!$resource->canDelete($type, $error))
			{
				return $this->noPermission($error);
			}

			/** @var \XFRM\Service\ResourceItem\Delete $deleter */
			$deleter = $this->service('XFRM:ResourceItem\Delete', $resource);

			if ($this->filter('author_alert', 'bool'))
			{
				$deleter->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
			}

			$deleter->delete($type, $reason);

			$this->plugin('XF:InlineMod')->clearIdFromCookie('resource', $resource->resource_id);

			return $this->redirect($this->buildLink('resources/categories', $resource->Category));
		}
		else
		{
			$viewParams = [
				'resource' => $resource,
				'category' => $resource->Category
			];
			return $this->view('XF:ResourceItem\Delete', 'xfrm_resource_delete', $viewParams);
		}
	}

	public function actionUndelete(ParameterBag $params)
	{
		$resource = $this->assertViewableResource($params->resource_id);
		if (!$resource->canUndelete($error))
		{
			return $this->noPermission($error);
		}

		if ($this->isPost())
		{
			if ($resource->resource_state == 'deleted')
			{
				$resource->resource_state = 'visible';
				$resource->save();
			}

			return $this->redirect($this->buildLink('resources', $resource));
		}
		else
		{
			$viewParams = [
				'resource' => $resource,
				'category' => $resource->Category
			];
			return $this->view('XF:ResourceItem\Undelete', 'xfrm_resource_undelete', $viewParams);
		}
	}

	public function actionApprove(ParameterBag $params)
	{
		$resource = $this->assertViewableResource($params->resource_id);
		if (!$resource->canApproveUnapprove($error))
		{
			return $this->noPermission($error);
		}

		if ($this->isPost())
		{
			/** @var \XFRM\Service\ResourceItem\Approve $approver */
			$approver = \XF::service('XFRM:ResourceItem\Approve', $resource);
			$approver->setNotifyRunTime(1); // may be a lot happening
			$approver->approve();

			return $this->redirect($this->buildLink('resources', $resource));
		}
		else
		{
			$viewParams = [
				'resource' => $resource,
				'category' => $resource->Category
			];
			return $this->view('XF:ResourceItem\Approve', 'xfrm_resource_approve', $viewParams);
		}
	}

	public function actionUpdate(ParameterBag $params)
	{
		$resource = $this->assertViewableResource($params->resource_id);

		$updateId = $this->filter('update', 'uint');
		/** @var \XFRM\Entity\ResourceUpdate|null $update */
		$update = $this->em()->find('XFRM:ResourceUpdate', $updateId);
		if (!$update || $update->resource_id != $resource->resource_id)
		{
			return $this->noPermission();
		}
		if (!$update->canView($error))
		{
			return $this->noPermission($error);
		}

		return $this->redirectPermanently($this->buildLink('resources/update', $update));
	}

	public function actionPrefixes(ParameterBag $params)
	{
		$this->assertPostOnly();

		$categoryId = $this->filter('val', 'uint');

		/** @var \XFRM\Entity\Category $category */
		$category = $this->em()->find('XFRM:Category', $categoryId,
			'Permissions|' . \XF::visitor()->permission_combination_id
		);
		if (!$category)
		{
			return $this->notFound(\XF::phrase('requested_category_not_found'));
		}

		if (!$category->canView($error))
		{
			return $this->noPermission($error);
		}

		$viewParams = [
			'category' => $category,
			'prefixes' => $category->getUsablePrefixes()
		];
		return $this->view('XFRM:Category\Prefixes', 'xfrm_category_prefixes', $viewParams);
	}

	/**
	 * @param $resourceId
	 * @param array $extraWith
	 *
	 * @return \XFRM\Entity\ResourceItem
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertViewableResource($resourceId, array $extraWith = [])
	{
		$visitor = \XF::visitor();

		$extraWith[] = 'User';
		$extraWith[] = 'Category';
		$extraWith[] = 'Category.Permissions|' . $visitor->permission_combination_id;
		$extraWith[] = 'Description';
		$extraWith[] = 'CurrentVersion';
		$extraWith[] = 'Discussion';
		$extraWith[] = 'Discussion.Forum';
		$extraWith[] = 'Discussion.Forum.Node';
		$extraWith[] = 'Discussion.Forum.Node.Permissions|' . $visitor->permission_combination_id;

		/** @var \XFRM\Entity\ResourceItem $resource */
		$resource = $this->em()->find('XFRM:ResourceItem', $resourceId, $extraWith);
		if (!$resource)
		{
			throw $this->exception($this->notFound(\XF::phrase('xfrm_requested_resource_not_found')));
		}

		if (!$resource->canView($error))
		{
			throw $this->exception($this->noPermission($error));
		}

		return $resource;
	}

	/**
	 * @return \XFRM\Repository\ResourceItem
	 */
	protected function getResourceRepo()
	{
		return $this->repository('XFRM:ResourceItem');
	}

	/**
	 * @return \XFRM\Repository\Category
	 */
	protected function getCategoryRepo()
	{
		return $this->repository('XFRM:Category');
	}

	public static function getActivityDetails(array $activities)
	{
		return self::getActivityDetailsForContent(
			$activities, \XF::phrase('xfrm_viewing_resource'), 'resource_id',
			function(array $ids)
			{
				$resources = \XF::em()->findByIds(
					'XFRM:ResourceItem',
					$ids,
					['Category', 'Category.Permissions|' . \XF::visitor()->permission_combination_id]
				);

				$router = \XF::app()->router('public');
				$data = [];

				foreach ($resources->filterViewable() AS $id => $resource)
				{
					$data[$id] = [
						'title' => $resource->title,
						'url' => $router->buildLink('resources', $resource)
					];
				}

				return $data;
			},
			\XF::phrase('xfrm_viewing_resources')
		);
	}
}