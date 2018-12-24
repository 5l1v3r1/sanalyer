<?php

namespace ThemeHouse\UIX\Pub\View\UIX;

use XF\Mvc\View;

class Toggle extends View
{
    public function renderJson()
    {
        return [
            'collapsed' => $this->params['collapsed'],
        ];
    }
}