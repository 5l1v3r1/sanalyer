<?php

namespace ThemeHouse\UIX\Widget;

class PostThread extends \XF\Widget\AbstractWidget
{
    public function render()
    {
        return $this->renderer('th_widget_post_thread_uix');
    }
}