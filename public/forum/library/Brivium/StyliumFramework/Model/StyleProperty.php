<?php

/**
 * Model for style properties and style property definitions.
 * Note that throughout, style ID -1 is the ACP master.
 *
 * @package XenForo_StyleProperty
 */
class Brivium_StyliumFramework_Model_StyleProperty extends XFCP_Brivium_StyliumFramework_Model_StyleProperty
{
	/**
	 * Gets all style properties in a style with the specified definition IDs
	 * that have been customized or defined directly in the style.
	 *
	 * @param integer $styleId
	 * @param array $definitionIds
	 *
	 * @return array Format: [definition id] => info
	 */
	public function getStylePropertiesInStyleByDefinitionNames($styleId, array $names, array $path = null)
	{
		if (!$names)
		{
			return array();
		}
		if ($path === null)
		{
			$path = $this->getParentPathFromStyle($styleId);
		}
		return $this->fetchAllKeyed('
			SELECT property_definition.*,
				style_property.*
			FROM xf_style_property AS style_property
			INNER JOIN xf_style_property_definition AS property_definition ON
				(property_definition.property_definition_id = style_property.property_definition_id)
			WHERE style_property.style_id IN (' . $this->_getDb()->quote($path) . ')
				AND property_definition.property_name IN (' . $this->_getDb()->quote($names) . ')
			ORDER BY property_definition.display_order
		', 'property_name');
	}

}