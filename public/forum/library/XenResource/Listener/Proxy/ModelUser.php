<?php

class XenResource_Listener_Proxy_ModelUser extends XFCP_XenResource_Listener_Proxy_ModelUser
{
	public function prepareUserConditions(array $conditions, array &$fetchOptions)
	{
		$result = parent::prepareUserConditions($conditions, $fetchOptions);

		if (!empty($conditions['resource_count']) && is_array($conditions['resource_count']))
		{
			$result .= ' AND (' . $this->getCutOffCondition("user.resource_count", $conditions['resource_count']) . ')';
		}

		return $result;
	}

	public function prepareUserOrderOptions(array &$fetchOptions, $defaultOrderSql = '')
	{
		$choices = array(
			'resource_count' => 'user.resource_count'
		);
		$order = $this->getOrderByClause($choices, $fetchOptions);
		if ($order)
		{
			return $order;
		}

		return parent::prepareUserOrderOptions($fetchOptions, $defaultOrderSql);
	}
}