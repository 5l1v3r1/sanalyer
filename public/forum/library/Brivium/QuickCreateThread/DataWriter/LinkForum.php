<?php

class Brivium_QuickCreateThread_DataWriter_LinkForum extends XFCP_Brivium_QuickCreateThread_DataWriter_LinkForum
{
	protected function _getFields()
	{
		$fields = parent::_getFields();
		$fields ['xf_link_forum']['brqct_select'] = array('type' => self::TYPE_STRING, 'default' => 'useDefault');
		$fields ['xf_link_forum']['brqct_url'] = array('type' => self::TYPE_STRING, 'default' => '');
		$fields ['xf_link_forum']['brqct_icon_date'] = array('type' => self::TYPE_UINT, 'default' => 0);
		return $fields;
	}
	public function save()
	{
		if(!empty($GLOBALS['BRQCT_ControllerAdmin_LinkForum'])){
			$GLOBALS['BRQCT_ControllerAdmin_LinkForum']->brqctSave($this);
			unset($GLOBALS['BRQCT_ControllerAdmin_LinkForum']);
		}
		return parent::save();
	}
}