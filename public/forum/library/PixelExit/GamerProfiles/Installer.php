<?php
class PixelExit_GamerProfiles_Installer
{
    public static function install($existingAddOn)
    {
		if (!$existingAddOn)
		{
			$dw = XenForo_DataWriter::create('XenForo_DataWriter_UserField');
			//Steam
			$dw->set('field_id', 'steamUserProfile');
			$dw->set('display_group', 'contact');
			$dw->set('display_order', 1);
			$dw->set('field_type', 'textbox');
			$dw->set('required', 0);
			$dw->set('show_registration', 0);
			$dw->set('user_editable', 'yes');
			$dw->set('viewable_profile', '1');
			$dw->set('viewable_message', '0');
			$dw->set('max_length', 25);
			$dw->setExtraData(XenForo_DataWriter_UserField::DATA_TITLE, 'Steam');
			$dw->setExtraData(XenForo_DataWriter_UserField::DATA_DESCRIPTION, 'Enter your Steam name here to link  to your Steam stats on http://exophase.com');
			$dw->save();
			
			$dw = XenForo_DataWriter::create('XenForo_DataWriter_UserField');
			//Origin
			$dw->set('field_id', 'originUserProfile');
			$dw->set('display_group', 'contact');
			$dw->set('display_order', 2);
			$dw->set('field_type', 'textbox');
			$dw->set('required', 0);
			$dw->set('show_registration', 0);
			$dw->set('user_editable', 'yes');
			$dw->set('viewable_profile', '1');
			$dw->set('viewable_message', '0');
			$dw->set('max_length', 25);
			$dw->setExtraData(XenForo_DataWriter_UserField::DATA_TITLE, 'Origin');
			$dw->setExtraData(XenForo_DataWriter_UserField::DATA_DESCRIPTION, 'Enter your Origin ID here - This will also be used for Battlefield 4 Battlelog profile');
			$dw->save();			
			
			$dw = XenForo_DataWriter::create('XenForo_DataWriter_UserField');
			//Xbox Live
			$dw->set('field_id', 'xboxliveUserProfile');
			$dw->set('display_group', 'contact');
			$dw->set('display_order', 3);
			$dw->set('field_type', 'textbox');
			$dw->set('required', 0);
			$dw->set('show_registration', 0);
			$dw->set('user_editable', 'yes');
			$dw->set('viewable_profile', '1');
			$dw->set('viewable_message', '0');
			$dw->set('max_length', 25);
			$dw->setExtraData(XenForo_DataWriter_UserField::DATA_TITLE, 'Xbox Live');
			$dw->setExtraData(XenForo_DataWriter_UserField::DATA_DESCRIPTION, 'Enter your Xbox Live user ID here to link to your Xbox Live stats on http://exophase.com');
			$dw->save();
			
			$dw = XenForo_DataWriter::create('XenForo_DataWriter_UserField');
			//PSN
			$dw->set('field_id', 'psnUserProfile');
			$dw->set('display_group', 'contact');
			$dw->set('display_order', 4);
			$dw->set('field_type', 'textbox');
			$dw->set('required', 0);
			$dw->set('show_registration', 0);
			$dw->set('user_editable', 'yes');
			$dw->set('viewable_profile', '1');
			$dw->set('viewable_message', '0');
			$dw->set('max_length', 25);
			$dw->setExtraData(XenForo_DataWriter_UserField::DATA_TITLE, 'PSN');
			$dw->setExtraData(XenForo_DataWriter_UserField::DATA_DESCRIPTION, 'Enter your PSN ID  here to link to your Playstation stats on http://exophase.com');
			$dw->save();
			
			$dw = XenForo_DataWriter::create('XenForo_DataWriter_UserField');
			//Twitch
			$dw->set('field_id', 'twitchUserProfile');
			$dw->set('display_group', 'contact');
			$dw->set('display_order', 5);
			$dw->set('field_type', 'textbox');
			$dw->set('required', 0);
			$dw->set('show_registration', 0);
			$dw->set('user_editable', 'yes');
			$dw->set('viewable_profile', '1');
			$dw->set('viewable_message', '0');
			$dw->set('max_length', 25);
			$dw->setExtraData(XenForo_DataWriter_UserField::DATA_TITLE, 'Twitch');
			$dw->setExtraData(XenForo_DataWriter_UserField::DATA_DESCRIPTION, 'Enter your Twitch user name here to link to your channel page');
			$dw->save();
		
			$dw = XenForo_DataWriter::create('XenForo_DataWriter_UserField');
			//YouTube
			$dw->set('field_id', 'youtubeUserProfile');
			$dw->set('display_group', 'contact');
			$dw->set('display_order', 6);
			$dw->set('field_type', 'textbox');
			$dw->set('required', 0);
			$dw->set('show_registration', 0);
			$dw->set('user_editable', 'yes');
			$dw->set('viewable_profile', '1');
			$dw->set('viewable_message', '0');
			$dw->set('max_length', 25);
			$dw->setExtraData(XenForo_DataWriter_UserField::DATA_TITLE, 'Youtube');
			$dw->setExtraData(XenForo_DataWriter_UserField::DATA_DESCRIPTION, 'Enter you YouTube channel username here');
			$dw->save();
			
			$dw = XenForo_DataWriter::create('XenForo_DataWriter_UserField');
			//Minecraft
			$dw->set('field_id', 'minecraftUserProfile');
			$dw->set('display_group', 'contact');
			$dw->set('display_order', 7);
			$dw->set('field_type', 'textbox');
			$dw->set('required', 0);
			$dw->set('show_registration', 0);
			$dw->set('user_editable', 'yes');
			$dw->set('viewable_profile', '1');
			$dw->set('viewable_message', '0');
			$dw->set('max_length', 25);
			$dw->setExtraData(XenForo_DataWriter_UserField::DATA_TITLE, 'Minecraft');
			$dw->setExtraData(XenForo_DataWriter_UserField::DATA_DESCRIPTION, 'Enter your Minecraft user ID here');
			$dw->save();
			
			$dw = XenForo_DataWriter::create('XenForo_DataWriter_UserField');
			//League of Legends
			$dw->set('field_id', 'legendsUserProfile');
			$dw->set('display_group', 'contact');
			$dw->set('display_order', 8);
			$dw->set('field_type', 'textbox');
			$dw->set('required', 0);
			$dw->set('show_registration', 0);
			$dw->set('user_editable', 'yes');
			$dw->set('viewable_profile', '1');
			$dw->set('viewable_message', '0');
			$dw->set('max_length', 25);
			$dw->setExtraData(XenForo_DataWriter_UserField::DATA_TITLE, 'League of Legends');
			$dw->setExtraData(XenForo_DataWriter_UserField::DATA_DESCRIPTION, 'Enter you League of Legends summoner name');
			$dw->save();
		}
    }
	
