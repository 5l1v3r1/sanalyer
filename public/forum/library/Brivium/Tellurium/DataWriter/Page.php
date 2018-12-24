<?php

class Brivium_Tellurium_DataWriter_Page extends XFCP_Brivium_Tellurium_DataWriter_Page
{
	protected function _getFields()
	{
		$fields = parent::_getFields();
		$fields ['xf_page']['brt_select'] = array('type' => self::TYPE_STRING, 'default' => 'useDefault');
		$fields ['xf_page']['brt_url'] = array('type' => self::TYPE_STRING, 'default' => '');
		$fields ['xf_page']['brt_icon_date'] = array('type' => self::TYPE_UINT, 'default' => 0);
		return $fields;
	}

	public function save()
	{
		if(!empty($GLOBALS['BRQCT_ControllerAdmin_Page'])){
			$GLOBALS['BRQCT_ControllerAdmin_Page']->brqctSave($this);
			unset($GLOBALS['BRQCT_ControllerAdmin_Page']);
		}
		return parent::save();
	}
}