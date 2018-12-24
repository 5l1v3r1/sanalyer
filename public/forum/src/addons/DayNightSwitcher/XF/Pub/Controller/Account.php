<?php

namespace DayNightSwitcher\XF\Pub\Controller;

use XF\Entity\User;

class Account extends XFCP_Account
{
    protected function preferencesSaveProcess(User $visitor)
    {
        $form = parent::preferencesSaveProcess($visitor);

        $input = $this->filter([
            'option' => ['daynightswitcher_enabled' => 'bool']
        ]);

        $userOptions = $visitor->getRelationOrDefault('Option');
        $form->setupEntityInput($userOptions, $input['option']);

        return $form;
    }
}