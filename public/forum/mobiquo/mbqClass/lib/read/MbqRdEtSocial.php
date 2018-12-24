<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseRdEtSocial');

/**
 * Social read class
 */
Class MbqRdEtSocial extends MbqBaseRdEtSocial {
    
    public function __construct() {
    }
    
    /**
     * get social objs
     *
     * @return  Array
     */
    public function getObjsMbqEtSocial($var, $mbqOpt) {
        if($mbqOpt['case'] == 'alert')
        {
            $oMbqDataPage = $mbqOpt['oMbqDataPage'];
            $alert_format = array(
        'sub'       => '%s replied to "%s"',
        'like'      => '%s liked your post in thread "%s"',
        'thank'     => '%s thanked your post in thread "%s"',
        'quote'     => '%s quoted your post in thread "%s"',
        'tag'       => '%s mentioned you in thread "%s"',
        'newtopic'  => '%s started a new thread "%s"',
        'pm'        => '%s sent you a message "%s"',
        'ann'       => '%sNew Announcement "%s"',
    );

            $bridge = Tapatalk_Bridge::getInstance();
            $visitor = XenForo_Visitor::getInstance();

            if(!$bridge->assertLoggedIn()) return;

            $start = $oMbqDataPage->startNum;
            $limit = $oMbqDataPage->numPerPage;

            $alertModel = $bridge->getAlertModel();
            $userModel = $bridge->getUserModel();
            //    $fetchOptions = array(
            //        'page'     => $page,
            //        'perPage'  => $perpage+1
            //    );
            $alertResults = $alertModel->getAlertsForUser(
                $visitor['user_id'],
                XenForo_Model_Alert::FETCH_MODE_ALL
            );
            $total_num = 0;
            // super dirty hax
            $derpView = new XenForo_ViewPublic_Base(
             new Tapatalk_ViewRenderer_HtmlInternal($bridge->getDependencies(), new Zend_Controller_Response_Http(), $bridge->_request),
             new Zend_Controller_Response_Http());
            $processedAlertNum = 0;
            
            if ($visitor['alerts_unread'])
            {
                $alertModel->markAllAlertsReadForUser($visitor['user_id']);
            }
            
            foreach($alertResults['alerts'] as $id => $alert)
            {
                $allow_action = array(
                    'insert'      => 'sub',
                    'watch_reply' => 'sub',
                    'quote'       => 'quote',
                    'tag'         => 'tag',
                    'like'        => 'like',
                    'sub'         => 'sub',
                    'insert_attachment'=> 'sub',
                );
                
                if (!isset($allow_action[$alert['action']])) 
                {
                    continue;
                }
                $alert['tp_type'] = $allow_action[$alert['action']];
                
                if (!isset($alert_format[$alert['tp_type']])) 
                {
                    continue;
                }
                $processedAlertNum++;

                if(($processedAlertNum < $start) || ($processedAlertNum - $start > $limit -1))
                {
                    $total_num ++;
                    continue;
                }
                $notif = $this->initOMbqEtSocial($alert, $mbqOpt);
                if($notif != null)
                {
                    $oMbqDataPage->datas[] = $notif;
                }
           
                $total_num ++;
            }
            //they do not return count, only num of pages so we need to play with it
            $oMbqDataPage->totalNum = $total_num;
            return $oMbqDataPage;
        }
        else
        {
            MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_CASE);
        }
    }
    
    /**
     * init one social by condition
     *
     * @return  Mixed
     */
    public function initOMbqEtSocial($var, $mbqOpt) {
        if($mbqOpt['case'] == 'alert')
        {
            $alert = $var;

            $allow_action = array(
              'insert'      => 'sub',
              'watch_reply' => 'sub',
              'quote'       => 'quote',
              'tag'         => 'tag',
              'like'        => 'like',
              'sub'         => 'sub',
              'insert_attachment'=> 'sub',
          );
            $alert_format = array(
       'sub'       => '%s replied to "%s"',
       'like'      => '%s liked your post in thread "%s"',
       'thank'     => '%s thanked your post in thread "%s"',
       'quote'     => '%s quoted your post in thread "%s"',
       'tag'       => '%s mentioned you in thread "%s"',
       'newtopic'  => '%s started a new thread "%s"',
       'pm'        => '%s sent you a message "%s"',
       'ann'       => '%sNew Announcement "%s"',
   );
            if (!isset($allow_action[$alert['action']])) 
            {
                return null;
            }
            $alert['tp_type'] = $allow_action[$alert['action']];
            
            if($alert['tp_type'] == 'sub')
                if(isset($alert['content']['first_post_id']) && isset($alert['content']['post_id']) && $alert['content']['post_id'] == $alert['content']['first_post_id'])
                    $alert['tp_type'] = 'newtopic';

            $message = sprintf($alert_format[$alert['tp_type']], $alert['user']['username'], (isset($alert['content']['title']) ? $this->basic_clean($alert['content']['title']) : ""));
            $oMbqEtAlert = MbqMain::$oClk->newObj('MbqEtAlert');
            
            $oMbqEtAlert->userId->setOriValue($alert['user']['user_id']);
            $oMbqEtAlert->username->setOriValue($alert['user']['username']);
            $oMbqEtAlert->iconUrl->setOriValue(TT_get_avatar($alert['user']));
            $oMbqEtAlert->message->setOriValue($message);
            $oMbqEtAlert->contentType->setOriValue($alert['tp_type']);
            $oMbqEtAlert->contentId->setOriValue($alert['content']['post_id']);
            $oMbqEtAlert->timestamp->setOriValue($alert['event_date']);
            return $oMbqEtAlert;
        }
        else
        {
            MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_CASE);
        }
        
    }
    function basic_clean($str)
    {
        $str = strip_tags($str);
        $str = trim($str);
        return html_entity_decode($str, ENT_QUOTES, 'UTF-8');
    }

}