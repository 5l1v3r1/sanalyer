<?php

namespace XenGenTr\XGTOgimage\XF\Entity;

use XF\Mvc\Entity\Structure;

class User extends XFCP_User
{
    public function canSetOgDuzenler(&$error = null)
    {
        return (
            $this->user_id &&
            $this->hasPermission('general', 'canSetOgDuzenler')
        );
    }
}
