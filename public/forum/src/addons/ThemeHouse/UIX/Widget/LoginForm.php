<?php

namespace ThemeHouse\UIX\Widget;

class LoginForm extends \XF\Widget\AbstractWidget
{
    public function render()
    {
        return $this->renderer('th_widget_login_uix');
    }
}