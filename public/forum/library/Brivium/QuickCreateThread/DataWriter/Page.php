<?php

class Brivium_QuickCreateThread_DataWriter_Page extends XFCP_Brivium_QuickCreateThread_DataWriter_Page
{
	protected function _getFields()
	{
		$fields = parent::_getFields();
		$fields ['xf_page']['brqct_select'] = array('type' => self::TYPE_STRING, 'default' => 'useDefault');
		$fields ['xf_page']['brqct_url'] = array('type' => self::TYPE_STRING, 'default' => '');
		$fields ['xf_page']['brqct_icon_date'] = array('type' => self::TYPE_UINT, 'default' => 0);
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