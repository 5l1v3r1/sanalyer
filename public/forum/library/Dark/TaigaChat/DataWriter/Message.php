<?php

class Dark_TaigaChat_DataWriter_Message extends XenForo_DataWriter
{
	
	/**
	* Gets the fields that are defined for the table. See parent for explanation.
	*
	* @return array
	*/
	protected function _getFields()
	{
		$options = XenForo_Application::get('options');
		return array(
			'dark_taigachat' => array(
				'id'      	   => array('type' => self::TYPE_UINT,   'autoIncrement' => true),
				'user_id'      => array('type' => self::TYPE_UINT,   'required' => true),
				'date'         => array('type' => self::TYPE_UINT,   'required' => true, 'default' => XenForo_Application::$time),
				'username'     => array('type' => self::TYPE_STRING, 'required' => true, 'maxLength' => 50),
				'message'      => array('type' => self::TYPE_STRING, 'required' => true, 'requiredError' => 'please_enter_valid_message', 'maxLength' => $options->dark_taigachat_maxlength)
			)
		);
	}

	/**
	* Gets the actual existing data out of data that was passed in. See parent for explanation.
	*
	* @param mixed
	*
	* @return array|false
	*/
	protected function _getExistingData($data)
	{
		if (!$id = $this->_getExistingPrimaryKey($data))
		{
			return false;
		}

		return array('dark_taigachat' => $this->_getModel()->getMessageById($id));
	}

	/**
	* Gets SQL condition to update the existing record.
	*
	* @return string
	*/
	protected function _getUpdateCondition($tableName)
	{
		return 'id = ' . $this->_db->quote($this->getExisting('id'));
	}


	/**
	 * Adds any number of responses to the poll. Blank options are ignored.
	 *
	 * @param array $responses
	 */
	public function addResponses(array $responses)
	{
		foreach ($responses AS $key => $response)
		{
			if (!is_string($response) || $response === '')
			{
				unset($responses[$key]);
			}
		}
		$this->_newResponses = array_merge($this->_newResponses, $responses);
	}

	/**
	 * Determines if the poll has new responses.
	 *
	 * @return boolean
	 */
	public function hasNewResponses()
	{
		return (count($this->_newResponses) > 0);
	}

	/**
	 * Pre-save handling.
	 */
	protected function _preSave()
	{		
		// what is this i dont even
		/*if ($this->isInsert() && !$this->get('user_id') && $this->isChanged('username'))
		{
			$userDw = XenForo_DataWriter::create('XenForo_DataWriter_User', XenForo_DataWriter::ERROR_ARRAY);
			$userDw->set('username', $this->get('username'));
			$userErrors = $userDw->getErrors();
			if ($userErrors)
			{
				$this->error(reset($userErrors), 'username');
			}
		}*/
		
		if ($this->isInsert() && !$this->isChanged('date'))
		{
			$this->set('date', XenForo_Application::$time);
		}		
	}

	/**
	 * Post-save handling.
	 */
	protected function _postSave()
	{
		
	}

	/**
	 * Post-delete handling.
	 */
	protected function _postDelete()
	{
		
	}

	/**
	 * @return XenForo_Model_Poll
	 */
	protected function _getModel()
	{
		return $this->getModelFromCache('Dark_TaigaChat_Model_TaigaChat');
	}
}