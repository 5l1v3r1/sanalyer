<?php
  
class Dark_TaigaChat_ControllerPublic_TaigaChat extends XenForo_ControllerPublic_Abstract
{
	public function actionList(){		
		$viewParams = array();
		$taigamodel = $this->_getTaigaChatModel();
		$options = XenForo_Application::get('options');
		$visitor = XenForo_Visitor::getInstance();
		
		
		$sidebar = false;		
		
		if(!$taigamodel->canViewMessages()){
			throw $this->getErrorOrNoPermissionResponseException('dark_no_permission_view_message');
		}
		
		if($this->_input->inRequest('sidebar') && $this->_input->filterSingle('sidebar', XenForo_Input::UINT))
			$sidebar = true;

		$messages = $taigamodel->getMessages(array(
			"page" => 1, 
			"perPage" => $sidebar ? $options->dark_taigachat_sidebarperpage : $options->dark_taigachat_fullperpage,
			"lastRefresh" => $this->_input->filterSingle('lastrefresh', XenForo_Input::UINT)
		));		
		
		// Workaround specific to my board, sorry I'm too lazy to remove it for release :P
		if($options->boardUrl == 'http://www.gamingmasters.co.uk'){
			foreach($messages as &$message){
				if(substr($message['username'], 0, 1) == '(')
					$message['usernameHtml'] = preg_replace('/(\(.*?\) )(.*)/', '<span class="muted">$1</span><span style="font-weight:bold">$2</span>', $message['username']);
			}
		}
		
		foreach($messages as &$message){
			if($taigamodel->canModifyMessage($message)){
				$message['canModify'] = true;
			}
		}
		
		$viewParams = array('taigachat' => array(
			"messages" => $messages,
			"sidebar" => $sidebar,
			"editside" => $options->dark_taigachat_editside,
			"timedisplay" => $options->dark_taigachat_timedisplay,
			"miniavatar" => $options->dark_taigachat_miniavatar,
			"lastrefresh" => $this->_input->filterSingle('lastrefresh', XenForo_Input::UINT)
		));
				
		return $this->responseView('Dark_TaigaChat_ViewPublic_TaigaChat_List', 'dark_taigachat_list', $viewParams); 
	}
	
	public function actionIndex(){
		
		$visitor = XenForo_Visitor::getInstance();
		$sessionModel = $this->getModelFromCache('Dark_TaigaChat_Model_Session');
		$options = XenForo_Application::get('options');
		
		$this->canonicalizeRequestUrl(
			XenForo_Link::buildPublicLink($options->dark_taigachat_route)
		);

		$onlineUsers = $sessionModel->getSessionActivityQuickList(
			$visitor->toArray(),
			array('cutOff' => array('>', $sessionModel->getOnlineStatusTimeout())),
			($visitor['user_id'] ? $visitor->toArray() : null)
		);
		$onlineUsersTaiga = $sessionModel->getSessionActivityQuickList(
			$visitor->toArray(),
			array(
				'cutOff' => array('>', $sessionModel->getOnlineStatusTimeout()), 
				'controller_name' => true
			),
			($visitor['user_id'] ? $visitor->toArray() : null)
		);
		
		$viewParams = array('taigachat' => array(
			'onlineUsers' => $onlineUsers,
			'online' => $onlineUsersTaiga,
		));

		return $this->responseView('Dark_TaigaChat_ViewPublic_TaigaChat_Index', 'dark_taigachat_full', $viewParams); 
	}
	
	public function actionPopup(){
				
		$options = XenForo_Application::get('options');
		
		$this->canonicalizeRequestUrl(
			XenForo_Link::buildPublicLink($options->dark_taigachat_route.'/popup')
		);
		
		$viewParams = array(
			'request' => $this->_request  // HAAAAAAAAAAAAAAAAX
		);

		return $this->responseView('Dark_TaigaChat_ViewPublic_TaigaChat_Popup', 'dark_taigachat_popup', $viewParams); 
	}
	
