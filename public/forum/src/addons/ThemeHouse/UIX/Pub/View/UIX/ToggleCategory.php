<?php

namespace ThemeHouse\UIX\Pub\View\UIX;

use XF\Mvc\View;

class ToggleCategory extends View
{
    public function renderJson()
    {
        return [
            'collapsed' => $this->params['collapsed'],
            'collapsedCategories' => $this->params['collapsedCategories'],
        ];
    }
}