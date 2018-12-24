<?php

class Brivium_StyliumFramework_EventListeners_Listener extends Brivium_BriviumHelper_EventListeners
{
	public static function initDependencies(XenForo_Dependencies_Abstract $dependencies, array $data)
    {
		XenForo_Template_Helper_Core::$helperCallbacks['brsf_selectorclean'] = array('Brivium_StyliumFramework_ViewPublic_Helper_Style', 'selectorClean');
		XenForo_Template_Helper_Core::$helperCallbacks['brsf_bodyclasses'] = array('Brivium_StyliumFramework_ViewPublic_Helper_Style', 'bodyClasses');
		XenForo_Template_Helper_Core::$helperCallbacks['brsf_stylethumb'] = array('Brivium_StyliumFramework_ViewPublic_Helper_Style', 'styleThumb');
	}

	protected static  $_loadedTemplateList = null;
	public static function templateCreate($templateName, array &$params, XenForo_Template_Abstract $template)
	{
		if(self::$_loadedTemplateList===null && isset(self::$_templateList)){
			self::$_templateList['public']['_all']['stylium_setting_value']	 = 'stylium_setting_value';
			self::$_templateList['public']['_all']['stylium_page_container_head']	 = 'stylium_page_container_head';
			self::$_templateList['public']['_all']['stylium_settings']	 = 'stylium_settings';
			self::$_templateList['public']['_all']['stylium_setting_toggle_footer']	 = 'stylium_setting_toggle_footer';

			self::$_loadedTemplateList = true;
		}

	}

	protected static $_loaded = null;
	protected static $_hasTemplatePerm = null;
	protected static $_styliumValues = null;

	public static function hasTemplatePerm()
    {
		if (self::$_hasTemplatePerm === null)
		{
			self::$_hasTemplatePerm = XenForo_Visitor::getInstance()->hasPermission('Brivium_StyliumFramework', 'useSetting');
		}

		return self::$_hasTemplatePerm;
	}

	public static function getSettingValues($userId, $styleId)
    {
		if (self::$_styliumValues === null){
			$settingDefaultValues = array(
				'layout'			=> XenForo_Template_Helper_Core::styleProperty('stylium_layoutStyle'),
				'primaryColor'		=> XenForo_Template_Helper_Core::styleProperty('stylium_settingPrimaryColor'),
				'secondaryColor'	=> XenForo_Template_Helper_Core::styleProperty('stylium_settingSecondaryColor'),
				'tertiaryColor'		=> XenForo_Template_Helper_Core::styleProperty('stylium_settingTertiaryColor'),

				'bodyBgFullPage'	=> XenForo_Template_Helper_Core::styleProperty('stylium_settingFullPageBg'),

				'bodyBgColor'		=> XenForo_Template_Helper_Core::styleProperty('stylium_settingBodyBackgroundColor'),
				'bodyBgSize'		=> XenForo_Template_Helper_Core::styleProperty('stylium_settingBodyBackgroundSize'),
				'bodyBgImage'		=> XenForo_Template_Helper_Core::styleProperty('stylium_settingBodyBackgroundImage'),
				'bodyBgRepeat'		=> XenForo_Template_Helper_Core::styleProperty('stylium_settingBodyBackgroundRepeat'),
				'bodyBgPosition'	=> XenForo_Template_Helper_Core::styleProperty('stylium_settingBodyBackgroundPosition'),

				'font'				=> XenForo_Template_Helper_Core::styleProperty('stylium_settingFont'),
				'fontVariants'		=> XenForo_Template_Helper_Core::styleProperty('stylium_settingFontVariants')
			);

			$styliumModel = self::getModelFromCache('Brivium_StyliumFramework_Model_Stylium');
			if(!empty($userId)){
				$styliumSetting = $styliumModel->getStyliumSettingForUser($userId, $styleId);
			}else{
				$styliumSetting = $styliumModel->getStyliumSettingForGuest($styleId);
			}
			$styliumSetting = $styliumModel->prepareStyliumSetting($styliumSetting);

			$settingCustomValues = array();
			$mergedValues = array();

			if(!self::hasTemplatePerm()){
				$mergedValues = $settingDefaultValues;
			}else{
				foreach($settingDefaultValues AS $propertyName=>$propertyValue){
					if(isset($styliumSetting['settingParam'][$propertyName])){
						$settingCustomValues[$propertyName] = $styliumSetting['settingParam'][$propertyName];
						$mergedValues[$propertyName] = $styliumSetting['settingParam'][$propertyName];
					}else{
						$settingCustomValues[$propertyName] = '';
						$mergedValues[$propertyName] = $settingDefaultValues[$propertyName];
					}
				}
			}
			Brivium_StyliumFramework_ViewPublic_Helper_Style::setStyliumValue($mergedValues);
			self::$_styliumValues = array($settingDefaultValues, $settingCustomValues, $mergedValues);
		}
		return self::$_styliumValues;
	}

