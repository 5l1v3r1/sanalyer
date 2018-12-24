<?php

namespace DayNightSwitcher\XF\Pub\Controller;

use DayNightSwitcher\Switcher;

class Misc extends XFCP_Misc
{
    public function actionStyle()
    {
        $parentReturn = parent::actionStyle();

        $visitor = \XF::visitor();
        if (!$visitor->canChangeStyle($error)) {
            return $this->noPermission($error);
        }

        $redirect = $this->getDynamicRedirect(null, true);

        $csrfValid = true;
        if ($visitor->user_id) {
            $csrfValid = $this->validateCsrfToken($this->filter('t', 'str'));
        }

        if (!($this->request->exists('style_id') && $csrfValid) && Switcher::shouldSwitchTheme($visitor))
        {
            return $this->view('XF:Misc\Style', 'daynightswitcher_style_chooser_disabled');
        }

        return $parentReturn;
    }
}