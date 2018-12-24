<?php

//Recent posts sidebar by borbole
class Borbole_RecentPosts_RecentPosts extends XenForo_Model
{
    public function getrecentPosts($limit = 0)
    {
        $visitor = XenForo_Visitor::getInstance();

        $postModel = $this->getModelFromCache('XenForo_Model_Post');

        $exclpostforums = XenForo_Application::get('options')->borboleRpExforums;


        $conditions = array(
            'deleted' => false,
            'moderated' => false
        );

        $fetchOptions = array(
            'join' => XenForo_Model_Thread::FETCH_USER,
            'permissionCombinationId' => $visitor['permission_combination_id'],
            'readUserId' => $visitor['user_id'],
            'watchUserId' => $visitor['user_id'],
            'postCountUserId' => $visitor['user_id'],
            'order' => 'last_post_date',
            'orderDirection' => 'desc',
            'limit' => $limit,
        );


        $whereConditions = $this->getModelFromCache('XenForo_Model_Thread')->prepareThreadConditions($conditions, $fetchOptions);
        $sqlClauses = $this->getModelFromCache('XenForo_Model_Thread')->prepareThreadFetchOptions($fetchOptions);
        $limitOptions = $this->getModelFromCache('XenForo_Model_Thread')->prepareLimitFetchOptions($fetchOptions);

        if (!empty($exclpostforums))
        {
			$whereConditions .= ' AND thread.node_id NOT IN (' . $this->_getDb()->quote($exclpostforums) . ')';
        }

        $sqlClauses['joinTables'] = str_replace('(user.user_id = thread.user_id)', '(user.user_id = thread.last_post_user_id)', $sqlClauses['joinTables']);

        $threads = $this->fetchAllKeyed($this->limitQueryResults('
				SELECT thread.*
					' . $sqlClauses['selectFields'] . '
				FROM xf_thread AS thread
				' . $sqlClauses['joinTables'] . '
				WHERE ' . $whereConditions . '
				' . $sqlClauses['orderClause'] . '
			', $limitOptions['limit'], $limitOptions['offset']
        ), 'thread_id');

        foreach($threads AS $threadID => &$thread)
        {
            if ($this->getModelFromCache('XenForo_Model_Thread')->canViewThreadAndContainer($thread, $thread))
            {
                $thread = $this->getModelFromCache('XenForo_Model_Thread')->prepareThread($thread, $thread);
                $thread['canInlineMod'] = false;
            }
            else
            {
                unset($threads[$threadID]);
            }
        }

        return $threads;
    }
}
