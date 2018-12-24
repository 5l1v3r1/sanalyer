<?php

namespace XFMG;

class Listener
{
	public static function userContentChangeInit(\XF\Service\User\ContentChange $changeService, array &$updates)
	{
		$updates['xf_mg_album'] = [
			['user_id', 'username'],
			['last_comment_user_id', 'last_comment_username']
		];
		$updates['xf_mg_album_comment_read'] = ['user_id', 'emptyable' => false];
		$updates['xf_mg_album_watch'] = ['user_id', 'emptyable' => false];
		$updates['xf_mg_category_watch'] = ['user_id', 'emptyable' => false];
		$updates['xf_mg_comment'] = ['user_id', 'username'];
		$updates['xf_mg_media_comment_read'] = ['user_id', 'emptyable' => false];
		$updates['xf_mg_media_item'] = [
			['user_id', 'username'],
			['last_comment_user_id', 'last_comment_username']
		];
		$updates['xf_mg_media_note'] = [
			['user_id', 'username'],
			['tagged_user_id', 'tagged_username']
		];
		$updates['xf_mg_media_temp'] = ['user_id', 'emptyable' => false];
		$updates['xf_mg_media_watch'] = ['user_id', 'emptyable' => false];
		$updates['xf_mg_rating'] = ['user_id', 'username'];
		$updates['xf_mg_shared_map_add'] = ['user_id', 'emptyable' => false];
		$updates['xf_mg_shared_map_view'] = ['user_id', 'emptyable' => false];
	}

	public static function userDeleteCleanInit(\XF\Service\User\DeleteCleanUp $deleteService, array &$deletes)
	{
		$deletes['xf_mg_album_comment_read'] = 'user_id = ?';
		$deletes['xf_mg_album_watch'] = 'user_id = ?';
		$deletes['xf_mg_category_watch'] = 'user_id = ?';
		$deletes['xf_mg_media_comment_read'] = 'user_id = ?';
		$deletes['xf_mg_media_temp'] = 'user_id = ?';
		$deletes['xf_mg_media_user_view'] = 'user_id = ?';
		$deletes['xf_mg_media_watch'] = 'user_id = ?';
		$deletes['xf_mg_shared_map_add'] = 'user_id = ?';
		$deletes['xf_mg_shared_map_view'] = 'user_id = ?';
	}

	public static function userMergeCombine(
		\XF\Entity\User $target, \XF\Entity\User $source, \XF\Service\User\Merge $mergeService
	)
	{
		$target->xfmg_media_quota += $source->xfmg_media_quota;
		$target->xfmg_media_count += $source->xfmg_media_count;
		$target->xfmg_album_count += $source->xfmg_album_count;
	}

	public static function userSearcherOrders(\XF\Searcher\User $userSearcher, array &$sortOrders)
	{
		$sortOrders = array_replace($sortOrders, [
			'xfmg_media_count' => \XF::phrase('xfmg_media_count'),
			'xfmg_album_count' => \XF::phrase('xfmg_album_count')
		]);
	}

	public static function templaterSetup(\XF\Container $container, \XF\Template\Templater &$templater)
	{
		$templater->addFunction('xfmg_allowed_media', function(\XF\Template\Templater $templater, &$escape, $type)
		{
			$options = \XF::options();

			switch ($type)
			{
				case 'image':
				case 'video':
				case 'audio':
					$option = 'xfmg' . ucfirst($type) . 'Extensions';
					return preg_split('/\s+/', trim($options->{$option}), -1, PREG_SPLIT_NO_EMPTY);
				case 'embed':
					return \XF::registry()->get('bbCodeMedia');
			}

			throw new \InvalidArgumentException('Unknown media type.');
		});

		$templater->addFunction('xfmg_watermark', function(\XF\Template\Templater $templater, &$escape, $type)
		{
			$options = \XF::options();
			$watermarkHash = $options->xfmgWatermarking['watermark_hash'];

			if (!$watermarkHash)
			{
				return null;
			}

			if ($type == 'url')
			{
				$path = sprintf("xfmg/watermark/%s.jpg",
					$watermarkHash
				);
				return \XF::app()->applyExternalDataUrl($path);
			}
			else
			{
				return \XF::repository('XFMG:Media')->getAbstractedWatermarkPath($watermarkHash);
			}
		});

		$templater->addFunction('xfmg_thumbnail', function(\XF\Template\Templater $templater, &$escape, \XF\Mvc\Entity\Entity $entity, $additionalClasses = '', $inline = false, $forceType = null)
		{
			if (!($entity instanceof \XFMG\Entity\Album) && !($entity instanceof \XFMG\Entity\MediaItem))
			{
				trigger_error('Thumbnail content must be an Album or MediaItem entity.', E_USER_WARNING);
				return '';
			}

			$escape = false;

			if ($entity instanceof \XFMG\Entity\MediaItem)
			{
				$type = $entity->media_type;
			}
			else
			{
				$type = 'album';
			}

			$class = 'xfmgThumbnail xfmgThumbnail--' . $type;
			if ($additionalClasses)
			{
				$class .= " $additionalClasses";
			}

			if (!$entity->isVisible())
			{
				$class .= ' xfmgThumbnail--notVisible xfmgThumbnail--notVisible--';
				if ($entity->content_type == 'xfmg_media')
				{
					$class .= $entity->media_state;
				}
				else
				{
					$class .= $entity->album_state;
				}
			}

			$thumbnailUrl = null;
			if ($entity->thumbnail_date)
			{
				$thumbnailUrl = $entity->getThumbnailUrl();
			}

			$customThumbnailUrl = null;
			if ($entity->custom_thumbnail_date)
			{
				$customThumbnailUrl = $entity->getCustomThumbnailUrl();
			}

			$outputUrl = null;
			$hasThumbnail = false;
			if ($customThumbnailUrl && $forceType != 'default')
			{
				$outputUrl = $customThumbnailUrl;
				$hasThumbnail = ($entity->custom_thumbnail_date > 0);
			}
			else if ($thumbnailUrl && $forceType != 'custom')
			{
				$outputUrl = $thumbnailUrl;
				$hasThumbnail = ($entity->thumbnail_date> 0);
			}
			if (!$hasThumbnail)
			{
				$class .= ' xfmgThumbnail--noThumb';
				$outputUrl = $templater->fn('transparent_img');
			}

			$title = $templater->filterForAttr($templater, $entity->title, $null);

			if ($inline)
			{
				$tag = 'span';
			}
			else
			{
				$tag = 'div';
			}

			return "<$tag class='{$class}'>
				<img class='xfmgThumbnail-image' src='{$outputUrl}' alt='{$title}' />
				<span class='xfmgThumbnail-icon'></span>
			</$tag>";
		});
	}

