<?php

defined('IN_MOBIQUO') or exit;

$server_param = array(

	'login' => array(
		'function'  => 'login_func',
		'signature' => array(array($xmlrpcStruct, $xmlrpcBase64, $xmlrpcBase64, $xmlrpcBoolean, $xmlrpcString),
							array($xmlrpcStruct, $xmlrpcBase64, $xmlrpcBase64)),
		'docstring' => 'login need two parameters,the first is user name(Base64), second is password(Base64).',
	),

	'sign_in' => array(
		'function'  => 'sign_in_func',
		'signature' => array(array($xmlrpcStruct,$xmlrpcString, $xmlrpcString, $xmlrpcBase64, $xmlrpcBase64,$xmlrpcBase64),
							 array($xmlrpcStruct,$xmlrpcString, $xmlrpcString, $xmlrpcBase64, $xmlrpcBase64),
							 array($xmlrpcStruct,$xmlrpcString, $xmlrpcString, $xmlrpcBase64),
							 array($xmlrpcStruct,$xmlrpcString, $xmlrpcString, $xmlrpcBase64, $xmlrpcBase64,$xmlrpcBase64,$xmlrpcStruct)),
							 array($xmlrpcStruct,$xmlrpcString, $xmlrpcString),
		'docstring' => 'login need three parameters,the first is user name(Base64), second and thrid is token and code(String).',
	),
	
	'register' => array(
		'function'  => 'register_func',
		'signature' => array(array($xmlrpcStruct, $xmlrpcBase64, $xmlrpcBase64, $xmlrpcBase64, $xmlrpcString, $xmlrpcString),
							 array($xmlrpcStruct, $xmlrpcBase64, $xmlrpcBase64, $xmlrpcBase64, $xmlrpcString, $xmlrpcString,$xmlrpcStruct),
							 array($xmlrpcStruct, $xmlrpcBase64, $xmlrpcBase64, $xmlrpcBase64),
							 array($xmlrpcStruct, $xmlrpcBase64, $xmlrpcBase64, $xmlrpcBase64,$xmlrpcStruct)),
		'docstring' => 'register need four parameters,the first is user name(Base64), second is password(Base64), third is md5(TapatalkID token), fourth is md5 of au_id join email of tapatalk id.',
	),

	'ignore_user' => array(
		'function'  => 'ignore_user_func',
		'signature' => array(array($xmlrpcStruct, $xmlrpcString, $xmlrpcInt),
							 array($xmlrpcStruct, $xmlrpcInt)),
		'docstring' => 'register need four parameters,the first is user name(Base64), second is password(Base64), third is md5(TapatalkID token), fourth is md5 of au_id join email of tapatalk id.',
	),

	'forget_password' => array(
		'function'  => 'forget_password_func',
		'signature' => array(array($xmlrpcStruct, $xmlrpcBase64, $xmlrpcString, $xmlrpcString),
							 array($xmlrpcStruct, $xmlrpcBase64)),
		'docstring' => 'forget_password need three parameters,the first is user name(Base64), second and thrid is validation token and code(String).',
	),

	'update_password' => array(
		'function'  => 'update_password_func',
		'signature' => array(array($xmlrpcStruct, $xmlrpcBase64, $xmlrpcBase64),
							 array($xmlrpcStruct, $xmlrpcBase64, $xmlrpcString, $xmlrpcString),),
		'docstring' => 'update_password need three parameters,the first is user name(Base64), second and thrid is validation token and code(String).',
	),

	'update_email' => array(
		'function'  => 'update_email_func',
		'signature' => array(array($xmlrpcStruct, $xmlrpcBase64, $xmlrpcBase64),),
		'docstring' => 'update_email need three parameters,the first is user name(Base64), second and thrid is validation token and code(String).',
	),

	'get_forum' => array(
		'function' => 'get_forum_func',
		'signature' => array(array($xmlrpcArray),
							 array($xmlrpcArray, $xmlrpcBoolean),
							 array($xmlrpcArray, $xmlrpcBoolean, $xmlrpcString)),
	),

	'get_board_stat' => array(
		'function'  => 'get_board_stat_func',
		'signature' => array(array($xmlrpcArray)),
		'docstring' => 'no need parameters for get_board_stat.',
	),

	'get_topic' => array(
		'function'  => 'get_topic_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcInt, $xmlrpcInt, $xmlrpcString),
							 array($xmlrpcArray, $xmlrpcString, $xmlrpcInt, $xmlrpcInt),
							 array($xmlrpcArray, $xmlrpcString, $xmlrpcInt),
							 array($xmlrpcArray, $xmlrpcString)),
		'docstring' => 'parameter should be array(forum id(string), start topic num(int), end topic number(int), topic type(string, "TOP" for sticky topics, "ANN" for announcement topics)',
	),

	'get_thread' => array(
		'function'  => 'get_thread_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcInt, $xmlrpcInt, $xmlrpcBoolean),
							 array($xmlrpcArray, $xmlrpcString, $xmlrpcInt, $xmlrpcInt),
							 array($xmlrpcArray, $xmlrpcString)),
		'docstring' => 'parameter should be array(topic id(string), start post number(int), end post number(int), bbcode enable(boolean))',
	),
	'get_thread_by_unread' => array(
		'function'  => 'get_thread_by_unread_func',
		'signature' => array(array($xmlrpcStruct, $xmlrpcString, $xmlrpcInt, $xmlrpcBoolean),
							 array($xmlrpcStruct, $xmlrpcString, $xmlrpcInt),
							 array($xmlrpcStruct, $xmlrpcString)),
		'docstring' => 'parameter should be)',
	),	
	'get_thread_by_post' => array(
		'function'  => 'get_thread_by_post_func',
		'signature' => array(array($xmlrpcStruct, $xmlrpcString, $xmlrpcInt, $xmlrpcBoolean),
							 array($xmlrpcStruct, $xmlrpcString, $xmlrpcInt),
							 array($xmlrpcStruct, $xmlrpcString)),
		'docstring' => 'parameter should be)',
	),
	
	'get_recommended_user' => array(
		'function'  => 'get_recommended_user_func',
		'signature' => array(array($xmlrpcStruct, $xmlrpcInt, $xmlrpcInt, $xmlrpcInt),
							 array($xmlrpcStruct, $xmlrpcInt, $xmlrpcInt),
							 array($xmlrpcStruct)),
	),

	'search_user' => array(
		'function'  => 'search_user_func',
		'signature' => array(array($xmlrpcStruct, $xmlrpcBase64, $xmlrpcInt, $xmlrpcInt),
							 array($xmlrpcStruct)),
	),
	
	'mark_conversation_unread' => array(
		'function'  => 'mark_conversation_unread_func',
		'signature' => array(array($xmlrpcStruct, $xmlrpcString),),
	),
	
	'mark_conversation_read' => array(
    	'function'  => 'mark_conversation_read_func',
    	'signature' => array(array($xmlrpcStruct, $xmlrpcString),
						     array($xmlrpcStruct)),
	),
	
	'get_raw_post' => array(
		'function'  => 'get_raw_post_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString)),
		'docstring' => 'parameter should be array(post id(string))',
	),

	'save_raw_post' => array(
		'function'  => 'save_raw_post_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcBase64, $xmlrpcBase64),
							 array($xmlrpcArray, $xmlrpcString, $xmlrpcBase64, $xmlrpcBase64, $xmlrpcBoolean),
							 array($xmlrpcArray, $xmlrpcString, $xmlrpcBase64, $xmlrpcBase64, $xmlrpcBoolean,$xmlrpcArray, $xmlrpcString),
							 array($xmlrpcArray, $xmlrpcString, $xmlrpcBase64, $xmlrpcBase64, $xmlrpcBoolean,$xmlrpcArray, $xmlrpcString, $xmlrpcBase64)),
	),

	'get_quote_post' => array(
		'function'  => 'get_quote_post_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString)),
		'docstring' => 'parameter should be array(post id(string))',
	),


	'get_user_topic' => array(
		'function'  => 'get_user_topic_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcBase64),
                             array($xmlrpcArray, $xmlrpcBase64, $xmlrpcString)),
		'docstring' => 'parameter should be array(username(string))',
	),

	'get_user_reply_post' => array(
		'function'  => 'get_user_reply_post_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcBase64),
                             array($xmlrpcArray, $xmlrpcBase64, $xmlrpcString)),
		'docstring' => 'parameter should be array(username(string))',
	),

	'get_new_topic' => array(
		'function'  => 'get_new_topic_func',
		'signature' => array(array($xmlrpcArray),
							 array($xmlrpcArray, $xmlrpcInt, $xmlrpcInt)),
		'docstring' => 'parameter should be array(start number(int), end bumber(int)) or no parameter',
	),

	'get_latest_topic' => array(
		'function'  => 'get_latest_topic_func',
		'signature' => array(array($xmlrpcArray),
							 array($xmlrpcArray, $xmlrpcInt, $xmlrpcInt),
							 array($xmlrpcArray, $xmlrpcInt, $xmlrpcInt, $xmlrpcStruct)),
		'docstring' => 'parameter should be array(start number(int), end bumber(int)) or no parameter',
	),

	'get_unread_topic' => array(
		'function'  => 'get_unread_topic_func',
		'signature' => array(array($xmlrpcArray),
							 array($xmlrpcArray, $xmlrpcInt, $xmlrpcInt)),
		'docstring' => 'parameter should be array(start number(int), end bumber(int)) or no parameter',
	),

	'get_subscribed_topic' => array(
		'function'  => 'get_subscribed_topic_func',
		'signature' => array(array($xmlrpcArray),
							 array($xmlrpcArray, $xmlrpcInt, $xmlrpcInt)),
		'docstring' => 'no need parameters for get_subscribed_topic, return first 20',
	),

	'get_subscribed_forum' => array(
		'function'  => 'get_subscribed_forum_func',
		'signature' => array(array($xmlrpcArray)),
		'docstring' => 'no need parameters for get_subscribed_forum',
	),

	'get_user_info' => array(
		'function'  => 'get_user_info_func',
		'signature' => array(array($xmlrpcStruct),
                             array($xmlrpcStruct, $xmlrpcBase64),
                             array($xmlrpcStruct, $xmlrpcBase64, $xmlrpcString)),
		'docstring' => 'parameter should be array(username(string))',
	),

	'get_config' => array(
		'function'  => 'get_config_func',
		'signature' => array(array($xmlrpcArray)),
		'docstring' => 'no need parameters for get_config',
	),

	'logout_user' => array(
		'function'  => 'logout_user_func',
		'signature' => array(array($xmlrpcArray)),
		'docstring' => 'no need parameters for logout_user',
	),

	'new_topic' => array(
		'function'  => 'new_topic_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcBase64, $xmlrpcBase64),
							 array($xmlrpcArray, $xmlrpcString, $xmlrpcBase64, $xmlrpcBase64, $xmlrpcString),
							 array($xmlrpcArray, $xmlrpcString, $xmlrpcBase64, $xmlrpcBase64, $xmlrpcString, $xmlrpcArray),
							 array($xmlrpcArray, $xmlrpcString, $xmlrpcBase64, $xmlrpcBase64, $xmlrpcString, $xmlrpcArray, $xmlrpcString)),
		'docstring' => 'parameter should be array(forum id(string), topic title(base64), topic content(base64), topic type id(string), attachments id(array), attachments group id(string))',
	),

	'reply_post' => array(
		'function'  => 'reply_post_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcBase64, $xmlrpcBase64),
							 array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcBase64, $xmlrpcBase64, $xmlrpcArray, $xmlrpcString),
							 array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcBase64, $xmlrpcBase64, $xmlrpcArray, $xmlrpcString, $xmlrpcBoolean)),
		'docstring' => 'parameter should be array(forum id(string), topic id(string), post title(base64), post content(base64), attachments id(array), attachment group id(string), bbcode enable(boolean))',
	),
	
	'reply_topic' => array(
		'function'  => 'reply_topic_func',
		'signature' => array(array($xmlrpcStruct, $xmlrpcString, $xmlrpcBase64, $xmlrpcBase64, $xmlrpcBase64, $xmlrpcString),
							 array($xmlrpcStruct, $xmlrpcString, $xmlrpcBase64, $xmlrpcBase64, $xmlrpcBase64),
							 array($xmlrpcStruct, $xmlrpcString, $xmlrpcString, $xmlrpcBase64, $xmlrpcBase64),
							 array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcBase64, $xmlrpcBase64),
							 array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcBase64, $xmlrpcBase64, $xmlrpcArray, $xmlrpcString),
							 array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcBase64, $xmlrpcBase64, $xmlrpcArray, $xmlrpcString, $xmlrpcBoolean)),
		'docstring' => 'parameter should be array(int,string,string)',
	),

	'subscribe_topic' => array(
		'function'  => 'subscribe_topic_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString),
		),
		'docstring' => 'parameter should be array(topic id(string))',
	),

	'unsubscribe_topic' => array(
		'function'  => 'unsubscribe_topic_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcInt),
							array($xmlrpcArray, $xmlrpcString),
		),
		'docstring' => 'parameter should be array(topic id(string))',
	),

	'subscribe_forum' => array(
		'function'  => 'subscribe_forum_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString)),
		'docstring' => 'parameter should be array(forum id(string))',
	),

	'unsubscribe_forum' => array(
		'function'  => 'unsubscribe_forum_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString)),
		'docstring' => 'parameter should be array(forum id(string))',
	),

	'get_inbox_stat' => array(
		'function'  => 'get_inbox_stat_func',
		'signature' => array(array($xmlrpcArray)),
		'docstring' => 'no parameter for get_inbox_stat, but need login first',
	),

	'get_conversations' => array(
		'function'  => 'get_conversations_func',
		'signature' => array(array($xmlrpcArray),
							 array($xmlrpcArray, $xmlrpcInt, $xmlrpcInt)),
		'docstring' => 'parameter should be array(start conv number(int), end conv number(int))',
	),

	'get_conversation' => array(
		'function'  => 'get_conversation_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString),
							 array($xmlrpcArray, $xmlrpcString, $xmlrpcInt, $xmlrpcInt),
							 array($xmlrpcArray, $xmlrpcString, $xmlrpcInt, $xmlrpcInt, $xmlrpcBoolean)),
		'docstring' => 'parameter should be array(conv id(string), bbcode enable(boolean))'
	),

	'get_online_users' => array(
		'function'  => 'get_online_users_func',
		'signature' => array(array($xmlrpcArray),
		                     array($xmlrpcArray, $xmlrpcInt),
		                     array($xmlrpcArray, $xmlrpcInt, $xmlrpcInt),
							 array($xmlrpcArray, $xmlrpcInt, $xmlrpcInt, $xmlrpcString),
							 array($xmlrpcArray, $xmlrpcInt, $xmlrpcInt, $xmlrpcString, $xmlrpcString)),
		'docstring' => 'no parameter',
	),

	'mark_all_as_read' => array(
		'function'  => 'mark_all_as_read_func',
		'signature' => array(array($xmlrpcArray),
							 array($xmlrpcArray, $xmlrpcString)),
		'docstring' => 'parameter should be array(forum id(string)) or null',
	),

	'search' => array(
		'function' => 'search_func',
		'signature' => array(array($xmlrpcStruct, $xmlrpcStruct)),
	),

	'search_topic' => array(
		'function'  => 'search_topic_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcBase64, $xmlrpcInt, $xmlrpcInt, $xmlrpcString),
							 array($xmlrpcArray, $xmlrpcBase64, $xmlrpcInt, $xmlrpcInt),
							 array($xmlrpcArray, $xmlrpcBase64)),
		'docstring' => 'parameter should be array(search key words(base64),start number(int), end number(int), search id(string))',
	),

	'search_post' => array(
		'function'  => 'search_post_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcBase64, $xmlrpcInt, $xmlrpcInt, $xmlrpcString),
							 array($xmlrpcArray, $xmlrpcBase64, $xmlrpcInt, $xmlrpcInt),
							 array($xmlrpcArray, $xmlrpcBase64)),
		'docstring' => 'parameter should be array(search key words(base64),start number(int), end number(int), search id(string))',
	),

	'get_participated_topic' => array(
		'function'  => 'get_participated_topic_func',
		'signature' => array(array($xmlrpcArray),
							 array($xmlrpcArray, $xmlrpcBase64),
							 array($xmlrpcArray, $xmlrpcInt, $xmlrpcInt),
							 array($xmlrpcArray, $xmlrpcBase64, $xmlrpcInt, $xmlrpcInt),
							 array($xmlrpcArray, $xmlrpcBase64, $xmlrpcInt, $xmlrpcInt, $xmlrpcString),
							 array($xmlrpcArray, $xmlrpcBase64, $xmlrpcInt, $xmlrpcInt, $xmlrpcString, $xmlrpcString)),
		'docstring' => 'parameter should be array(username(base64), start number(int), end number(int))',
	),

	'login_forum' => array(
		'function'  => 'login_forum_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcBase64)),
		'docstring' => 'parameter should be arrsy(forum id(string), password(base64))',
	),

	'invite_participant' => array(
		'function'  => 'invite_participant_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcArray, $xmlrpcString),
							 array($xmlrpcArray, $xmlrpcArray, $xmlrpcString, $xmlrpcBase64)),
		'docstring' => '',
	),

	'new_conversation' => array(
		'function'  => 'new_conversation_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcArray, $xmlrpcString),
							 array($xmlrpcArray, $xmlrpcArray, $xmlrpcBase64, $xmlrpcBase64),
							 array($xmlrpcArray, $xmlrpcArray, $xmlrpcBase64, $xmlrpcBase64,$xmlrpcArray,$xmlrpcString)),
		'docstring' => '',
	),

	'reply_conversation' => array(
		'function'  => 'reply_conversation_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcBase64),
							 array($xmlrpcArray, $xmlrpcString, $xmlrpcBase64, $xmlrpcBase64),
							 array($xmlrpcArray, $xmlrpcString, $xmlrpcBase64, $xmlrpcBase64, $xmlrpcArray, $xmlrpcString)),
		'docstring' => '',
	),

	'get_quote_conversation' => array(
		'function'  => 'get_quote_conversation_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString)),
		'docstring' => '',
	),

	'delete_conversation' => array(
		'function'  => 'delete_conversation_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcInt)),
		'docstring' => '',
	),

	'get_dashboard' => array(
		'function'  => 'get_dashboard_func',
		'signature' => array(array($xmlrpcArray),
							array($xmlrpcArray, $xmlrpcBoolean)),
		'docstring' => 'no parameters or boolean mark alerts read',
	),

	'like_post' => array(
		'function'  => 'like_post_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString)),
		'docstring' => 'int post_id to like',
	),
	'unlike_post' => array(
		'function'  => 'unlike_post_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString)),
		'docstring' => 'int post_id to unlike',
	),
	
	'report_post' => array(
		'function'  => 'report_post_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcBase64)),
		'docstring' => 'int post_id to unlike, base64 optional reason',
	),
	'report_pm' => array(
		'function'  => 'report_pm_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcBase64)),
		'docstring' => 'int msg_id to unlike, base64 optional reason',
	),


	'upload_attach' => array(
		'function' => 'upload_attach_func',
		'signature' => array(array($xmlrpcStruct)),
		'docstring' => 'authorize need two parameters,the first is user name,second is password. Both are Base64',
	),
	
	'set_avatar' => array(
		'function' => 'upload_avatar_func',
		'signature' => array(array($xmlrpcStruct)),
		'docstring' => 'authorize need two parameters,the first is user name,second is password. Both are Base64',
	),
	
	'upload_avatar' => array(
		'function' => 'upload_avatar_func',
		'signature' => array(array($xmlrpcStruct)),
		'docstring' => 'authorize need two parameters,the first is user name,second is password. Both are Base64',
	),

	'get_id_by_url' => array(
		'function'  => 'get_id_by_url_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString)),
		'docstring' => 'string url',
	),
	
	'authorize_user' => array(
		'function'  => 'authorize_user_func',
		'signature' => array(
			array($xmlrpcArray, $xmlrpcBase64, $xmlrpcString),
			array($xmlrpcArray, $xmlrpcBase64, $xmlrpcBase64),
		),
		'docstring' => 'b64 username, b64 password',
	),
	
	'remove_attachment' => array(
		'function'  => 'remove_attachment_func',
		'signature' => array(array($xmlrpcStruct, $xmlrpcString, $xmlrpcString, $xmlrpcString),
							 array($xmlrpcStruct, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
		'docstring' => 'parameter should be',
	),

	// below part is for moderation functions
	'm_stick_topic' => array(
		'function'  => 'm_stick_topic_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcInt)),
		'docstring' => '',
	),

	'm_close_topic' => array(
		'function'  => 'm_close_topic_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcInt)),
		'docstring' => '',
	),

	'm_delete_topic' => array(
		'function'  => 'm_delete_topic_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcInt, $xmlrpcBase64),
							array($xmlrpcArray, $xmlrpcString, $xmlrpcInt)),
		'docstring' => '',
	),

	'm_delete_post' => array(
		'function'  => 'm_delete_post_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcInt, $xmlrpcBase64)),
                            array($xmlrpcArray, $xmlrpcString, $xmlrpcInt),
                            array($xmlrpcArray, $xmlrpcString),
		'docstring' => '',
	),

	'm_undelete_topic' => array(
		'function'  => 'm_undelete_topic_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcBase64)),
		'docstring' => '',
	),

	'm_undelete_post' => array(
		'function'  => 'm_undelete_post_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcBase64)),
		'docstring' => '',
	),

	'm_delete_post_by_user' => array(
		'function'  => 'm_delete_post_by_user_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcBase64)),
		'docstring' => '',
	),

	'm_move_topic' => array(
		'function'  => 'm_move_topic_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString)),
		'docstring' => '',
	),

	'm_rename_topic' => array(
		'function'  => 'm_rename_topic_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcBase64, $xmlrpcString),
							 array($xmlrpcArray, $xmlrpcString, $xmlrpcBase64)),
		'docstring' => '',
	),

	'm_move_post' => array(
		'function'  => 'm_move_post_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcBase64, $xmlrpcString)),
		'docstring' => '',
	),

	'm_merge_topic' => array(
		'function'  => 'm_merge_topic_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString),
							 array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcBoolean)),
		'docstring' => '',
	),

	'm_get_moderate_topic' => array(
		'function'  => 'm_get_moderate_topic_func',
		'signature' => array(array($xmlrpcArray),
							 array($xmlrpcArray, $xmlrpcInt),
							 array($xmlrpcArray, $xmlrpcInt, $xmlrpcInt),
							 array($xmlrpcArray, $xmlrpcInt, $xmlrpcInt, $xmlrpcInt)),
		'docstring' => '',
	),

	'm_get_moderate_post' => array(
		'function'  => 'm_get_moderate_post_func',
		'signature' => array(array($xmlrpcArray),
							 array($xmlrpcArray, $xmlrpcInt),
							 array($xmlrpcArray, $xmlrpcInt, $xmlrpcInt),
							 array($xmlrpcArray, $xmlrpcInt, $xmlrpcInt, $xmlrpcInt)),
		'docstring' => '',
	),

	'm_approve_topic' => array(
		'function'  => 'm_approve_topic_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcInt)),
		'docstring' => '',
	),

	'm_approve_post' => array(
		'function'  => 'm_approve_post_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcInt)),
		'docstring' => '',
	),

	'm_ban_user' => array(
		'function'  => 'm_ban_user_func',
		'signature' => array(array($xmlrpcArray, $xmlrpcBase64, $xmlrpcInt, $xmlrpcBase64),
		                array($xmlrpcArray, $xmlrpcBase64, $xmlrpcInt, $xmlrpcBase64,$xmlrpcInt)),
		'docstring' => '',
	),

	'm_get_report_post' => array(
		'function'  => 'm_get_report_post_func',
		'signature' => array(array($xmlrpcArray),
							 array($xmlrpcArray, $xmlrpcInt, $xmlrpcInt)),
		'docstring' => '',
	),
	'update_push_status' => array(
		'function' => 'update_push_status_func',
		'signature' => array(array($xmlrpcStruct, $xmlrpcStruct),
							array($xmlrpcStruct, $xmlrpcStruct, $xmlrpcBase64, $xmlrpcBase64)),
	),
	'get_alert' => array(
		'function' => 'get_alert_func',
		'signature' => array(array($xmlrpcStruct, $xmlrpcInt, $xmlrpcInt),
							array($xmlrpcStruct, $xmlrpcInt),
							array($xmlrpcStruct)),
	),
	'prefetch_account' => array(
		'function'  => 'prefetch_account_func',
		'signature' => array(array($xmlrpcStruct, $xmlrpcBase64)),
		'docstring' => 'forget_password need three parameters,the first is user name(Base64), second and thrid is validation token and code(String).',
	),
	'm_unban_user' => array(
		'function' => 'm_unban_user_func',
		'signature' =>array(array($xmlrpcArray,$xmlrpcString)),
	),
  
	'm_close_report' => array(
		'function' => 'm_close_report_func',
		'signature' =>array(array($xmlrpcArray,$xmlrpcString)),
	),
	
	'update_signature' => array(
        'function' => 'update_signature_func',
        'signature' =>array(array($xmlrpcArray, $xmlrpcBase64),),
    ),
//    'get_topic_participants' => array(
//        'function' => 'get_topic_participants_func',
//        'signature' =>array(array($xmlrpcArray, $xmlrpcString, $xmlrpcInt),),
//    ),
    'activate_account' => array(
        'function' => 'activate_account_func',
        'signature' =>array(array($xmlrpcArray, $xmlrpcBase64, $xmlrpcString, $xmlrpcString),),
    ),
    'set_api_key' => array(
        'function' => 'set_api_key_func',
        'signature' => array(array($xmlrpcStruct)),
    ),
     'reset_push_slug' => array(
        'function' => 'reset_push_slug_func',
        'signature' => array(array($xmlrpcStruct)),
    ),
    'user_subscription' => array(
        'function' => 'user_subscription_func',
        'signature' => array(array($xmlrpcStruct)),
    ),
    'push_content_check' => array(
        'function' => 'push_content_check_func',
        'signature' => array(array($xmlrpcStruct)),
    ),
);
