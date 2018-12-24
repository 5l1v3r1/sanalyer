<?php

//Recent posts sidebar by borbole
class Borbole_RecentPosts_ControllerPublic extends XFCP_Borbole_RecentPosts_ControllerPublic
{
	public function actionIndex()
    {
        $parent = parent::actionIndex();

        $maxResults = XenForo_Application::get('options')->recentpostsnum;

		$recentPosts = $this->_getBorboleRecentPostsModel()->getrecentPosts($maxResults);
		
        $parent->params['recentPosts'] = $recentPosts;
		
		return $parent;
		
	}
	
	
	/**
	 * @return Borbole_RecentPosts_RecentPosts
	 */
	protected function _getBorboleRecentPostsModel()
	{
		return $this->getModelFromCache('Borbole_RecentPosts_RecentPosts');
	}
}       
