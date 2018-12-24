<?php

class DragDropNodes_ViewAdmin_Node_List extends XenForo_ViewAdmin_Base
{
	public function renderHtml()
	{
		$templatesTree = array();
		$this->_params['nodeTemplates'] = array();
		foreach ($this->_params['nodeTree'] AS $nodeId => $nodeChildren)
		{
			$this->_params['nodeTemplates'][$nodeId] = $this->renderNodeTemplates($nodeId, $nodeChildren);
		}
	}
	
	protected function renderNodeTemplates($nodeId, $childNodes)
	{
		$renderedChildNodes = array();
		foreach ($childNodes AS $childNodeId => $childNode)
		{
			$renderedChildNodes[] = $this->renderNodeTemplates($childNodeId, $childNode);
		}
		
		return $this->createTemplateObject(
			'drag_drop_nodes_node_list_item',
			$this->_params + array('nodeId' => $nodeId, 'renderedChildNodes' => $renderedChildNodes)
		);
	}
}