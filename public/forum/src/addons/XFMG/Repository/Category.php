<?php

namespace XFMG\Repository;

use XF\Repository\AbstractCategoryTree;

class Category extends AbstractCategoryTree
{
	protected function getClassName()
	{
		return 'XFMG:Category';
	}

	public function mergeCategoryListExtras(array $extras, array $childExtras)
	{
		$output = array_merge([
			'media_count' => 0,
			'album_count' => 0,
			'comment_count' => 0,
			'childCount' => 0
		], $extras);

		foreach ($childExtras AS $child)
		{
			if (!empty($child['media_count']))
			{
				$output['media_count'] += $child['media_count'];
			}

			if (!empty($child['album_count']))
			{
				$output['album_count'] += $child['album_count'];
			}

			if (!empty($child['comment_count']))
			{
				$output['comment_count'] += $child['comment_count'];
			}

			$output['childCount'] += 1 + (!empty($child['childCount']) ? $child['childCount'] : 0);
		}

		return $output;
	}

	public function getCategoryTypes()
	{
		return [
			'container' => \XF::phrase('xfmg_cat_type.container'),
			'album' => \XF::phrase('xfmg_cat_type.album'),
			'media' => \XF::phrase('xfmg_cat_type.media'),
		];
	}
}