<?php
/**
* Brivium_Tellurium_Option_Modification
*/
class Brivium_Tellurium_Option_Modification
{
	public static function modification(array $matched)
	{

		$options = XenForo_Application::get('options');

			return '<a href="{xen:link categories, $category}">
	<xen:if is="{$category.brt_select}==\'url\'">
		<img src="{$category.brt_url}" class="brIcon" />
	<xen:else />
		<img src="{xen:helper nodeIcon, $category, 1}" class="brIcon" />
	</xen:if>
</a>';
		

		return $matched[0];
	}
}