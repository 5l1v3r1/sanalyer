<?php

class Brivium_Tellurium_WidgetRenderer_Tellurium extends WidgetFramework_WidgetRenderer
{
	public function extraPrepareTitle(array $widget)
	{
		if (empty($widget['title']))
		{
			return new XenForo_Phrase('BRQCT_quick_create_thread');
		}

		return parent::extraPrepareTitle($widget);
	}

	protected function _getConfiguration()
	{
		return array('name' => 'Brivium - Quick Create Thread');
	}

	protected function _getOptionsTemplate()
	{
		return false;
	}

	protected function _getRenderTemplate(array $widget, $positionCode, array $params)
	{
		return 'BRQCT_wf_quick_create_thread';
	}

	protected function _render(array $widget, $positionCode, array $params, XenForo_Template_Abstract $renderTemplateObject)
	{
		if(!isset($GLOBALS['BRQCTWidgetFramework_quickCreateThread'])){
			$visitor = XenForo_Visitor::getInstance();
			$show = true;
			$excludeGroups = XenForo_Application::get('options')->BRQCT_excludeUserGroup;
			if($excludeGroups){
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
						$show = false;
						break;
					}
				}
			}
			$GLOBALS['BRQCTWidgetFramework_quickCreateThread'] = $show;
		}
		if($GLOBALS['BRQCTWidgetFramework_quickCreateThread']){
			$renderTemplateObject->setParam('show', true);
		}

		return $renderTemplateObject->render();
	}

}
