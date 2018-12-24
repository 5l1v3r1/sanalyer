<?php

/**
* Data writer for styles.
*
* @package XenForo_Style
*/
class Brivium_StyliumFramework_DataWriter_Style extends XFCP_Brivium_StyliumFramework_DataWriter_Style
{
	protected function _getFields()
	{
		$fields = parent::_getFields();
		$fields['xf_style']['brsf_style_id'] = array('type' => self::TYPE_STRING, 'default' => '');
		return $fields;
	}
	public function save()
	{
		$result = parent::save();
		$GLOBALS['BRSFStyleId'] = $this->get('style_id');
		return $result;
	}
}