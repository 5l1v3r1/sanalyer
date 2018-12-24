<?php

class Dark_TaigaChat_ViewPublic_TaigaChat_List extends XenForo_ViewPublic_Base
{
	public function renderJson(){
		
		$options = XenForo_Application::get('options');
		
		$formatter = XenForo_BbCode_Formatter_Base::create('Dark_TaigaChat_BbCode_Formatter_Tenori', array('view' => $this));
		
		switch($options->dark_taigachat_bbcode){
			case 'Full':
				$formatter->displayableTags = true;
				break;
			case 'Basic':
			default:
				$formatter->displayableTags = array('img', 'url', 'email', 'b', 'u', 'i', 's', 'color');			
				break;
			case 'None':
				$formatter->displayableTags = array('url', 'email');			
				break;
		}
		$formatter->getTagsAgain();
		
		$parser = new XenForo_BbCode_Parser($formatter);
		
		if($options->dark_taigachat_imagemode == 'Link')
			foreach($this->_params['taigachat']['messages'] as &$message){
				$message['message'] = str_ireplace(array("[img]", "[/img]"), array("[url]", "[/url]"), $message['message']);
			}
		
		$maxid = $this->_params['taigachat']['lastrefresh'];
		foreach($this->_params['taigachat']['messages'] as &$message){
			
			if($options->dark_taigachat_bbcode == 'Full')
				$message['message'] = XenForo_Helper_String::autoLinkBbCode($message['message']);
			else 
			{
				// We don't want to parse youtube etc. urls if [media] is disabled
				$autoLinkParser = new XenForo_BbCode_Parser(XenForo_BbCode_Formatter_Base::create('Dark_TaigaChat_BbCode_Formatter_BbCode_AutoLink', false));
				$message['message'] = $autoLinkParser->render($message['message']);	
			}
				
			if($message['id'] > $maxid)
				$maxid = $message['id'];
		}
		
		XenForo_ViewPublic_Helper_Message::bbCodeWrapMessages($this->_params['taigachat']['messages'], $parser);
		
		if($options->dark_taigachat_direction)
			$this->_params['taigachat']['messages'] = array_reverse($this->_params['taigachat']['messages']);
		
		$template = $this->createTemplateObject($this->_templateName, $this->_params);
		$template->setParams($this->_params);
		$rendered = $template->render();
		   
		$rendered = preg_replace(
			'/\s+<\/(.*?)>\s+</si', 
			' </$1> <', $rendered);
		$rendered = preg_replace(
			'/\s+<(.*?)([ >])/si', 
			' <$1$2', $rendered);
		
		//$rendered = str_replace(array("\r", "\n", "\t"), "", $rendered);		
		
		$derp = XenForo_ViewRenderer_Json::jsonEncodeForOutput(array(
			"templateHtml" => $rendered,
			"reverse" => $options->dark_taigachat_direction,
			"lastrefresh" => $maxid,
		));
		
		$extraHeaders = XenForo_Application::gzipContentIfSupported($derp);
		foreach ($extraHeaders AS $extraHeader)
		{
			header("$extraHeader[0]: $extraHeader[1]", $extraHeader[2]);
		}
		
		return $derp;
	}
	
	public function renderHtml(){
		
		$options = XenForo_Application::get('options');
		
		
		$template = $this->createTemplateObject('dark_taigachat_full', $this->_params);
		$template->setParams($this->_params);
		$rendered = $template->render();

	}
}