	public static function navigationSetup(\XF\Pub\App $app, array &$navigationFlat, array &$navigationTree)
	{
		if (isset($navigationFlat['xfmg']) && self::visitor()->canViewMedia() && \XF::options()->xfmgUnviewedCounter)
		{
			$session = $app->session();

			$mediaUnviewed = $session->get('xfmgUnviewedMedia');
			if ($mediaUnviewed)
			{
				$navigationFlat['xfmg']['counter'] = count($mediaUnviewed['unviewed']);
			}
		}
	}

	public static function appSetup(\XF\App $app)
	{
		$container = $app->container();

		$container['customFields.xfmgMediaFields'] = $app->fromRegistry('xfmgMediaFields',
			function(\XF\Container $c) { return $c['em']->getRepository('XFMG:MediaField')->rebuildFieldCache(); },
			function(array $mediaFieldsInfo)
			{
				$definitionSet = new \XF\CustomField\DefinitionSet($mediaFieldsInfo);
				$definitionSet->addFilter('display_add_media', function(array $field)
				{
					return (bool)$field['display_add_media'];
				});
				return $definitionSet;
			}
		);
	}

	public static function appPubStartEnd(\XF\Pub\App $app)
	{
		$visitor = self::visitor();

		if ($visitor->user_id && $visitor->canViewMedia() && \XF::options()->xfmgUnviewedCounter)
		{
			$session = $app->session();

			$mediaUnviewed = array_replace([
				'unviewed' => [],
				'lastUpdateDate' => 0
			], $session->get('xfmgUnviewedMedia') ?: []);

			if ($mediaUnviewed['lastUpdateDate']  < (\XF::$time - 5 * 60)) // 5 minutes
			{
				$categoryRepo = \XF::repository('XFMG:Category');
				$categoryList = $categoryRepo->getViewableCategories();
				$categoryIds = $categoryList->keys();

				$mediaRepo = \XF::repository('XFMG:Media');
				$mediaItems = $mediaRepo->findMediaForIndex($categoryIds)
					->unviewedOnly($visitor->user_id)
					->orderByDate()
					->fetch();

				if ($mediaItems->count())
				{
					$mediaUnviewed['unviewed'] = array_fill_keys($mediaItems->keys(), true);
				}
			}

			$mediaUnviewed['lastUpdateDate'] = \XF::$time;
			$session->set('xfmgUnviewedMedia', $mediaUnviewed);
		}
	}

	public static function criteriaUser($rule, array $data, \XF\Entity\User $user, &$returnValue)
	{
		switch ($rule)
		{
			case 'xfmg_media_count':
				if (isset($user->xfmg_media_count) && $user->xfmg_media_count >= $data['media_items'])
				{
					$returnValue = true;
				}
				break;

			case 'xfmg_album_count':
				if (isset($user->xfmg_album_count) && $user->xfmg_album_count >= $data['albums'])
				{
					$returnValue = true;
				}
				break;
		}
	}

	public static function criteriaPage($rule, array $data, \XF\Entity\User $user, array $params, &$returnValue)
	{
		if (isset($params['breadcrumbs']) && is_array($params['breadcrumbs']) && !empty($data['category_ids']))
		{
			$returnValue = false;

			if (empty($data['category_only']))
			{
				foreach ($params['breadcrumbs'] AS $i => $navItem)
				{
					if (isset($navItem['attributes']['category_id']) && in_array($navItem['attributes']['category_id'], $data['category_ids']))
					{
						$returnValue = true;
					}
				}
			}

			if ($params['containerKey'])
			{
				list ($type, $id) = explode('-', $params['containerKey'], 2);

				if ($type == 'xfmgCategory' && $id && in_array($id, $data['category_ids']))
				{
					$returnValue = true;
				}
			}
		}
	}

	public static function criteriaTemplateData(array &$templateData)
	{
		$categoryRepo = \XF::repository('XFMG:Category');
		$templateData['xfmgCategories'] = $categoryRepo->getCategoryOptionsData(false);
	}

	public static function templaterTemplatePreRenderPublicEditor(\XF\Template\Templater $templater, &$type, &$template, array &$params)
	{
		if (isset($params['customIcons']['gallery']))
		{
			if (!self::visitor()->canViewMedia())
			{
				unset($params['customIcons']['gallery']);
			}
		}
	}

	public static function editorDialog(array &$data, \XF\Pub\Controller\AbstractController $controller)
	{
		$controller->assertRegistrationRequired();

		$data['template'] = 'xfmg_editor_dialog_gallery';
	}

	/**
	 * @return \XFMG\XF\Entity\User
	 */
	public static function visitor()
	{
		/** @var \XFMG\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		return $visitor;
	}
}