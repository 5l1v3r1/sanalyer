<?php

class DragDropNodes_ControllerAdmin_Node extends XFCP_DragDropNodes_ControllerAdmin_Node
{
	public function actionIndex()
	{
		$response = parent::actionIndex();
		
		$response->params['nodeTree'] = $this->_getNodeModel()->getNodeTree($response->params['nodes']);
		$response->params['nodeCount'] = count($response->params['nodes']);
		
		return $this->responseView('DragDropNodes_ViewAdmin_Node_List', 'node_list', $response->params);
	}
	
	public function actionUpdateDisplayOrder()
	{
		$input = $this->_input->filter(array(
			'nodes' => XenForo_Input::ARRAY_SIMPLE
		));
		
		$nodeModel = $this->_getNodeModel();
		
		$nodes = $nodeModel->getAllNodes();
		
		$updatedNodes = array();
		foreach ($nodes AS $node)
		{
			if (isset($input['nodes'][$node['node_id']]))
			{
				if ($input['nodes'][$node['node_id']]['parent_node_id'] != $node['parent_node_id'] ||
					$input['nodes'][$node['node_id']]['display_order'] != $node['display_order']
				)
				{
					$nodesInputHandler = new XenForo_Input($input['nodes'][$node['node_id']]);
					$nodeInput = $nodesInputHandler->filter(array(
						'parent_node_id' => XenForo_Input::UINT,
						'display_order' => XenForo_Input::UINT,
					));
					
					$dw = $this->_getNodeDataWriter();
					$dw->setExistingData($node['node_id']);
					$dw->set('parent_node_id', $nodeInput['parent_node_id']);
					$dw->set('display_order', $nodeInput['display_order']);
					$dw->save();
				}
			}
		}
		
		return $this->responseRedirect(
			XenForo_ControllerResponse_Redirect::SUCCESS,
			XenForo_Link::buildAdminLink('nodes')
		);
	}
}