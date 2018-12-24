<?php

class Brivium_QuickCreateThread_ControllerAdmin_Page extends XFCP_Brivium_QuickCreateThread_ControllerAdmin_Page
{
	public function actionSave()
	{
		$GLOBALS['BRQCT_ControllerAdmin_Page']= $this;
		return parent::actionSave();
	}
	public function brqctSave($writer)
	{
		$brqct_select = $this->_input->filterSingle('brqct_select', XenForo_Input::STRING);
		if (!empty($brqct_select)){
			$writer->set('brqct_select',$brqct_select);
		}
		$default = $this->_input->filter(array(
			'brqct_url' => XenForo_Input::STRING,
		));
		$writer->bulkSet($default);
	}
	public function actionIcon()
	{
		$nodeId = $this->_input->filterSingle('node_id', XenForo_Input::INT);
		$pageModel = $this->_getPageModel();
		$page = $pageModel->getPageById($nodeId);
			if (!$page)
			{
				return $this->responseError(new XenForo_Phrase('requested_page_not_found'), 404);
			}
		if ($nodeId && $nodeType = $this->_getNodeModel()->getNodeTypeByNodeId($nodeId))
		{
			$viewParams = array(
				'nodes' => $this->_getNodeModel()->getNodeById($nodeId),
				'page' => $page
			);
			return $this->responseView('Brivium_QuickCreateThread_ViewAdmin_Node_Avatar', 'BRQCT_node_icon_page', $viewParams);
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
		$number = 1;

		$page = $this->_getPageModel()->getPageById($nodeId);
		$nodes = $this->_getNodeModel()->getNodeById($nodeId);
		$icons = XenForo_Upload::getUploadedFiles('icon');
		$icon = reset($icons);

		$iconModel = $this->getModelFromCache('Brivium_QuickCreateThread_Model_Icon');

		if ($icon)
		{
			$iconModel->uploadIcon($icon, $page, $number, 'page');
		}
		else if ($this->_input->filterSingle('delete', XenForo_Input::UINT))
		{
			$iconModel->deleteIcon($page['node_id'], $number, true, 'page');
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
	protected function _getPageModel()
	{
		return $this->getModelFromCache('XenForo_Model_Page');
	}
}