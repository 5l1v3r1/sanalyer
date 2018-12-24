<?php
class Brivium_QuickCreateThread_ViewPublic_Create extends XenForo_ViewPublic_Base
{
	public function renderJson()
	{
		$this->_params['editorTemplate'] = XenForo_ViewPublic_Helper_Editor::getEditorTemplate(
			$this, 'message', ''
		);
	}
}