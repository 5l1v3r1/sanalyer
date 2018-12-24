<?php
class Borbole_RecentPosts_Option_Forum extends XenForo_Option_NodeChooser
{

	public static function renderOption(XenForo_View $view, $fieldPrefix, array $preparedOption, $canEdit)
	{
		$preparedOption['nodeFilter'] = 'Forum';
		return XenForo_Option_NodeChooser::_render( 'borbole_rp_option_list_option_select_multiple_box',$view, $fieldPrefix, $preparedOption, $canEdit);
	}

}
?>