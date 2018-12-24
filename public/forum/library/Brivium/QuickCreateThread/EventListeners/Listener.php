<?php
class Brivium_QuickCreateThread_EventListeners_Listener extends Brivium_BriviumHelper_EventListeners
{
	public static function widgetFrameworkReady(&$renderers)
	{
		if(!in_array('Brivium_QuickCreateThread_WidgetRenderer_QuickCreateThread',$renderers)){
			$renderers[] = 'Brivium_QuickCreateThread_WidgetRenderer_QuickCreateThread';
		}
	}

	public static function templateCreate($templateName, array &$params, XenForo_Template_Abstract $template)
	{
		if ($template instanceof XenForo_Template_Admin)
		{
		}else{
			$template->preloadTemplate('BRQCT_forum_list_nodes');
			$template->preloadTemplate('BRQCT_ad_sidebar_top');
			$template->preloadTemplate('BRQCT_navigation_tabs_forums');
			$addOns = XenForo_Application::get('addOns');

			if (!empty($addOns['Brivium_Tellurium']))
			{
				$params['BRQCT_enable_addons'] = true;
			}
		}
	}

	protected static  $_show = null;
	public static function templateHook($hookName, &$contents, array $hookParams, XenForo_Template_Abstract $template)
    {
		if(is_null(self::$_show)){
			$visitor = XenForo_Visitor::getInstance();
			self::$_show = true;
			$excludeGroups = XenForo_Application::get('options')->BRQCT_excludeUserGroup;
			if($excludeGroups)
			{
				$belongstogroups = $visitor['user_group_id'];
				if (!empty($visitor['secondary_group_ids']))
				{
					$belongstogroups .= ','.$visitor['secondary_group_ids'];
				}
				$groupcheck = explode(',',$belongstogroups);
				unset($belongstogroups);
				foreach ($groupcheck AS $groupId)
				{
					if (in_array($groupId, $excludeGroups))
					{
						self::$_show = false;
						break;
					}
				}
			}

			$styleIds = XenForo_Application::get('options')->BRQCT_displayStyle;
			$defaultStyleId = XenForo_Application::get('options')->defaultStyleId;

			if (!empty($visitor['style_id']) && !in_array($visitor['style_id'], $styleIds))
			{
				self::$_show = false;
			}

			if (!in_array($defaultStyleId, $styleIds) && empty($visitor['style_id']))
			{
				self::$_show = false;
			}
		}
		if(self::$_show){
			switch ($hookName) {
				case 'forum_list_nodes':
					$newTemplate = $template->create('BRQCT_' . $hookName, $template->getParams());
					$contents .= $newTemplate->render();

				case 'navigation_tabs_forums':
					$newTemplate = $template->create('BRQCT_' . $hookName, $template->getParams());
					$contents .= $newTemplate->render();
					break;
			}
		}

		switch ($hookName) {
			case 'forum_edit_basic_information':
				$newTemplate = $template->create('BRQCT_' . $hookName, $template->getParams());
				$contents .= $newTemplate->render();
				break;
			case 'admin_page_edit_panes':
				$newTemplate = $template->create('BRQCT_' . $hookName, $template->getParams());
				$contents .= $newTemplate->render();
				break;
			case 'admin_link_forum_edit':
				$newTemplate = $template->create('BRQCT_' . $hookName, $template->getParams());
				$contents .= $newTemplate->render();
				break;
			case 'admin_category_edit':
				$newTemplate = $template->create('BRQCT_' . $hookName, $template->getParams());
				$contents .= $newTemplate->render();
				break;
		}
	}

	protected static $_isAdminCP = false;
	public static function initDependencies(XenForo_Dependencies_Abstract $dependencies, array $data)
	{
		XenForo_Template_Helper_Core::$helperCallbacks['nodeicon'] = array('Brivium_QuickCreateThread_EventListeners_Helpers', 'helperGetCustomIcon');

		if($dependencies instanceof XenForo_Dependencies_Admin)
		{
			self::$_isAdminCP = true;
		}
	}

	public static function templatePostRender($templateName, &$content, array &$containerData, XenForo_Template_Abstract $template)
	{
		if($template instanceof XenForo_Template_Public && !self::$_isAdminCP)
		{
			$visitor = XenForo_Visitor::getInstance();
			$styleId = $visitor['style_id'];
			$show = true;

			$styleIds = XenForo_Application::get('options')->BRQCT_displayStyle;
			$defaultStyleId = XenForo_Application::get('options')->defaultStyleId;

			if (!empty($visitor['style_id']) && !in_array($visitor['style_id'], $styleIds))
			{
				$show = false;
			}

			if (!in_array($defaultStyleId, $styleIds) && empty($visitor['style_id']))
			{
				$show = false;
			}

			if ($show)
			{
				$content = Brivium_QuickCreateThread_EventListeners_Helpers::replaceContent($content);
			}
		}
	}
}