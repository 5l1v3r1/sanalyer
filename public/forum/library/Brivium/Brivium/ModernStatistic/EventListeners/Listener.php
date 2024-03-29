<?php

class Brivium_ModernStatistic_EventListeners_Listener extends Brivium_BriviumHelper_EventListeners
{
	protected static  $_loaded = null;
	
	public static function initDependencies(XenForo_Dependencies_Abstract $dependencies, array $data)
	{
		if ($dependencies instanceof XenForo_Dependencies_Public)
		{
			$data = XenForo_Model::create('XenForo_Model_DataRegistry')->get('brmsModernStatisticCache');
			if(!isset($data['modernStatistics']) || !isset($data['positions'])){
				$data = self::getModelFromCache('Brivium_ModernStatistic_Model_ModernStatistic')->rebuildModernStatisticCache();
			}
			$statisticObj = new Brivium_ModernStatistic_ModernStatistic($data['modernStatistics']);
			XenForo_Application::set('brmsModernStatistics',$statisticObj);
			XenForo_Application::set('brmsPositions',$data['positions']);
			self::$_loaded = true;
		}
	}
	protected static  $_loadedTemplates = null;
	public static function templateCreate($templateName, array &$params, XenForo_Template_Abstract $template) {
		
		self::$_loadedTemplates[$templateName] = $templateName;
		$template->preloadTemplate('BRMS_ModernStatistic');
		$template->preloadTemplate('BRMS_modern_statistic_header');
	}
	
	protected static  $_dismissed = null;
	protected static  $_headerLoaded = null;
    public static function templateHook($hookName, &$contents, array $hookParams, XenForo_Template_Abstract $template)
	{
		if(self::$_loaded != null){
			$modernStatisticModel = self::getModelFromCache('Brivium_ModernStatistic_Model_ModernStatistic');
			$renderedContents = $modernStatisticModel->getModernStatisticForHook($hookName, self::$_loadedTemplates, $template->getParams(), $template);
			if($renderedContents){
				$newTemplate = $template->create('BRMS_modern_statistic_header',$template->getParams());
				$contents = $newTemplate->render() . $contents;
				$contents .= $renderedContents;
			}
		}
	}
}