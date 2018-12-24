<?php

/**
 * Helper for choosing what happens by default to spam threads.
 *
 * @package XenForo_Options
 */
abstract class Brivium_ModernStatistic_Option_Render
{
	public static function renderUserGroups(XenForo_View $view, $fieldPrefix, array $preparedOption, $canEdit)
	{
		$userGroups = XenForo_Model::create('XenForo_Model_UserGroup')->getAllUserGroups();
		foreach ($userGroups AS $userGroupId => $userGroup)
		{
			$formatParams[$userGroupId] = array(
				'label' => $userGroup['title'],
				'value' => $userGroup['user_group_id'],
				'selected' => in_array($userGroup['user_group_id'], $preparedOption['option_value'])
			);
		}
		$preparedOption['formatParams'] = $formatParams;

		return XenForo_ViewAdmin_Helper_Option::renderOptionTemplateInternal('option_list_option_checkbox', $view, $fieldPrefix, $preparedOption, $canEdit);
	}

	public static function renderOption(XenForo_View $view, $fieldPrefix, array $preparedOption, $canEdit)
	{
		$choices = $preparedOption['option_value'];
		$editLink = $view->createTemplateObject('option_list_option_editlink', array(
			'preparedOption' => $preparedOption,
			'canEditOptionDefinition' => $canEdit
		));
		$modernModel = XenForo_Model::create('Brivium_ModernStatistic_Model_ModernStatistic');
		$forumOptions = XenForo_Option_NodeChooser::getNodeOptions(
			-1,false,
			'Forum'
		);
		$listKinds = array(
			array(
				'value' => 'thread',
				'label' => new XenForo_Phrase('thread'),
			),
		);
		$resourceVersion = $modernModel->checkXenForoResourceAddon();
		$categoryOptions = array();
		if($resourceVersion){
			$listKinds[] = array(
				'value' => 'resource',
				'label' => new XenForo_Phrase('resource'),
			);
			$categoryModel = XenForo_Model::create('XenResource_Model_Category');
			$categories = $categoryModel->prepareCategories($categoryModel->getViewableCategories());

			foreach ($categories AS $categoryId => $category)
			{
				$category['depth'] += 1;

				$categoryOptions[$categoryId] = array(
					'value' => $categoryId,
					'label' => $category['category_title'],
					'depth' => $category['depth']
				);
			}
		}
		$listTypes = array(
			array(
				'value' => 'thread_latest',
				'label' => new XenForo_Phrase('BRMS_latest_threads'),
			),
			array(
				'value' => 'thread_hotest',
				'label' => new XenForo_Phrase('BRMS_most_viewed_threads'),
			),
			array(
				'value' => 'post_latest',
				'label' => new XenForo_Phrase('BRMS_latest_replies'),
			),
			array(
				'value' => 'most_reply',
				'label' => new XenForo_Phrase('BRMS_most_replied_threads'),
			),
			array(
				'value' => 'sticky_threads',
				'label' => new XenForo_Phrase('BRMS_sticky_threads'),
			),
			array(
				'value' => 'my_threads',
				'label' => new XenForo_Phrase('BRMS_my_threads'),
			),
		);
		$listTypeResources = array(
			array(
				'value' => 'resource_last_update',
				'label' => new XenForo_Phrase('latest_updates'),
			),
			array(
				'value' => 'resource_resource_date',
				'label' => new XenForo_Phrase('newest_resources'),
			),
			array(
				'value' => 'resource_rating_weighted',
				'label' => new XenForo_Phrase('top_resources'),
			),
			array(
				'value' => 'resource_download_count',
				'label' => new XenForo_Phrase('most_downloaded'),
			),
		);
		$listThreadOrders = array(
			array(
				'value' => 'title',
				'label' => new XenForo_Phrase('title_alphabetical'),
			),
			array(
				'value' => 'post_date',
				'label' => new XenForo_Phrase('thread_creation_time'),
			),
			array(
				'value' => 'view_count',
				'label' => new XenForo_Phrase('number_of_views'),
			),
			array(
				'value' => 'reply_count',
				'label' => new XenForo_Phrase('number_of_replies'),
			),
			array(
				'value' => 'first_post_likes',
				'label' => new XenForo_Phrase('first_message_likes'),
			),
		);
		return $view->createTemplateObject('BRMS_option_template_tab_selector', array(
			'fieldPrefix'	 	=> $fieldPrefix,
			'listedFieldName' 	=> $fieldPrefix . '_listed[]',
			'preparedOption' 	=> $preparedOption,
			'formatParams' 		=> $preparedOption['formatParams'],
			'editLink' 			=> $editLink,


			'resourceVersion' 	=> $resourceVersion,
			'categoryList' 		=> $categoryOptions,
			'forumList' 		=> $forumOptions,
			'choices' 			=> $choices,
			'listTypes' 		=> $listTypes,
			'listTypeResources' => $listTypeResources,

			'listThreadOrders' 	=> $listThreadOrders,

			'listKinds' 		=> $listKinds,
			'nextCounter' 		=> count($choices)
		));
	}

	public static function verifyOption(array &$tabs, XenForo_DataWriter $dw, $fieldName)
	{
		$output = array();
		foreach ($tabs as $tab) {
			if (!empty($tab['kind'])) {
				switch($tab['kind']){
					case 'resource':
						if (!empty($tab['type_resource']) && !empty($tab['kind'])) {
							$tab['type']= $tab['type_resource'];
							$output[] = $tab;
						}
						break;
					case 'thread':
						if (!empty($tab['type']) && !empty($tab['kind'])) {
							$output[] = $tab;
						}
						break;
					default:
						if (!empty($tab['type']) && !empty($tab['kind'])) {
							$output[] = $tab;
						}
						break;
				}
			}
		}
		$tabs = $output;
		return true;
	}
	public static function renderOptionLimit(XenForo_View $view, $fieldPrefix, array $preparedOption, $canEdit)
	{
		$choices = $preparedOption['option_value'];
		$editLink = $view->createTemplateObject('option_list_option_editlink', array(
			'preparedOption' => $preparedOption,
			'canEditOptionDefinition' => $canEdit
		));
		return $view->createTemplateObject('BRMS_option_template_item_limit', array(
			'fieldPrefix' => $fieldPrefix,
			'listedFieldName' => $fieldPrefix . '_listed[]',
			'preparedOption' => $preparedOption,
			'formatParams' => $preparedOption['formatParams'],
			'editLink' => $editLink,

			'choices' => $choices,
			'nextCounter' => count($choices)
		));
	}

	public static function verifyOptionLimit(array &$numbers, XenForo_DataWriter $dw, $fieldName)
	{
		$output = array();
		if(!empty($numbers['value'])){
			foreach ($numbers['value'] as $number) {
				if (!empty($number) && $number > 0) {
					$output[] = intval($number);
				}
			}
			asort($output);
			$numbers['value'] = array_values(array_unique($output));
		}
		if(!empty($numbers['default'])){
			$numbers['default'] = intval($numbers['default']);
		}else{
			$numbers['default'] = 15;
		}
		if(!empty($numbers['enabled']) && empty($numbers['value']))
			$dw->error(new XenForo_Phrase('BRMS_must_have_value_for_item_limit'), $fieldName);
		return true;
	}
}