<?php

class Brivium_Tellurium_DataWriter_Category extends XFCP_Brivium_Tellurium_DataWriter_Category
{
	protected function _getFields()
	{
		$fields = parent::_getFields();
		$fields ['xf_node']['brt_select'] = array('type' => self::TYPE_STRING, 'default' => 'useDefault');
		$fields ['xf_node']['brt_url'] = array('type' => self::TYPE_STRING, 'default' => '');
		$fields ['xf_node']['brt_icon_date'] = array('type' => self::TYPE_UINT, 'default' => 0);
		return $fields;
	}

	public function save()
	{
		if(!empty($GLOBALS['BRQCT_ControllerAdmin_Category'])){
			$GLOBALS['BRQCT_ControllerAdmin_Category']->brqctSave($this);
			unset($GLOBALS['BRQCT_ControllerAdmin_Category']);
		}
		return parent::save();
	}

	protected function _getDataRegistryModel()
	{
		return $this->getModelFromCache('XenForo_Model_DataRegistry');
	}
}