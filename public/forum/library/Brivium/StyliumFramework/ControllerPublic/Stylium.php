<?php

class Brivium_StyliumFramework_ControllerPublic_Stylium extends XenForo_ControllerPublic_Abstract
{
	public function actionIndex()
	{
		$this->canonicalizeRequestUrl(
			XenForo_Link::buildPublicLink('index')
		);
	}
	public function actionSave()
	{
		$styleId = $this->_input->filterSingle('style_id', XenForo_Input::UINT);

		if($styleId){
			$settingParams = $this->_input->filter(array(
				'layout' => XenForo_Input::STRING,
				'font' => XenForo_Input::STRING,

				'primaryColor' => XenForo_Input::STRING,
				'secondaryColor' => XenForo_Input::STRING,
				'tertiaryColor' => XenForo_Input::STRING,

				'bodyBgColor' => XenForo_Input::STRING,
				'bodyBgSize' => XenForo_Input::STRING,
				'bodyBgImage' => XenForo_Input::STRING,
				'bodyBgRepeat' => XenForo_Input::STRING,
				'bodyBgPosition' => XenForo_Input::STRING,
			));
			$propertyValues = array(
				'stylium_layoutStyle' => $settingParams['layout'],
				'stylium_settingFont' => $settingParams['font'],

				'stylium_settingPrimaryColor' => $settingParams['primaryColor'],
				'stylium_settingSecondaryColor' => $settingParams['secondaryColor'],
				'stylium_settingTertiaryColor' => $settingParams['tertiaryColor'],

				'stylium_settingBodyBackgroundColor' => $settingParams['bodyBgColor'],
				'stylium_settingBodyBackgroundSize' => $settingParams['bodyBgSize'],
				'stylium_settingBodyBackgroundImage' => $settingParams['bodyBgImage'],
				'stylium_settingBodyBackgroundRepeat' => $settingParams['bodyBgRepeat'],
				'stylium_settingBodyBackgroundPosition' => $settingParams['bodyBgPosition'],
			);

			$userId = XenForo_Visitor::getUserId();
			$styliumModel = $this->_getStyliumModel();
			$styliumModel->updateStyliumSetting($userId, $styleId, $settingParams);
			$asDefault = $this->_input->filterSingle('as_default', XenForo_Input::UINT);
			if($asDefault && XenForo_Visitor::getInstance()->hasAdminPermission('style')){
				$propertyModel = $this->_getStylePropertyModel();

				$propertyNames = array_keys($propertyValues);
				$propertyNames = array_merge($propertyNames,array(
					'stylium_primaryColor',
					'stylium_secondaryColor',
					'stylium_tertiaryColor',
					'body',
				));

				$properties = $propertyModel->getStylePropertiesInStyleByDefinitionNames($styleId, $propertyNames);
				$properties = $propertyModel->prepareStyleProperties($properties, $styleId);
				$primaryColor = '';
				if(!empty($properties['stylium_primaryColor']) && !empty($properties['stylium_primaryColor']['property_value'])){
					$primaryColor = !empty($properties['stylium_primaryColor']['property_value'])?$properties['stylium_primaryColor']['property_value']:'';
					unset($properties['stylium_primaryColor']);
				}
				$secondaryColor = '';
				if(!empty($properties['stylium_secondaryColor'])){
					$secondaryColor = !empty($properties['stylium_secondaryColor']['property_value'])?$properties['stylium_secondaryColor']['property_value']:'';
					unset($properties['stylium_secondaryColor']);
				}
				$tertiaryColor = '';
				if(!empty($properties['stylium_tertiaryColor'])){
					$tertiaryColor = !empty($properties['stylium_tertiaryColor']['property_value'])?$properties['stylium_tertiaryColor']['property_value']:'';
					unset($properties['stylium_tertiaryColor']);
				}
				$customProperties = array();
				foreach($properties AS $propertyName=>$property){
					if(!empty($property['property_definition_id']) && !empty($property['property_name']) && !empty($propertyValues[$property['property_name']])){
						if($property['property_name']=='stylium_settingPrimaryColor' && $propertyValues[$property['property_name']] == $primaryColor){
							continue;
						}else if($property['property_name']=='stylium_settingSecondaryColor' && $propertyValues[$property['property_name']] == $secondaryColor){
							continue;
						}else if($property['property_name']=='stylium_settingTertiaryColor' && $propertyValues[$property['property_name']] == $tertiaryColor){
							continue;
						}
						$customProperties[$property['property_definition_id']] = $propertyValues[$property['property_name']];
					}
				}
				if($customProperties){
					$propertyModel->saveStylePropertiesInStyleFromInput($styleId, $customProperties);
				}
			}
		}
		return $this->responseView('Brivium_StyliumFramework_ViewPublic_StyliumFramework', '', array());
	}

	public function actionRestore()
	{
		$styleId = $this->_input->filterSingle('style_id', XenForo_Input::UINT);

		if($styleId){
			$userId = XenForo_Visitor::getUserId();
			$this->_getStyliumModel()->removeStyliumSetting($userId, $styleId);

			return $this->responseView('Brivium_StyliumFramework_ViewPublic_StyliumFramework', '', array());
		}else{
			return $this->responseView('Brivium_StyliumFramework_ViewPublic_StyliumFramework', '', array());
		}
	}

	/**
	 * @return  XenForo_Model_StyleProperty
	 */
	protected function _getStylePropertyModel()
	{
		return $this->getModelFromCache('XenForo_Model_StyleProperty');
	}

	/**
	 * Gets the stylium model.
	 *
	 * @return Brivium_StyliumFramework_Model_Stylium
	 */
	protected function _getStyliumModel()
	{
		return $this->getModelFromCache('Brivium_StyliumFramework_Model_Stylium');
	}
}