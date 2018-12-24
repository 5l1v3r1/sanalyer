<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseRdEtForum');

if (XenForo_Template_Helper_Core::styleProperty('forumIconReadPath'))
{
    $icon_read =  XenForo_Link::convertUriToAbsoluteUri(XenForo_Template_Helper_Core::styleProperty('forumIconReadPath'), true);
    $icon_unread = XenForo_Link::convertUriToAbsoluteUri(XenForo_Template_Helper_Core::styleProperty('forumIconUnreadPath'), true);
    $icon_link = XenForo_Link::convertUriToAbsoluteUri(XenForo_Template_Helper_Core::styleProperty('linkIconPath'), true);
}
else
{
    $tapatalk_dir_name = XenForo_Application::get('options')->tp_directory;
    if (empty($tapatalk_dir_name)) $tapatalk_dir_name = 'mobiquo';
    $icon_read =   FORUM_ROOT.$tapatalk_dir_name.'/forum_icons/forum-read.png';
    $icon_unread = FORUM_ROOT.$tapatalk_dir_name.'/forum_icons/forum-unread.png';
    $icon_link =   FORUM_ROOT.$tapatalk_dir_name.'/forum_icons/link.png';
}


/**
 * forum read class
 */
Class MbqRdEtForum extends MbqBaseRdEtForum {



    public function __construct() {
    }

    public function makeProperty(&$oMbqEtForum, $pName, $mbqOpt = array()) {
        switch ($pName) {
            default:
                MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_PNAME . ':' . $pName . '.');
                break;
        }
    }

    public function getForumTree($return_description, $root_forum_id) {

        $bridge = Tapatalk_Bridge::getInstance();
        $nodeModel = $bridge->getNodeModel();

        $nodes = $nodeModel->getAllNodes(false, true);
        $nodePermissions = $nodeModel->getNodePermissionsForPermissionCombination();

        $nodeHandlers = $nodeModel->getNodeHandlersForNodeTypes(
            $nodeModel->getUniqueNodeTypeIdsFromNodeList($nodes)
        );

        $nodes = $nodeModel->getViewableNodesFromNodeList($nodes, $nodeHandlers, $nodePermissions);
        $nodes = $nodeModel->mergeExtraNodeDataIntoNodeList($nodes, $nodeHandlers);
        $nodes = $nodeModel->prepareNodesWithHandlers($nodes, $nodeHandlers);

        foreach($nodes as $id => $node)
        {
            if(($node['parent_node_id'] != 0 && !isset($nodes[$node['parent_node_id']])) || !$node['display_in_list'])
                unset($nodes[$id]);

            if (!isset($node['hasNew']))
            {

                $nodes[$id]['hasNew'] = $this->getNodeHasNewByChilds($nodes, $id);
            }
        }
        $done=array();
        if(isset($root_forum_id) && !empty($root_forum_id))
            $result = $this->treeBuild($root_forum_id, $nodes, $xml_nodes, $done);
        else
            $result = $this->treeBuild(0, $nodes, $xml_nodes, $done);

        if(false && $bridge->isXenResourceAvailable())
        {
            $done=array();
            $xenresourceCategoryModel = $bridge->getXenResourceCategoryModel();
            $resourceCategoryNodes = $xenresourceCategoryModel->getViewableCategories();
            $resultXenResources = $this->treeBuildResources(0, $resourceCategoryNodes, $xml_nodes, $done);
            $result = array_merge($result, $resultXenResources);
        }
        return $result;
    }
    function getNodeHasNewByChilds($nodes, $id)
    {
        $currentNode = $nodes[$id];
        if(isset($currentNode['hasNew']) && $currentNode['hasNew'])
        {
            return true;
        }
        foreach($nodes as $childId=>$childnode)
        {
            if($childnode['parent_node_id'] == $id)
            {
                if($childnode['hasNew'])
                {
                    return true;
                }
                else
                {
                    return $this->getNodeHasNewByChilds($nodes, $childId);
                }
            }
        }
        return false;
    }
    function treeBuild($parent_id, &$nodes, &$xml_nodes, &$done)
    {
        $newNodes = array();
        foreach($nodes as $id => &$node){
            // not interested in page nodes or nodes from addons etc.
            if(!isset($node['node_type_id']) || ($node['node_type_id'] != 'Forum' && $node['node_type_id'] != 'Category' && $node['node_type_id'] != 'LinkForum'))
                continue;

            if((string)$node['parent_node_id'] === (string)$parent_id && !array_key_exists((string)$id, $done))
            {
                $done[(string)$id] = true;
                $child_nodes = $this->treeBuild($id, $nodes, $xml_nodes, $done);
                $node2 = $this->initOMbqEtForum($node, array('case'=>'byRow'));

                if (empty($child_nodes))
                {
                    if ($node['node_type_id'] == 'Category') continue;
                }
                else
                    $node2->objsSubMbqEtForum = $child_nodes;

                $newNodes[]=$node2;

            }
        }

        return $newNodes;
    }
    function treeBuildResources($parent_id, &$nodes, &$xml_nodes, &$done)
    {
        $newNodes = array();
        foreach($nodes as $id => &$node){
            // not interested in page nodes or nodes from addons etc.
            if($node['parent_category_id'] === $parent_id && !array_key_exists($id, $done))
            {
                $done[$id] = true;
                $child_nodes = $this->treeBuildResources($id, $nodes, $xml_nodes, $done);
                $node2 = $this->initOMbqEtForum($node, array('case'=>'byResourceRow'));
                $node2->objsSubMbqEtForum = $child_nodes;
                $newNodes[]=$node2;
            }
        }

        return $newNodes;
    }
    function stillHasChildren($id, &$nodes)
    {
        foreach($nodes as $node_id => $node){
            if($node['parent_node_id'] === $id /*&& $node_id !== $id && $id !== 0*/) return true;
        }

        return false;
    }
    /**
     * get forum objs
     *
     * @param  Mixed  $var
     * @param  Array  $mbqOpt
     * $mbqOpt['case'] = 'byForumIds' means get data by forum ids.$var is the ids.
     * $mbqOpt['case'] = 'subscribed' means get subscribed data.$var is the user id.
     * @return  Array
     */
    public function getObjsMbqEtForum($var, $mbqOpt) {
        if ($mbqOpt['case'] == 'byForumIds') {
            $forumIds = $var;
            if(!is_array($forumIds))
            {
                $forumIds = array($forumIds);
            }
            $objsMbqEtForum = array();
            foreach($forumIds as $forumId)
            {
                if(mobiquo_hide_forum($forumId))
                {
                    continue;
                }
                $objsMbqEtForum[] = $this->initOMbqEtForum($forumId, array('case'=>'byForumId'));
            }
            return $objsMbqEtForum;
        } elseif ($mbqOpt['case'] == 'subscribed') {
            if(XenForo_Application::get('options')->currentVersionId < 1020070)
            {
                return array();
            }

            $bridge = Tapatalk_Bridge::getInstance();
            $visitor = XenForo_Visitor::getInstance();
            $forumWatchModel = $bridge->getForumWatchModel();
            $forumModel = $bridge->getForumModel();

            $forumsWatched = $forumWatchModel->getUserForumWatchByUser($visitor['user_id']);
            $forumids = array_keys($forumsWatched);

            $forum_list = array();
            $fetchOptions = array(
                'watchUserId' => $visitor['user_id'],
                'readUserId' => $visitor['user_id'],
            );
            $forumdetails = $forumModel->getForumsByIds($forumids, $fetchOptions);

            foreach($forumdetails as $id => $node)
            {
                // filtering hideForums
                $options = XenForo_Application::get('options');
                $hideForums = $options->hideForums;
                if (in_array($node['node_id'], $hideForums)){
                    continue;
                }

                $forum_list[] = $this->initOMbqEtForum($node, array('case'=>'byRow'));

            }
            return $forum_list;
        }
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_CASE);
    }
    function parseForumTree($parentForum, $forums)
    {
        $result = array();
        foreach($forums as $forum)
        {
            $oMbqEtForum = $this->initOMbqEtForum($forum, array('case'=>'byRow'));
            if(mobiquo_hide_forum($oMbqEtForum->forumId->oriValue))
            {
                continue;
            }
            if($forum->hasChildren())
            {
                $oMbqEtForum->objsSubMbqEtForum = $this->parseForumTree($oMbqEtForum, $forum->children());
            }
            $result[] = $oMbqEtForum;
        }
        return $result;
    }
    public function initOMbqEtForum($var, $mbqOpt)
    {
        $bridge = Tapatalk_Bridge::getInstance();
        if ($mbqOpt['case'] == 'byForumId') {
            $forumId = $var;
            if($resourceForumId = $bridge->isXenRenresourceForumId($forumId))
            {
                $xenResourceCategoryModel = $bridge->getXenResourceCategoryModel();
                try
                {
                    $forum = $xenResourceCategoryModel->getCategoryById($resourceForumId);
                }
                catch(Exception $ex)
                {
                    return null;
                }
                $objsMbqEtForum = $this->initOMbqEtForum($forum, array('case'=>'byResourceRow'));
                return $objsMbqEtForum;
            }
            else
            {
                $nodeModel = $bridge->getNodeModel();
                $ftpHelper = $bridge->getHelper('ForumThreadPost');
                try
                {
                    $forum = $ftpHelper->assertForumValidAndViewable($forumId);
                }
                catch(Exception $ex)
                {
                    return null;
                }
                $objsMbqEtForum = $this->initOMbqEtForum($forum, array('case'=>'byRow'));
                return $objsMbqEtForum;
            }
        }
        else if($mbqOpt['case'] == 'byRow')
        {
            $node = $var;
            $forum_id = $node['node_id'];
            if(MbqMain::$Cache->Exists('MbqEtForum', $forum_id))
            {
                return MbqMain::$Cache->Get('MbqEtForum',$forum_id);
            }
            $bridge = Tapatalk_Bridge::getInstance();
            $forumModel = $bridge->getForumModel();
            if (!empty($node)){
                $nodeModel = $bridge->getNodeModel();
                $nodes = array($node);
                $nodeHandlers = $nodeModel->getNodeHandlersForNodeTypes(
                       $nodeModel->getUniqueNodeTypeIdsFromNodeList($nodes)
                   );
                $nodePermissions = $nodeModel->getNodePermissionsForPermissionCombination();

                $nodes = $nodeModel->mergeExtraNodeDataIntoNodeList($nodes, $nodeHandlers);
                $nodes = $nodeModel->prepareNodesWithHandlers($nodes, $nodeHandlers);
                $node = $nodes[0];
            }
            //if (!empty($node)){
            //    $node = $forumModel->prepareForum($node);
            //}


            global $icon_read, $icon_unread, $icon_link;
            $visitor = XenForo_Visitor::getInstance();
            $bridge = Tapatalk_Bridge::getInstance();
            $forumModel = $bridge->getForumModel();
            $url = '';
            if($node['node_type_id'] == 'LinkForum'){
                $linkForumModel = XenForo_Model::create('XenForo_Model_LinkForum');
                $link = $linkForumModel->getLinkForumById($node['node_id']);
                $url = $link['link_url'];
            }

            switch ($node['node_type_id'])
            {
                case 'Category' : $nodeType = 'category'; break;
                case 'LinkForum': $nodeType = 'link'; break;
                default : $nodeType = 'forum';
            }
            $icon = tp_get_forum_icon($forum_id, $nodeType, false, ($node['hasNew'] || !$visitor['user_id']) );
            if (empty($icon)) {
                if($node['node_type_id'] == 'LinkForum') {
                    $icon = $icon_link;
                } else {
                    $icon = ($node['hasNew'] || !$visitor['user_id']) ? $icon_unread : $icon_read;
                }
            }
            $subscriptionEmail = false;
            if($nodeType == 'forum' && !(XenForo_Application::get('options')->currentVersionId < 1020070))
            {
                $subscriptionStatus = $bridge->getForumWatchModel()->getUserForumWatchByForumId($visitor['user_id'], $forum_id);
                if($subscriptionStatus)
                {
                    $is_subscribed = true;
                    if($subscriptionStatus['send_email'] == 1)
                    {
                        $subscriptionEmail = true;
                    }
                }
                else
                {
                    $is_subscribed = false;
                }
                $can_subscribe = $forumModel->canWatchForum($node) && MbqMain::isActiveMember();
                $processed_roForums = array();
                $options = XenForo_Application::get('options');
                $readonlyForums = $options->readonlyForums;
                if(!empty($readonlyForums))
                {
                    foreach($readonlyForums as $forum_idstr)
                    {
                        $forum_ids = explode(',', $forum_idstr);
                        $processed_roForums = array_merge($processed_roForums, $forum_ids);
                    }
                }
                $processed_roForums = array_unique($processed_roForums);

                $can_post = $forumModel->canPostThreadInForum($node) && !in_array($forum_id, $processed_roForums);

            }
            else
            {
                $is_subscribed = false;
                $can_subscribe = false;
                $can_post = false;
            }

            $oMbqEtForum = MbqMain::$oClk->newObj('MbqEtForum');
            $oMbqEtForum->forumId->setOriValue($forum_id);
            $oMbqEtForum->forumName->setOriValue($node['title']);
            $oMbqEtForum->parentId->setOriValue($node['parent_node_id']);
            $oMbqEtForum->description->setOriValue($node['description']);
            $oMbqEtForum->logoUrl->setOriValue($icon);
            $oMbqEtForum->newPost->setOriValue(isset($node['hasNew']) && !empty($node['hasNew']));
            $oMbqEtForum->unreadTopicNum->setOriValue(isset($node['hasNew']) && !empty($node['hasNew']) ? $node['hasNew'] : 0);
            $oMbqEtForum->isProtected->setOriValue(false);
            $oMbqEtForum->isSubscribed->setOriValue($is_subscribed);
            if($is_subscribed)
            {
                $oMbqEtForum->subscriptionEmail->setOriValue($subscriptionEmail);
            }
            $oMbqEtForum->canSubscribe->setOriValue($can_subscribe);
            $oMbqEtForum->url->setOriValue($url);
            $oMbqEtForum->subOnly->setOriValue($node['node_type_id'] == 'Category');
            $oMbqEtForum->canPost->setOriValue($can_post);
            $oMbqEtForum->canUpload->setOriValue($forumModel->canUploadAndManageAttachment($node, $errorPhraseKey));

            $prefixModel = $bridge->_getPrefixModel();
            $prefixes_list = array();
            $prefixGroups = $prefixModel->getUsablePrefixesInForums($node['node_id']);
            if (!empty($prefixGroups))
            {
                foreach($prefixGroups as $prefixGroup)
                {
                    foreach($prefixGroup['prefixes'] as $prefix)
                    {
                        $prefixItem = array(
                            'id'  => $prefix['prefix_id'],
                            'name' => TT_get_prefix_name($prefix['prefix_id']),
                        );
                        $prefixes_list[] = $prefixItem;
                    }
                }
            }
            $oMbqEtForum->prefixes->setOriValue($prefixes_list);
            $oMbqEtForum->requirePrefix->setOriValue(isset($node['require_prefix']) && $node['require_prefix']);

            $oMbqEtForum->mbqBind = $node;
            MbqMain::$Cache->Set('MbqEtForum',$forum_id, $oMbqEtForum);
            return $oMbqEtForum;
        }
        else if($mbqOpt['case'] == 'byResourceRow')
        {
            $bridge = Tapatalk_Bridge::getInstance();
            $xenResourceCategoryModel = $bridge->getXenResourceCategoryModel();
            $node = $var;
            $forum_id = $bridge->xenResourcePrefix . $node['resource_category_id'];
            if(MbqMain::$Cache->Exists('MbqEtForum', $forum_id))
            {
                return MbqMain::$Cache->Get('MbqEtForum',$forum_id);
            }
            $bridge = Tapatalk_Bridge::getInstance();



            global $icon_read, $icon_unread, $icon_link;
            $visitor = XenForo_Visitor::getInstance();
            $bridge = Tapatalk_Bridge::getInstance();

            $is_subscribed = $bridge->getXenResourceCategoryWatchModel()->getUserCategoryWatchByCategoryId($visitor['user_id'], $node['resource_category_id']);
            $can_subscribe = $is_subscribed ? false : $xenResourceCategoryModel->canWatchCategory($node);
            $can_post = false;

            $oMbqEtForum = MbqMain::$oClk->newObj('MbqEtForum');
            $oMbqEtForum->forumId->setOriValue($forum_id);
            $oMbqEtForum->forumName->setOriValue($node['category_title']);
            $oMbqEtForum->parentId->setOriValue($bridge->xenResourcePrefix . $node['parent_category_id']);
            $oMbqEtForum->description->setOriValue($node['category_description']);
            //$oMbqEtForum->logoUrl->setOriValue($icon);
            $oMbqEtForum->newPost->setOriValue(false);
            $oMbqEtForum->unreadTopicNum->setOriValue(0);
            $oMbqEtForum->isProtected->setOriValue(false);
            $oMbqEtForum->isSubscribed->setOriValue($is_subscribed);
            $oMbqEtForum->canSubscribe->setOriValue($can_subscribe);
            //$oMbqEtForum->url->setOriValue($url);
            $oMbqEtForum->subOnly->setOriValue(false);
            $oMbqEtForum->canPost->setOriValue(false);
            $oMbqEtForum->canUpload->setOriValue(false);

            $prefixModel = $bridge->getXenResourcePrefixModel();
            $prefixes_list = array();
            $prefixGroups = $prefixModel->getUsablePrefixesInCategories($node['resource_category_id']);
            if (!empty($prefixGroups))
            {
                foreach($prefixGroups as $prefixGroup)
                {
                    foreach($prefixGroup['prefixes'] as $prefix)
                    {
                        $prefixItem = array(
                            'id'  => $prefix['prefix_id'],
                            'name' => TT_get_prefix_name($prefix['prefix_id']),
                        );
                        $prefixes_list[] = $prefixItem;
                    }
                }
            }
            $oMbqEtForum->prefixes->setOriValue($prefixes_list);
            $oMbqEtForum->requirePrefix->setOriValue(isset($node['require_prefix']) && $node['require_prefix']);

            $oMbqEtForum->mbqBind = $node;
            MbqMain::$Cache->Set('MbqEtForum',$forum_id, $oMbqEtForum);
            return $oMbqEtForum;
        }
        return null;
    }
    /**
     * login forum
     *
     * @return Array
     */
    public function loginForum($oMbqEtForum, $password) {
        return new XenForo_Phrase('dark_passworded_forums_not_supported');
    }

    public function getUrl($oMbqEtForum)
    {
        return XenForo_Link::buildPublicLink('full:forums',$oMbqEtForum->mbqBind);
    }
}