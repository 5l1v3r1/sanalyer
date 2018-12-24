<?php

defined('MBQ_IN_IT') or exit;
/**
 * This file is not needed by default!
 * Run this first before call MbqMain::initAppEnv() when you need!

 */
/* Please write any codes you need in the following area before call MbqMain::initAppEnv()! */
$startTime = microtime(true);

$mobiquo_root_path = dirname(__FILE__).'/';
$xf_root_path = dirname(dirname(__FILE__)).'/';
define('MOBIQUO_DIR', $mobiquo_root_path);
define('SCRIPT_ROOT', $xf_root_path);
if (DIRECTORY_SEPARATOR == '/')
{
    define('FORUM_ROOT', 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']).'/');
}
else
{
    define('FORUM_ROOT', 'http://'.$_SERVER['HTTP_HOST'].str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])).'/');
}

//fix for eu_cookie plugin
$GLOBALS['eucookie_set'] = 1;
//end of fix for eu_cookie plugin

require_once SCRIPT_ROOT.'library/XenForo/Autoloader.php';
XenForo_Autoloader::getInstance()->setupAutoloader(SCRIPT_ROOT.'library');

XenForo_Application::initialize(SCRIPT_ROOT.'library', SCRIPT_ROOT);
XenForo_Application::set('page_start_time', $startTime);

try
{
    $bridge = Tapatalk_Bridge::getInstance();
    $bridge->setAction(MbqMain::getCurrentCmd());
    $bridge->setUserParams('useragent', isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "");
    $bridge->initBasePlugin();
}
catch (XenForo_ControllerResponse_Exception $e)
{
    $controllerResponse = $e->getControllerResponse();

	if ($controllerResponse instanceof XenForo_ControllerResponse_Reroute)
	{
		$errorPhrase = $bridge->responseErrorMessage($controllerResponse);
		if(isset($errorPhrase->errorText)||!empty($errorPhrase->errorText)){
			if ($errorPhrase -> errorText instanceof XenForo_Phrase){
                MbqError::alert('', $errorPhrase->errorText->render());
			}else if (!is_array($errorPhrase->errorText)){
                MbqError::alert('', $errorPhrase->errorText);
			}else{
                MbqError::alert('', 'Unknow error');
			}
		}else {
            MbqError::alert('', 'Unknow error');
		}
	}
	else if($controllerResponse instanceof XenForo_ControllerResponse_Error)
	{
        MbqError::alert('', TT_GetPhraseString($controllerResponse->errorText));
	}
	else
	{
        MbqError::alert('', 'Unknow error');
	}
}

$visitor = XenForo_Visitor::getInstance();
$user_id = $visitor->getUserId();
date_default_timezone_set($visitor->timezone);

if($user_id != 0)
{
    $oMbqRdEtUser = MbqMain::$oClk->newObj('MbqRdEtUser');
    $oMbqRdEtUser->initOCurMbqEtUser($user_id);
}

function tp_get_forum_icon($id, $type = 'forum', $lock = false, $new = false)
{
    if (!in_array($type, array('link', 'category', 'forum')))
        $type = 'forum';

    $icon_name = $type;
    if ($type != 'link')
    {
        if ($lock) $icon_name .= '_lock';
        if ($new) $icon_name .= '_new';
    }

    $icon_map = array(
        'category_lock_new' => array('category_lock', 'category_new', 'lock_new', 'category', 'lock', 'new'),
        'category_lock'     => array('category', 'lock'),
        'category_new'      => array('category', 'new'),
        'lock_new'          => array('lock', 'new'),
        'forum_lock_new'    => array('forum_lock', 'forum_new', 'lock_new', 'forum', 'lock', 'new'),
        'forum_lock'        => array('forum', 'lock'),
        'forum_new'         => array('forum', 'new'),
        'category'          => array(),
        'forum'             => array(),
        'lock'              => array(),
        'new'               => array(),
        'link'              => array(),
    );

    $final = !isset($icon_map[$icon_name]) || empty($icon_map[$icon_name]);

    if ($url = tp_get_forum_icon_by_name($id, $icon_name, $final))
        return $url;

    foreach ($icon_map[$icon_name] as $sub_name)
    {
        $final = !isset($icon_map[$sub_name]) || empty($icon_map[$sub_name]);
        if ($url = tp_get_forum_icon_by_name($id, $sub_name, $final))
            return $url;
    }

    return '';
}

