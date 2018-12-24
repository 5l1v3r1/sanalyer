<?php

class Tapatalk_Push_Alert extends XFCP_Tapatalk_Push_Alert
{
    protected function _save()
    {
        parent::_save();
        //Tapatalk add
        $this->Tapatalk_hook();
    }

    protected function Tapatalk_hook($newData = array())
    {
        $post = !empty($newData)? $newData : $this->getMergedData();
        $post_model = $this->getModelFromCache('XenForo_Model_Post');
        $post_data = $post_model->getPostById($post['content_id']);
        $thread_model = $this->getModelFromCache('XenForo_Model_Thread');
        $thread = $thread_model->getThreadById($post_data['thread_id']);

        if ($post['action'] == 'insert' || $post['action'] == 'insert_attachment'){
            if ($thread['first_post_id'] == 0){
                $post['action'] = 'newtopic';
            }else{
                $post['action'] = 'sub';
            }
        }
        $allow_action = array(
            'watch_reply' => 'sub',
            'quote'       => 'quote',
            'tag'         => 'tag',
            'tagged'      => 'tag',
            'like'        => 'like',
            'sub'         => 'sub',
            'newtopic'    => 'newtopic'
        );
        if(isset($allow_action[$post['action']]) && !empty($allow_action[$post['action']]))
            $post['action'] = $allow_action[$post['action']];
        else 
            return;
        XenForo_Application::autoload('Tapatalk_Push_Push');
        Tapatalk_Push_Push::tapatalk_push_reply($post,$thread);
    }
    
}