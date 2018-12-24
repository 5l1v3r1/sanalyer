<?php
class Brivium_StyliumFramework_Model_Stylium extends XenForo_Model
{
	public function getStyliumSettingById($styliumSettingId)
	{
		return $this->_getDb()->fetchRow('
			SELECT *
			FROM xf_brivium_stylium_setting
			WHERE stylium_setting_id = ?
		',$styliumSettingId);
	}

	public function getStyleByStyliumIdOrTitle($styliumId, $title = '')
	{
		$db = $this->_getDb();
		$data = array();
		if ($styliumId && $db->fetchRow('SHOW columns FROM `xf_style` WHERE Field = ?', 'brsf_style_id')) {
			$data = $db->fetchRow('
				SELECT *
				FROM xf_style
				WHERE brsf_style_id = ?
			', $styliumId);
		}
		if(!$data && $title){
			$data = $db->fetchRow('
				SELECT *
				FROM xf_style
				WHERE title = ?
			', $title);
		}
		return $data;
	}
	
	public function getStyliumSettingForUser($userId, $styleId)
	{
		return $this->_getDb()->fetchRow('
			SELECT *
				FROM xf_brivium_stylium_setting
			WHERE user_id = ? AND style_id = ?
		', array($userId, $styleId));
	}
	
	
	
	public function insertStyliumSetting($userId, $styleId, $params)
	{
		$params = serialize($params);
		$this->_getDb()->query('
			INSERT INTO xf_brivium_stylium_setting
				(`user_id`, `style_id`, `setting_param`, `last_update`)
			VALUES
				(?, ?, ?, ?)
		', array($userId, $styleId, $params, XenForo_Application::$time));
		return true;
	}
	
	public function prepareStyliumSetting($styliumSetting)
	{
		if(!empty($styliumSetting['setting_param'])){
			$styliumSetting['settingParam'] = @unserialize($styliumSetting['setting_param']);
		}
		return $styliumSetting;
	}
	
	public function updateStyliumSettingForUser($userId, $styleId, array $params)
	{
		if(!$this->getStyliumSettingForUser($userId,$styleId)){
			return $this->insertStyliumSetting($userId, $styleId, $params);
		}
		$db = $this->_getDb();
		
		$params = serialize($params);
		
		$condition = 'user_id = ' . $db->quote($userId) . ' AND style_id = ' . $db->quote($styleId);
		$db->update('xf_brivium_stylium_setting',
			array(
				'setting_param' => $params,
				'last_update' => XenForo_Application::$time,
			),$condition
		);
		return true;
	}
	public function removeStyliumSettingForUser($userId, $styleId)
	{
		$db = $this->_getDb();
		
		$condition = 'user_id = ' . $db->quote($userId) . ' AND style_id = ' . $db->quote($styleId);
		$db->delete('xf_brivium_stylium_setting',$condition);
		return true;
	}
	
	
	public function getStyliumSettingForGuest($styleId)
	{
		$detail = XenForo_Helper_Cookie::getCookie($this->getCookieName($userId, $styleId));
		if($detail){
			$detail = @unserialize($detail);
		}
		return $detail;
	}
	
	public function updateStyliumSetting($userId, $styleId, array $params)
	{
		if($userId){
			$this->updateStyliumSettingForUser($userId, $styleId, $params);
			XenForo_Helper_Cookie::deleteCookie($this->getCookieName($userId, $styleId));
		}else{
			$params = serialize($params);
			$data = array(
				'stylium_setting_id'	=> 0,
				'user_id'	=> $userId,
				'style_id'	=> $styleId,
				'setting_param'	=> $params,
				'last_update'	=> XenForo_Application::$time,
			);
			
			XenForo_Helper_Cookie::setCookie($this->getCookieName($userId, $styleId), serialize($data), 86400 * 365, true);
		}
		return ;
	}
	
	public function removeStyliumSetting($userId, $styleId)
	{
		if($userId){
			$this->removeStyliumSettingForUser($userId, $styleId);
			XenForo_Helper_Cookie::deleteCookie($this->getCookieName($userId, $styleId));
		}else{
			XenForo_Helper_Cookie::deleteCookie($this->getCookieName($userId, $styleId));
		}
		return ;
	}
	public function getCookieName($userId, $styleId)
	{
		return 'brsf_stylium_style_'.$styleId;
	}
	
	
}