function tp_get_forum_icon_by_name($id, $name, $final)
{
    global $boarddir, $boardurl;

    $tapatalk_forum_icon_dir = './forum_icons/';
    $tapatalk_forum_icon_url = FORUM_ROOT.'mobiquo/forum_icons/';

    $filename_array = array(
    $name.'_'.$id.'.png',
    $name.'_'.$id.'.jpg',
    $id.'.png', $id.'.jpg',
    $name.'.png',
    $name.'.jpg',
    );

    foreach ($filename_array as $filename)
    {
        if (file_exists($tapatalk_forum_icon_dir.$filename))
        {
            return $tapatalk_forum_icon_url.$filename;
        }
    }

    if ($final) {
        if (file_exists($tapatalk_forum_icon_dir.'default.png'))
            return $tapatalk_forum_icon_url.'default.png';
        else if (file_exists($tapatalk_forum_icon_dir.'default.jpg'))
            return $tapatalk_forum_icon_url.'default.jpg';
    }

    return '';
}
/**
 *
 * Simulate XenForo_Helper_Criteria but as cannot initilize as Xenforo, we simly match nodes rule.
 */
function TT_pageMatchesCriteria($criteria, $node, $nodeModel)
{
    $breadCrumbs = $nodeModel->getNodeBreadCrumbs($node);
    if (!$criteria = TT_unserializeCriteria($criteria))
    {
        return true;
    }

    foreach ($criteria AS $criterion)
    {
        $data = $criterion['data'];

        switch ($criterion['rule'])
        {
            // browsing within one of the specified nodes
            case 'nodes':
                {

                    if (!isset($data['node_ids']) || empty($data['node_ids']))
                    {
                            return false; // no node ids specified
                        }
                    if(is_array($breadCrumbs) && !empty($breadCrumbs) && is_array($data['node_ids']))
                    {
                        foreach ($breadCrumbs as $parent_nodeid => $parent_node)
                        {
                            if(in_array($parent_nodeid, $data['node_ids']))
                                return true;
                        }
                        return false;
                    }
                }
                break;
        }

    }
    return true;
}

function TT_unserializeCriteria($criteria)
{
    if (!is_array($criteria))
    {
        $criteria = @unserialize($criteria);
        if (!is_array($criteria))
        {
            return array();
        }
    }

    return $criteria;
}

function TT_cutstr($string, $length)
{
    if(strlen($string) <= $length) {
        return $string;
    }

    $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);

    $strcut = '';

    $n = $tn = $noc = 0;
    while($n < strlen($string)) {

        $t = ord($string[$n]);
        if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
            $tn = 1; $n++; $noc++;
        } elseif(194 <= $t && $t <= 223) {
            $tn = 2; $n += 2; $noc += 2;
        } elseif(224 <= $t && $t <= 239) {
            $tn = 3; $n += 3; $noc += 2;
        } elseif(240 <= $t && $t <= 247) {
            $tn = 4; $n += 4; $noc += 2;
        } elseif(248 <= $t && $t <= 251) {
            $tn = 5; $n += 5; $noc += 2;
        } elseif($t == 252 || $t == 253) {
            $tn = 6; $n += 6; $noc += 2;
        } else {
            $n++;
        }

        if($noc >= $length) {
            break;
        }

    }
    if($noc > $length) {
        $n -= $tn;
    }

    $strcut = TT_wholeWordTrim($string, $n, 0, "");

    return $strcut;
}

