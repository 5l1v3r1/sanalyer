<?php

class Brivium_QuickCreateThread_ControllerAdmin_Forum extends XFCP_Brivium_QuickCreateThread_ControllerAdmin_Forum
{
	public function actionSave()
	{
		$GLOBALS['BRQCT_ControllerAdmin_Forum']= $this;
		return parent::actionSave();
	}
	public function brqctSave($writer)
	{
		$brqct_select = $this->_input->filterSingle('brqct_select', XenForo_Input::STRING);
		if (!empty($brqct_select)){
			$writer->set('brqct_select',$brqct_select);
		}
		$default = $this->_input->filter(array(
			'brqct_url_1' => XenForo_Input::STRING,
			'brqct_url_2' => XenForo_Input::STRING
		));
		$writer->bulkSet($default);
	}
	public function actionIcon()
	{
		$nodeId = $this->_input->filterSingle('node_id', XenForo_Input::INT);
		$icon = $this->_input->filterSingle('icon', XenForo_Input::INT);
		$forumModel = $this->_getForumModel();
		$forum = $forumModel->getForumById($nodeId);

		if (!$forum)
		{
			return $this->responseError(new XenForo_Phrase('requested_forum_not_found'), 404);
		}

		if ($nodeId && $nodeType = $this->_getNodeModel()->getNodeTypeByNodeId($nodeId))
		{
			$viewParams = array(
				'nodes' => $this->_getNodeModel()->getNodeById($nodeId),
				'icon' => $icon,
				'forum' => $forum
			);
			return $this->responseView('Brivium_QuickCreateThread_ViewAdmin_Node_Avatar', 'BRQCT_node_icon', $viewParams);
		}
		else
		{
			return $this->responseError(new XenForo_Phrase('requested_node_not_found'), 404);
		}
	}
	public function actionIconUpload()
	{
		$this->_assertPostOnly();

		$nodeId = $this->_input->filterSingle('node_id', XenForo_Input::INT);
		$number = $this->_input->filterSingle('number', XenForo_Input::INT);

		$forum = $this->_getForumModel()->getForumById($nodeId);
		$nodes = $this->_getNodeModel()->getNodeById($nodeId);
		$icons = XenForo_Upload::getUploadedFiles('icon');
		$icon = reset($icons);

		$iconModel = $this->getModelFromCache('Brivium_QuickCreateThread_Model_Icon');

		if ($icon)
		{
			$iconModel->uploadIcon($icon, $forum ,$number, 'forum');
		}
		else if ($this->_input->filterSingle('delete', XenForo_Input::UINT))
		{
			$iconModel->deleteIcon($forum['node_id'],$number, true, 'forum');
		}

		return $this->responseRedirect(
			XenForo_ControllerResponse_Redirect::SUCCESS,
			XenForo_Link::buildAdminLink('nodes/edit', $nodes)
		);
	}
	protected function _getNodeModel()
	{
		return $this->getModelFromCache('XenForo_Model_Node');
	}
	protected function _getForumModel()
	{
		return $this->getModelFromCache('XenForo_Model_Forum');
	}
}