	 public static function uninstall()
    {	
		$dw = XenForo_DataWriter::create('XenForo_DataWriter_UserField');
		$dw->setExistingData('steamUserProfile');
		$dw->delete();
		
		$dw = XenForo_DataWriter::create('XenForo_DataWriter_UserField');
		$dw->setExistingData('originUserProfile');
		$dw->delete();
		
		$dw = XenForo_DataWriter::create('XenForo_DataWriter_UserField');
		$dw->setExistingData('psnUserProfile');
		$dw->delete();
		
		$dw = XenForo_DataWriter::create('XenForo_DataWriter_UserField');
		$dw->setExistingData('xboxliveUserProfile');
		$dw->delete();
		
		$dw = XenForo_DataWriter::create('XenForo_DataWriter_UserField');
		$dw->setExistingData('twitchUserProfile');
		$dw->delete();
		
		$dw = XenForo_DataWriter::create('XenForo_DataWriter_UserField');
		$dw->setExistingData('youtubeUserProfile');
		$dw->delete();
		
		$dw = XenForo_DataWriter::create('XenForo_DataWriter_UserField');
		$dw->setExistingData('minecraftUserProfile');
		$dw->delete();
		
		$dw = XenForo_DataWriter::create('XenForo_DataWriter_UserField');
		$dw->setExistingData('legendsUserProfile');
		$dw->delete();
    }
}
