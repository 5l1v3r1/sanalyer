<?php

class Brivium_StyliumFramework_ViewPublic_Stylium_Stylium extends XenForo_ViewPublic_Base
{
	public function renderJson()
	{
		$output = array();

		$output = XenForo_ViewRenderer_Json::jsonEncodeForOutput($output);
		return $output;
	}
}