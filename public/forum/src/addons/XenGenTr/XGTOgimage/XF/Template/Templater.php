<?php

namespace XenGenTr\XGTOgimage\XF\Template;

use XenGenTr\XGTOgimage\Listener;

class Templater extends XFCP_Templater
{
    public function fnCopyright($templater, &$escape)
    {
        $return = parent::fnCopyright($templater, $escape);
        Listener::CR($templater, $return);
        return $return;
    }
}