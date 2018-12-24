<?php
class Brivium_Tellurium_EventListeners_Helpers extends XenForo_Template_Helper_Core
{
	public static function helperGetCustomIcon($node, $number)
	{
		if (!$node && !$number){
			return '';
		}else{
			$url = self::_getCustomNodeIcon($node, $number);
			return htmlspecialchars($url);
		}
	}
	protected static function _getCustomNodeIcon($node, $number)
	{
		if (!empty($node['brt_icon_date_'.$number])){
			return "data/nodeIcons/".$node['node_id']."_".$number.".jpg?".$node['brt_icon_date_'.$number];
		}else if(!empty($node['brt_icon_date'])){
			return "data/nodeIcons/".$node['node_id']."_".$number.".jpg?$node[brt_icon_date]";
		}else{
			return "styles/brivium/Tellurium/df.jpg";
		}
	}

	public static function replaceContent($content)
	{
		return str_replace(':hover',':active', $content);
	}
}