	public static function templateHook($hookName, &$contents, array $hookParams, XenForo_Template_Abstract $template)
    {
		switch ($hookName) {
			case 'page_container_js_body':
				if(self::$_loaded===null){
					$templateParams = $template->getParams();
					if(isset($templateParams['visitorStyle']['properties'])){
						$properties = @unserialize($templateParams['visitorStyle']['properties']);
						if(isset($properties['stylium_layoutStyle'])){
							$newTemplate = $template->create('stylium_setting_value', $templateParams);
							$newParams = array();
							if(isset($templateParams['visitorStyle']['style_id']) && isset($templateParams['visitor']['user_id'])){
								list($settingDefaultValues, $settingCustomValues, $mergedValues) = self::getSettingValues($templateParams['visitor']['user_id'], $templateParams['visitorStyle']['style_id']);
								$newParams['settingDefaultValues'] = $settingDefaultValues;
								$newParams['settingCustomValues'] = $settingCustomValues;
								$newParams['styliumValue'] = $mergedValues;
							}
							$newTemplate->setParams($newParams);
							$contents .= $newTemplate->render();
						}
					}

					self::$_loaded = true;
				}
				break;
			case 'stylium_settings':
			case 'stylium_setting_toggle_footer':
				if (self::hasTemplatePerm())
				{
					$newTemplate = $template->create($hookName, $template->getParams());
					$newParams = array(
						'canSaveBoardDefault'	=> XenForo_Visitor::getInstance()->hasAdminPermission('style')
					);
					$newTemplate->setParams($newParams);
					$contents .= $newTemplate->render();
				}
				break;
			case 'page_container_head':
				$templateParams = $template->getParams();
				$templateParams = $template->getParams();
					if(isset($templateParams['visitorStyle']['properties'])){
						$properties = @unserialize($templateParams['visitorStyle']['properties']);
						if(isset($properties['stylium_layoutStyle'])){
							$newTemplate = $template->create('stylium_page_container_head', $templateParams);
							$newParams = array();
							$newParams['canUseStyliumSetting'] = self::hasTemplatePerm();
							if(isset($templateParams['visitorStyle']['style_id']) && isset($templateParams['visitor']['user_id'])){
								list($settingDefaultValues, $settingCustomValues, $mergedValues) = self::getSettingValues($templateParams['visitor']['user_id'], $templateParams['visitorStyle']['style_id']);
								$newParams['settingDefaultValues'] = $settingDefaultValues;
								$newParams['settingCustomValues'] = $settingCustomValues;
								//prd($settingCustomValues);
								$newParams['styliumValue'] = $mergedValues;
								$systemFont = array(
									'Arial',
									'Tahoma',
									'Verdana',
									'Trebuchet MS',
									'Lucida Sans Unicode',
									'Georgia',
									'Times New Roman',
								);
								$newParams['systemFont'] = $systemFont;
							}
							$newTemplate->setParams($newParams);
							$contents .= $newTemplate->render();
						}
					}
				break;
		}
		self::$_copyrightNotice = '<div id="BRCopyright" class="concealed muted" style="float:left;margin-left: 10px;"><style>@media (max-width:480px){.Responsive #BRCopyright span{display: none;}}</style><div class="muted"><a href="http://brivium.com/xenforo-add-ons" class="concealed" title="Brivium XenForo Add-ons">XenForo Add-ons</a> & <a href="http://brivium.com/xenforo-styles" class="concealed" title="Brivium XenForo Add-ons">XenForo Styles</a><span> &trade;  &copy; 2012-'.date("Y").' Brivium LLC.</span></div></div>';
	}
}