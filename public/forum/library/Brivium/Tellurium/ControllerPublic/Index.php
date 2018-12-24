<?php
class Brivium_Tellurium_ControllerPublic_Index extends XenForo_ControllerPublic_Abstract
{
	public function actionIndex()
	{
        $nodeModel = $this->getModelFromCache('XenForo_Model_Node');
        $forumModel = $this->getModelFromCache('XenForo_Model_Forum');

		$nodes = $nodeModel->getViewableNodeList();
		$options = array();
		$excludeForums = XenForo_Application::get('options')->BRQCT_excludeForum;
		$hideDisabledNodes = XenForo_Application::get('options')->BRQCT_hideDisabledNodes;
		$canCreateThread = false;
		foreach ($nodes AS $nodeId => $node)
		{
			$options[$nodeId] = array(
				'value' => $nodeId,
				'label' => $node['title'],
				'depth' => $node['depth'],
				'node_type_id' => $node['node_type_id'],
				'parent' => $node['parent_node_id']
			);
			if ((!empty($node['node_type_id']) && $node['node_type_id'] != 'Forum') ||!$forumModel->canPostThreadInForum($forumModel->getForumById($nodeId)))
			{
				$options[$nodeId]['disabled'] = 'disabled';
			}
			if($excludeForums){
				if (in_array($nodeId, $excludeForums))
				{
					$options[$nodeId]['disabled'] = 'disabled';
				}
			}
			if($options[$nodeId]['node_type_id'] == 'Forum' && (empty($options[$nodeId]['disabled']) || $options[$nodeId]['disabled'] != 'disabled')){
				$canCreateThread = true;
			}

			if($hideDisabledNodes && !empty($options[$nodeId]['disabled']))
			{
				unset($options[$nodeId]);
			}
		}
		if(!$nodes || !$canCreateThread){
			$this->_assertRegistrationRequired();
			return $this->responseError(new XenForo_Phrase('do_not_have_permission'));
		}
        $viewParams = array(
          'options'  => $options
        );
        return $this->responseView('Brivium_Tellurium_ViewPublic_Create', 'BRQCT_create_form', $viewParams);
	}

	public function actionBRQCT()
	{
		$forumId = $this->_input->filterSingle('node_id', XenForo_Input::UINT);
		$ftpHelper = $this->getHelper('ForumThreadPost');
		$forum = $ftpHelper->assertForumValidAndViewable($forumId);

		$viewParams = array(
			'prefixId' => $forum['default_prefix_id'],
			'prefixes' 	=> $this->_getPrefixModel()->getUsablePrefixesInForums($forumId),
			'forum'		=>$forum
		);

		return $this->responseView('Brivium_Tellurium_ViewPublic_CreateThread', 'BRQCT_quick_thread_create', $viewParams);
	}

	public function actionCreateThread(){
        $forumId = $this->_input->filterSingle('node_id', XenForo_Input::UINT);
		$ftpHelper = $this->getHelper('ForumThreadPost');
		$forum = $ftpHelper->assertForumValidAndViewable($forumId);

		$forumId = $forum['node_id'];
		return $this->responseRedirect(
			XenForo_ControllerResponse_Redirect::RESOURCE_CANONICAL_PERMANENT,
			XenForo_Link::buildPublicLink('forums/create-thread', $forum)
		);
	}

	protected function _getPrefixModel()
	{
		return $this->getModelFromCache('XenForo_Model_ThreadPrefix');
	}
}