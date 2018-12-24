<?php

namespace ThemeHouse\UIX\Pub\View\UIX;

use XF\Mvc\View;

class ToggleWidth extends View
{
    public function renderJson()
    {
        return [
            'width' => $this->params['width'],
        ];
    }
}