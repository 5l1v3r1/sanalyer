<?php

namespace Truonglv\ProfileCover;

use Truonglv\ProfileCover\Observer\UserField;
use Truonglv\ProfileCover\Service\Cover;
use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;

class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;

	public function installStep1()
    {
        /** @var \XF\Entity\UserField $field */
        $field = \XF::em()->create('XF:UserField');
        $field->field_id = Cover::USER_FIELD_ID;
        $field->display_order = 999;
        $field->field_type = 'textbox';
        $field->match_type = 'none';

        $field->moderator_editable = false;
        $field->show_registration = false;
        $field->viewable_profile = false;
        $field->viewable_message = false;
        $field->user_editable = 'never';
        $field->save();

        /** @var \XF\Entity\Phrase $phrase */
        $phrase = $field->getMasterPhrase(true);
        $phrase->phrase_text = 'Profile Cover';
        $phrase->save();
    }

    public function uninstallStep1()
    {
        UserField::$allowDeleteField = true;

        /** @var \XF\Entity\UserField $field */
        $field = \XF::finder('XF:UserField')->whereId(Cover::USER_FIELD_ID)->fetchOne();
        if (!empty($field)) {
            $field->delete();
        }
    }
}