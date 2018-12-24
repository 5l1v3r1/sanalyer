<?php

class Brivium_QuickCreateThread_ControllerAdmin_Category extends XFCP_Brivium_QuickCreateThread_ControllerAdmin_Category
{
	public function actionSave()
	{
		$GLOBALS['BRQCT_ControllerAdmin_Category']= $this;
		return parent::actionSave();
	}
	public function brqctSave($writer)
	{
		$brqct_select = $this->_input->filterSingle('brqct_select', XenForo_Input::STRING);
		if (!empty($brqct_select)){
			$writer->set('brqct_select',$brqct_select);
		}
		$default = $this->_input->filter(array(
			'brqct_url' => XenForo_Input::STRING
		));

		$writer->bulkSet($default);
	}
	public function actionIcon()
	{
		$nodeId = $this->_input->filterSingle('node_id', XenForo_Input::INT);
		$nodeModel = $this->_getNodeModel();
		$category = $nodeModel->getNodeById($nodeId);
			if (!$category)
			{
				return $this->responseError(new XenForo_Phrase('requested_category_not_found'), 404);
			}
		if ($nodeId && $nodeType = $this->_getNodeModel()->getNodeTypeByNodeId($nodeId))
		{
			$viewParams = array(
				'category' => $category
			);
			return $this->responseView('Brivium_QuickCreateThread_ViewAdmin_Node_Avatar', 'BRQCT_node_icon_category', $viewParams);
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

		$category = $this->_getNodeModel()->getNodeById($nodeId);
		$icons = XenForo_Upload::getUploadedFiles('icon');
		$icon = reset($icons);

		$iconModel = $this->getModelFromCache('Brivium_QuickCreateThread_Model_Icon');

		if ($icon)
		{
			$iconModel->uploadIcon($icon, $category, $number, 'category');
		}
		else if ($this->_input->filterSingle('delete', XenForo_Input::UINT))
		{
			$iconModel->deleteIcon($category['node_id'], $number, true, 'category');
		}

		return $this->responseRedirect(
			XenForo_ControllerResponse_Redirect::SUCCESS,
			XenForo_Link::buildAdminLink('nodes/edit', $category)
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