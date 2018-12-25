<?php

namespace XenGenTr\XGTForumistatistik\XF\Template;

use XF\App;
use XF\Language;

class Templater extends XFCP_Templater
{
	protected $displayGroupIds = [];

	public function fnUsernameClasses($templater, &$escape, $user, $includeGroupStyling = true)
	{
		$options = $this->options;

        $isAddonEnabledGlobally = \XF::options()->offsetGet('XGT_istatistik_kullanici_gruprengi');

        if(!$isAddonEnabledGlobally)
           {
              return false;
           }

		$includeGroupStyling = true;

		if (empty($user['display_style_group_id']))
		{
			$user['display_style_group_id'] = $this->getDisplayStyleGroupIdFromCache($user['user_id']);
		}
		else
		{
			$this->displayGroupIds[$user['user_id']] = $user['display_style_group_id'];
		}

		return parent::fnUsernameClasses($templater, $escape, $user, $includeGroupStyling);
	}

	protected function getDisplayStyleGroupIdFromCache($userId)
	{
		if (!isset($this->displayGroupIds[$userId]))
		{
			$displayGroupId = \XF::db()
				->fetchOne("SELECT display_style_group_id FROM xf_user WHERE user_id=?", $userId);
			$this->displayGroupIds[$userId] = $displayGroupId;
		}

		return $this->displayGroupIds[$userId];
	}
}