	public function actionPost(){
		$this->_assertPostOnly();
		$viewParams = array();		
		$visitor = XenForo_Visitor::getInstance();
		$taigamodel = $this->_getTaigaChatModel();
		$sessionmodel = $this->getModelFromCache('Dark_TaigaChat_Model_Session');
		$usermodel = $this->getModelFromCache('XenForo_Model_User');
		
		if(!$taigamodel->canPostMessages()){
			throw $this->getErrorOrNoPermissionResponseException('dark_no_permission_post_message');
		}
		
		
		$input = $this->_input->filter(array(
			'message' => XenForo_Input::STRING
		));
		//$input['message'] = XenForo_Helper_String::autoLinkBbCode($input['message']);

		$dw = XenForo_DataWriter::create('Dark_TaigaChat_DataWriter_Message');
		$dw->set('user_id', $visitor['user_id']);
		$dw->set('username', $visitor['user_id'] > 0 ? $visitor['username'] : new XenForo_Phrase('guest'));
		$dw->set('message', $input['message']);
		$dw->save();        
		
		// Keep users 'online' if they are posting in the shoutbox
		$user_id = $visitor->getUserId();
		if($user_id >= 1){
			$usermodel->updateSessionActivity($user_id, $_SERVER['REMOTE_ADDR'], 'Dark_TaigaChat_ControllerPublic_TaigaChat', 'post', 'valid', array());
		}
		
		return $this->responseReroute("Dark_TaigaChat_ControllerPublic_TaigaChat", "list");
		
		//return $this->responseView('Dark_TaigaChat_ViewPublic_TaigaChat_Post', '', $viewParams); 
	}
	
	public function actionEdit(){
		$taigamodel = $this->_getTaigaChatModel();
		$visitor = XenForo_Visitor::getInstance();
		$id = $this->_input->filterSingle('id', XenForo_Input::UINT);
		$options = XenForo_Application::get('options');
				
		$message = $taigamodel->getMessageById($id);
		
		if(!$message)
			throw $this->getErrorOrNoPermissionResponseException('dark_invalid_message');
			
		if(!$taigamodel->canModifyMessage($message)){
			throw $this->getErrorOrNoPermissionResponseException('dark_no_permission_modify_message');
		}
		
		if($this->_input->inRequest("message")){
			$input = $this->_input->filter(array(
				'message' => XenForo_Input::STRING
			));
			//$input['message'] = XenForo_Helper_String::autoLinkBbCode($input['message']);

			$dw = XenForo_DataWriter::create('Dark_TaigaChat_DataWriter_Message');
			$dw->setExistingData($id);
			$dw->set('message', $input['message']);
			$dw->save();
			
			return $this->responseRedirect(
				XenForo_ControllerResponse_Redirect::SUCCESS,
				$this->getDynamicRedirect($options->dark_taigachat_route) // attempt to stay on index
			);		
			
			//return $this->responseView('Dark_TaigaChat_ViewPublic_TaigaChat_Edit', ''); 
			
		} else {			
		
			$viewParams = array('taigachat' => array(
				"message" => $taigamodel->getMessageById($id),
				"route" => $options->dark_taigachat_route
			));
					
			return $this->responseView('Dark_TaigaChat_ViewPublic_TaigaChat_Edit', 'dark_taigachat_edit', $viewParams); 
		}
	}
	
	public function actionDelete(){
		$taigamodel = $this->_getTaigaChatModel();
		$visitor = XenForo_Visitor::getInstance();
		$id = $this->_input->filterSingle('id', XenForo_Input::UINT);
		
		$message = $taigamodel->getMessageById($id);
		
		if(!$message)
			throw $this->getErrorOrNoPermissionResponseException('dark_invalid_message');
			
		if(!$taigamodel->canModifyMessage($message)){
			throw $this->getErrorOrNoPermissionResponseException('dark_no_permission_modify_message');
		}
				
		$taigamodel->deleteMessage($id);
		
		return $this->responseRedirect(
			XenForo_ControllerResponse_Redirect::SUCCESS, 
			$this->getDynamicRedirect('taigachat') // attempt to stay on index
		);
		
		//return $this->responseView('Dark_TaigaChat_ViewPublic_TaigaChat_Delete', ''); 
	}
	
	/**
	* @return Dark_TaigaChat_Model_TaigaChat
	*/
	protected function _getTaigaChatModel(){
		return $this->getModelFromCache('Dark_TaigaChat_Model_TaigaChat');;
	}
	
	static public function getSessionActivityDetailsForList(array $activities){
		return new XenForo_Phrase('dark_viewing_shoutbox');
	}
}
