<?php

class Brivium_Tellurium_DataWriter_Forum extends XFCP_Brivium_Tellurium_DataWriter_Forum
{
	protected function _getFields()
	{
		$fields = parent::_getFields();
		$fields ['xf_forum']['brt_select'] = array('type' => self::TYPE_STRING, 'default' => 'useDefault');
		$fields ['xf_forum']['brt_url_1'] = array('type' => self::TYPE_STRING, 'default' => '');
		$fields ['xf_forum']['brt_url_2'] = array('type' => self::TYPE_STRING, 'default' => '');
		$fields ['xf_forum']['brt_icon_date_1'] = array('type' => self::TYPE_UINT, 'default' => 0);
		$fields ['xf_forum']['brt_icon_date_2'] = array('type' => self::TYPE_UINT, 'default' => 0);
		return $fields;
	}
	public function save()
	{
		if(!empty($GLOBALS['BRQCT_ControllerAdmin_Forum'])){
			$GLOBALS['BRQCT_ControllerAdmin_Forum']->brqctSave($this);
			unset($GLOBALS['BRQCT_ControllerAdmin_Forum']);
		}
		return parent::save();
	}
}