function TT_wholeWordTrim($string, $maxLength, $offset = 0, $elipses = '...')
{
    //TODO: this may need a handler for language independence and some form of error correction for bbcode

    if ($offset)
    {
        $string = preg_replace('/^\S*\s+/s', '', utf8_substr($string, $offset));
    }

    $strLength = utf8_strlen($string);

    if ($maxLength > 0 && $strLength > $maxLength)
    {
        $string = utf8_substr($string, 0, $maxLength);
        $string = strrev(preg_replace('/^\S*\s+/s', '', strrev($string))) . $elipses;
    }

    if ($offset)
    {
        $string = $elipses . $string;
    }

    return $string;
}
function TT_get_avatar($user, $size = 'm')
{
    if (isset($user['user_id']) && !empty($user['user_id']) && ((isset($user['gravatar']) && !empty($user['gravatar'])) || (isset($user['avatar_date']) && !empty($user['avatar_date'])))){
        $paths = XenForo_Application::get('requestPaths');
        //$options = XenForo_Application::get('options');
        //$paths['fullBasePath'] = $options->boardUrl;
		return XenForo_Link::convertUriToAbsoluteUri(XenForo_Template_Helper_Core::callHelper('avatar', array($user, $size)), true, $paths);
    }else{
        if(isset($user['custom_fields']))
        {
            $customFields = unserialize($user['custom_fields']);
        }
        if(isset($customFields['tapatalk_avatar_url']))
        {
            return $customFields['tapatalk_avatar_url'];
        }
        else
        {
            return '';
        }
    }
}


function TT_get_prefix_name($id)
{
    static $prefixModel;

    if (empty($prefixModel))
    {
        $bridge = Tapatalk_Bridge::getInstance();
        $prefixModel = $bridge->_getPrefixModel();
    }

    $prefix = '';
    if (!empty($id))
    {
        $prefix = new XenForo_Phrase($prefixModel->getPrefixTitlePhraseName($id));
        $prefix = (string)$prefix;
    }

    return $prefix;
}

function TT_addNameValue($name, $value, &$list){
    $list[] = array(
        'name'  => $name,
        'value' => $value
    );
}
function TT_get_usertype_by_item($userid)
{
    $bridge = Tapatalk_Bridge::getInstance();
    $userModel = $bridge->getUserModel();
    $member = $userModel->getUserById($userid);
    $state = $member['user_state'];
    if( $member['is_banned'] == 1)
    {
        return 'banned';
    }
    else if($state == 'email_confirm' || $state == 'email_confirm_edit' || $state == 'Email invalid (bounced)')
    {
        return 'inactive';
    }
    else if($state == 'moderated')
    {
        return 'unapproved';
    }
    else if($member['is_admin'] == 1)
    {
        return 'admin';
    }
    else if($member['is_moderator'] == 1)
    {
        return 'mod';
    }
    return 'normal';
}
function TT_forum_exclude($nodeId, $allNodes, $nodeModel)
{
    if(in_array($nodeId, $allNodes))
    {
        $childNodes = $nodeModel->getChildNodesForNodeIds(array($nodeId));

        foreach($allNodes as $index => $node)
            if($node == $nodeId)
                unset($allNodes[$index]);

        foreach($childNodes as $_nodeid => $_node)
            $allNodes = TT_forum_exclude($_nodeid, $allNodes, $nodeModel);
    }

    return $allNodes;
}

function TT_forum_include($nodeId, $allNodes, $nodeModel, $selectedNodes)
{
    if(in_array($nodeId, $allNodes))
    {

        $childNodes = $nodeModel->getChildNodesForNodeIds(array($nodeId));

        if(in_array($nodeId, $allNodes) && !in_array($nodeId, $selectedNodes))
        {

            $selectedNodes[] = $nodeId;
        }

        foreach($childNodes as $_nodeid => $_node)
        {

            $selectedNodes = TT_forum_include($_nodeid, $allNodes, $nodeModel, $selectedNodes);
        }
    }

    return $selectedNodes;
}

function TT_GetPhraseString($string, $params = array())
{
    $XenForoPhrase = new XenForo_Phrase($string, $params);
    return $XenForoPhrase->render();
}
function TT_GetXenforoPhraseString(XenForo_Phrase $XenForoPhrase, $params = array())
{
    return $XenForoPhrase->render();
}

function TT_get_board_url(){
    $request = new Zend_Controller_Request_Http();
    $getScheme = $request->getScheme();
    $getHttpHost = $request->getHttpHost();
    $getBasePath = $request->getBasePath();

    if (!empty($getScheme) && !empty($getHttpHost)){
        return $getScheme . '://' . $getHttpHost . $getBasePath;
    } else {
        return XenForo_Application::get('options')->boardUrl;
    }
}
