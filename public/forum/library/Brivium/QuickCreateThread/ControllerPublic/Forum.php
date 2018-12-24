<?php
class Brivium_QuickCreateThread_ControllerPublic_Forum extends XFCP_Brivium_QuickCreateThread_ControllerPublic_Forum
{
	public function actionAddThread()
	{
		$this->_assertPostOnly();
		$forumId = $this->_input->filterSingle('node_id', XenForo_Input::UINT);

		if (empty($forumId))
		{
			return $this->responseError(new XenForo_Phrase('BRQCT_you_must_select_forum'));
		}

		if ($this->_input->inRequest('more_options'))
		{
			return $this->responseReroute(__CLASS__, 'CreateThread');
		}

		return parent::actionAddThread();
	}

	public function actionCreateThread()
	{
		$action = parent::actionCreateThread();

		$input['message'] = $this->getHelper('Editor')->getMessageText('message', $this->_input);
		$input['message'] = XenForo_Helper_String::autoLinkBbCode($input['message']);

		if(utf8_strlen($input['message']) && empty($action->params['draft']['message']))
		{
			$action->params['draft']['message'] = $input['message'];
		}

		return $action;
	}
}