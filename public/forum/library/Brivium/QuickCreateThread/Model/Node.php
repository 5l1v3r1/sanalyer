<?php

class Brivium_CustomNodeStyle_Model_Node extends XFCP_Brivium_CustomNodeStyle_Model_Node
{
	public function getCategories()
	{
		return $this->fetchAllKeyed('
				SELECT *
				FROM xf_node
				WHERE node_type_id = "Category" AND parent_node_id > 0
			', 'node_id');
	}
	public function getCategoriesRoot()
	{
		return $this->fetchAllKeyed('
				SELECT *
				FROM xf_node
				WHERE node_type_id = "Category" AND parent_node_id = 0
			', 'node_id');
	}
}