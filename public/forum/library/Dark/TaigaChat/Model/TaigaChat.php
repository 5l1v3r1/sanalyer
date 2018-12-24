<?php
class Dark_TaigaChat_Model_TaigaChat extends XenForo_Model
{	
	public function getMessages(array $fetchOptions = array())
	{
		$limitOptions = $this->prepareLimitFetchOptions($fetchOptions);

		return $this->_getDb()->fetchAll($this->limitQueryResults(
			"
				SELECT *, IF(user.username IS NULL, taigachat.username, user.username) AS username
				FROM dark_taigachat AS taigachat
				LEFT JOIN xf_user AS user ON
					(user.user_id = taigachat.user_id)
				WHERE taigachat.id > ?
				ORDER BY taigachat.id DESC
			", $limitOptions['limit'], $limitOptions['offset']
		), array($fetchOptions['lastRefresh']));
	}
	
	public function getMessagesToday()
	{
		return $this->_getDb()->fetchAll(
			"
				SELECT *, IF(user.username IS NULL, taigachat.username, user.username) AS username
				FROM dark_taigachat AS taigachat
				LEFT JOIN xf_user AS user ON
					(user.user_id = taigachat.user_id)
				WHERE date > UNIX_TIMESTAMP()-60*60*24
				ORDER BY date DESC                
			"
		);
	}
	
	public function getMessageById($id, array $fetchOptions = array())
	{
		return $this->_getDb()->fetchRow('		
			SELECT *, IF(user.username IS NULL, taigachat.username, user.username) AS username
			FROM dark_taigachat AS taigachat
			LEFT JOIN xf_user AS user ON
				(user.user_id = taigachat.user_id)
			WHERE taigachat.id = ?
		', $id);
	}
	
	public function deleteMessage($id){
		
		return $this->_getDb()->query('		
			DELETE FROM dark_taigachat 
			WHERE id = ?
		', $id);
	}	
	
	public function deleteOldMessages(){
		$this->_getDb()->query("
			select @goat := date from dark_taigachat order by date desc limit 1000;
		");
		return $this->_getDb()->query("
			delete from dark_taigachat where date < @goat		
		");		
	}		
	
	public function canModifyMessage(array $message, array $user = null)
	{
		$this->standardizeViewingUserReference($user);

		if ($user['user_id'] == $message['user_id'])
		{
			return XenForo_Permission::hasPermission($user['permissions'], 'dark_taigachat', 'modify');
		}
		else
		{
			return XenForo_Permission::hasPermission($user['permissions'], 'dark_taigachat', 'modifyAll');
		}
	}
	
	public function canViewMessages(array $user = null)
	{
		$this->standardizeViewingUserReference($user);

		return XenForo_Permission::hasPermission($user['permissions'], 'dark_taigachat', 'view');		
	}
	
	public function canPostMessages(array $user = null)
	{
		$this->standardizeViewingUserReference($user);
		
		return XenForo_Permission::hasPermission($user['permissions'], 'dark_taigachat', 'post');		
	}
	
}