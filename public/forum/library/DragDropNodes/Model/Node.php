<?php

class DragDropNodes_Model_Node extends XFCP_DragDropNodes_Model_Node
{
	/**
	 * Gets a tree structure of the nodes.
	 *
	 * @param array|null $nodes	Node list from getAllNodes().
	 *
	 * @return array The node tree.
	 */
	public function getNodeTree($nodes = null)
	{
		if (!$this->_isNodesArray($nodes))
		{
			$nodes = $this->getAllNodes(true);
		}
		
		$nodeTree = array();
		$this->_prepareNodeTree($nodes, $nodeTree);
		
		return $nodeTree;
	}
	
	/**
	 * Internal recursive function which prepares the node tree.
	 *
	 * @param array $nodes		Node list from getAllNodes().
	 * @param array	$nodeTree	A pointer to the current node processed.
	 * @param int	@parentId	The parent ID of the current node.
	 */
	protected function _prepareNodeTree($nodes, &$nodeTree, $parentId = 0)
	{
		foreach ($nodes AS $node)
		{
			if ($node['parent_node_id'] == $parentId)
			{
				$nodeTree[$node['node_id']] = array();
				$this->_prepareNodeTree($nodes, $nodeTree[$node['node_id']], $node['node_id']);
			}
		}
	}
}