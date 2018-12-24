<?php

/**
 *
 * @package Brivium_Credits
 */
class Brivium_StoreProduct_ChangeUserTitle_ActionHandler extends Brivium_Credits_ActionHandler_Abstract
{
	protected $_addOnId = 'BRS_ChangeUserTitle';
	protected $_addOnTitle = 'Brivium - Store Product Change User Title';

	protected $_displayOrder = 2140;

 	public function getActionId()
 	{
 		return 'BRS_ChangeUserTitle';
 	}

	public function getActionTitlePhrase()
 	{
 		return 'BRC_action_BRS_ChangeUserTitle';
 	}

	public function getDescriptionPhrase()
 	{
 		return 'BRC_action_BRS_ChangeUserTitle_explain';
 	}
}