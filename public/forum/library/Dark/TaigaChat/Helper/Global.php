<?php
  
class Dark_TaigaChat_Helper_Global 
{	
	public static function getTaigaChatStuff(&$response, $action, $dis=false){
		$options = XenForo_Application::get('options');
		$visitor = XenForo_Visitor::getInstance();
		
		$smilies = array();
		$toolbar_bbcode = array();
		if($options->dark_taigachat_toolbar){
			$toolbar_bbcode_temp = explode("\n", str_replace("\r", "", trim($options->dark_taigachat_toolbar_bbcode)));            
			foreach($toolbar_bbcode_temp as $bbcode){
				$bbcode = explode(":", trim($bbcode));
				$toolbar_bbcode[$bbcode[0]] = $bbcode[1];
			}
			 
			if (XenForo_Application::isRegistered('smilies'))
			{
				$smilies = XenForo_Application::get('smilies');
			}
			else
			{
				$smilies = XenForo_Model::create('XenForo_Model_Smilie')->getAllSmiliesForCache();
				XenForo_Application::set('smilies', $smilies);
			}
		
			foreach($smilies as &$smilie){
				$smilie['text'] = $smilie['smilieText'][0];
				$smilie['sprite_mode'] = array_key_exists('sprite_params', $smilie);
			}
		}
		
		if(empty($response->params['taigachat']))
			$response->params['taigachat'] = array();
		
		// Don't forget to add to dark_taigachat template too
		$response->params['taigachat'] += array(
			"refreshtime" => $options->dark_taigachat_refreshtime,
			"maxrefreshtime" => $options->dark_taigachat_maxrefreshtime,
			"enabled" => $options->dark_taigachat_enabled,
			"maxlength" => $options->dark_taigachat_maxlength,
			"reverse" => $options->dark_taigachat_direction,
			"height" => $options->dark_taigachat_height,
			"route" => $options->dark_taigachat_route,
			"timedisplay" => $options->dark_taigachat_timedisplay,
			"toolbar" => $options->dark_taigachat_toolbar,
			"toolbar_bbcode" => $toolbar_bbcode,
			"toolbar_smilies" => $smilies,
			"thumbzoom" => $options->dark_taigachat_imagemode == 'ThumbZoom',
			"js_modification" => filemtime("js/dark/taigachat.js"),
			"canView" => $visitor->hasPermission('dark_taigachat', 'view'),
			"canPost" => $visitor->hasPermission('dark_taigachat', 'post'),
			"sidebar" => $response->viewName != "Dark_TaigaChat_ViewPublic_TaigaChat_Index" && $action != 'popup',
			"popup" => $action == 'popup',
			"limit" => $response->viewName != "Dark_TaigaChat_ViewPublic_TaigaChat_Index" && $action != 'popup' ? $options->dark_taigachat_sidebarperpage : $options->dark_taigachat_fullperpage,
		);        
	}
}