<?php

namespace ThemeHouse\UIX\XF\Entity;

class Node extends XFCP_Node
{
    public function isCollapsed_uix()
    {
        $session = $this->app()->session();
        $collapsedNodes = $session->get('th_uix_collapsedCategories');
        if (!empty($collapsedNodes) && in_array($this->node_id, $collapsedNodes)) {
            return true;
        }

        return false;
    }
}