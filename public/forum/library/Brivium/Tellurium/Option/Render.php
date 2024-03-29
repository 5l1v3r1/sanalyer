<?php

/**
 * Helper for choosing what happens by default to spam threads.
 *
 * @package XenForo_Options
 */
abstract class Brivium_Tellurium_Option_Render
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
		$value = $preparedOption['option_value'];
		$seleted = 0;
		if($value)$seleted = -1;
		$editLink = $view->createTemplateObject('option_list_option_editlink', array(
			'preparedOption' => $preparedOption,
			'canEditOptionDefinition' => $canEdit
		));

		$forumOptions = XenForo_Option_NodeChooser::getNodeOptions(
			$seleted,
			sprintf('(%s)', new XenForo_Phrase('unspecified')),
			'Forum'
		);
		return $view->createTemplateObject('BRQCT_option_template_forumSelector', array(
			'fieldPrefix' => $fieldPrefix,
			'listedFieldName' => $fieldPrefix . '_listed[]',
			'preparedOption' => $preparedOption,
			'formatParams' => $forumOptions,
			'editLink' => $editLink
		));
	}

	public static function renderStyles(XenForo_View $view, $fieldPrefix, array $preparedOption, $canEdit)
	{
		$styleModel = XenForo_Model::create('XenForo_Model_Style');
		$styles = $styleModel->getAllStylesAsFlattenedTree();

		foreach ($styles AS $id => $style)
		{
			if (!$style['user_selectable'])
			{
				unset($styles[$id]);
			}
		}

		foreach ($styles AS $styleId => $style)
		{
			$formatParams[$styleId] = array(
				'label' => $style['title'],
				'value' => $style['style_id'],
				'selected' => in_array($style['style_id'], $preparedOption['option_value'])
			);
		}
		$preparedOption['formatParams'] = $formatParams;

		return XenForo_ViewAdmin_Helper_Option::renderOptionTemplateInternal('option_list_option_checkbox', $view, $fieldPrefix, $preparedOption, $canEdit);
	}
}