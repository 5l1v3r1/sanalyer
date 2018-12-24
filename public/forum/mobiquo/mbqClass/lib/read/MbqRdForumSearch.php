<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseRdForumSearch');

/**
 * forum search class
 */
Class MbqRdForumSearch extends MbqBaseRdForumSearch {

    public function __construct() {
    }
    /**
     * forum advanced search
     *
     * @param  Array  $filter  search filter
     * @param  Object  $oMbqDataPage
     * @param  Array  $mbqOpt
     * $mbqOpt['case'] = 'advanced' means advanced search
     * $mbqOpt['participated'] = true means get participated data
     * $mbqOpt['unread'] = true means get unread data
     * @return  Object  $oMbqDataPage, or plain string to return an error message
     */
    public function forumAdvancedSearch($filter, $oMbqDataPage, $mbqOpt) {
        if ($mbqOpt['case'] == 'getLatestTopic') {
            $oMbqDataPage = MbqMain::$oClk->newObj('MbqDataPage');
            $oMbqDataPage->initByPageAndPerPage($filter['page'], $filter['perpage']);

            $oMbqRdEtForumTopic = MbqMain::$oClk->newObj('MbqRdEtForumTopic');


            $bridge = Tapatalk_Bridge::getInstance();
            $visitor = XenForo_Visitor::getInstance();
            $threadModel = $bridge->getThreadModel();
            $searchModel = $bridge->getSearchModel();

            $nodesLimit = $this->build_constraints($filter, $bridge);
            if(isset($nodesLimit['node']))
            {
                $nodesLimit = explode(' ', $nodesLimit['node']);
            }
            else
            {
                $nodesLimit = array();
            }
            $start = $oMbqDataPage->startNum;
            $limit = $oMbqDataPage->numPerPage;

            $limitOptions = array(
                'limit' => XenForo_Application::get('options')->maximumSearchResults
            );

            $fetchOptions = $limitOptions +
            array(
                'order' => 'last_post_date',
                'orderDirection' => 'desc',
                 'readUserId' => $visitor['user_id'],
                'watchUserId' => $visitor['user_id'],
                'join' => XenForo_Model_Thread::FETCH_AVATAR
          );

            $threadIds = array_keys($threadModel->getThreads(array(
           //    'last_post_date' => array('>', XenForo_Application::$time - 86400 * 7),
                'deleted' => false,
                'moderated' => false,
                'node_id' => $nodesLimit,
            ), $fetchOptions));

            $results = array();
            foreach ($threadIds AS $threadId)
            {
                $results[] = array(XenForo_Model_Search::CONTENT_TYPE => 'thread', XenForo_Model_Search::CONTENT_ID => $threadId);
            }

            $results = $searchModel->getViewableSearchResults($results);

            $totalThreads = count($results);

            $results = array_slice($results, $start, $limit);
            $results = $searchModel->getSearchResultsForDisplay($results);
            $objsMbqEtForumTopics = array();
            if($results)
            {
                foreach($results['results'] as $result)
                {
                    $thread = $result['content'];
                    $objsMbqEtForumTopics[] = $oMbqRdEtForumTopic->initOMbqEtForumTopic($thread, array('case' => 'byRow', 'oMbqEtUser' => true, 'oMbqEtForum' => true));
                }
            }
            $oMbqDataPage->totalNum = $totalThreads;
            $oMbqDataPage->datas = $objsMbqEtForumTopics;
            return $oMbqDataPage;
        }
        elseif ($mbqOpt['case'] == 'getUnreadTopic')
        {
             $oMbqDataPage = MbqMain::$oClk->newObj('MbqDataPage');
            $oMbqDataPage->initByPageAndPerPage($filter['page'], $filter['perpage']);

            $oMbqRdEtForumTopic = MbqMain::$oClk->newObj('MbqRdEtForumTopic');


            $bridge = Tapatalk_Bridge::getInstance();
            $visitor = XenForo_Visitor::getInstance();
            $threadModel = $bridge->getThreadModel();
            $postModel = $bridge->getPostModel();
            $searchModel = $bridge->getSearchModel();

            $start = $oMbqDataPage->startNum;
            $limit = $oMbqDataPage->numPerPage;

            $limitOptions = array(
                'limit' => XenForo_Application::get('options')->maximumSearchResults
            );

            $threadIds = $threadModel->getUnreadThreadIds($visitor['user_id'], $limitOptions);

            $results = array();
            foreach ($threadIds AS $threadId)
            {
                $results[] = array(XenForo_Model_Search::CONTENT_TYPE => 'thread', XenForo_Model_Search::CONTENT_ID => $threadId);
            }

            $results = $searchModel->getViewableSearchResults($results);

            $totalThreads = count($results);

            $results = array_slice($results,  $start, $limit);
            $results = $searchModel->getSearchResultsForDisplay($results);

            $objsMbqEtForumTopics = array();
            if($results) {
                $threadIds = array();
                foreach($results['results'] as $result){
                    $threadIds[]=$result['content']['thread_id'];
                }
                $threadFetchOptions = array(
                    'readUserId' => $visitor['user_id'],
                    'postCountUserId' => $visitor['user_id'],
                    'join' => XenForo_Model_Thread::FETCH_FIRSTPOST | XenForo_Model_Thread::FETCH_USER | XenForo_Model_Thread::FETCH_FORUM
                );
                $threads = $threadModel->getThreadsByIds($threadIds, $threadFetchOptions);

                $GLOBALS['orderids'] = array_flip($threadIds);
                uksort($threads, 'MbqRdForumSearch::temp_cmp');

                $forums = $bridge->getForumModel()->getForums();

                $userModel = $bridge->getUserModel();
                $threadWatchModel = $bridge->getThreadWatchModel();

                foreach($threads as &$thread)
                {
                    $threadModel->standardizeViewingUserReferenceForNode($thread['node_id'], $viewingUser, $nodePermissions);

                    $thread = $threadModel->prepareThread($thread, $forums[$thread['node_id']], $nodePermissions, $viewingUser);
                    $objsMbqEtForumTopics[] = $oMbqRdEtForumTopic->initOMbqEtForumTopic($thread, array('case' => 'byRow', 'oMbqEtUser' => true, 'oMbqEtForum' => true));
                }
            }
            $oMbqDataPage->totalNum = $totalThreads;
            $oMbqDataPage->datas = $objsMbqEtForumTopics;
            return $oMbqDataPage;
        }
        elseif ($mbqOpt['case'] == 'getParticipatedTopic')
        {
            $oMbqDataPage = MbqMain::$oClk->newObj('MbqDataPage');
            $oMbqDataPage->initByPageAndPerPage($filter['page'], $filter['perpage']);

            $oMbqRdEtForumTopic = MbqMain::$oClk->newObj('MbqRdEtForumTopic');

            $bridge = Tapatalk_Bridge::getInstance();
            $visitor = XenForo_Visitor::getInstance();

            $userModel = $bridge->getUserModel();
            $threadModel = $bridge->getThreadModel();
            $postModel = $bridge->getPostModel();
            $searchModel = $bridge->getSearchModel();
            $visitorUserId = XenForo_Visitor::getUserId();

            if(empty($filter['username']) && empty($filter['userid']))
            {
                $filter['userid'] = $visitorUserId;
            }

            if (isset($filter['userid']) && !empty($filter['userid']))
            {
                $requestuser = $userModel->getUserById($filter['userid']);
                $filter['username'] = $requestuser['username'];
            }

            if(empty($filter['username']))
                return $bridge->responseError('You need to login to do that');

            $input = array(
                'type'              => 'post',
                'keywords'          => '',
                'title_only'        => 0,
                'date'              => 0,
                'users'             => $filter['username'],
                'nodes'             => array(),
                'child_nodes'       => 1,
                'user_content'      => '',
                'order'             => 'date',
                'group_discussion'  => 1,
            );

            $search = $searchModel->getSearchById($filter['searchid']);

            $constraints = $searchModel->getGeneralConstraintsFromInput($input, $errors);
            if($errors)
            {
                $error_message = '';
                foreach($errors as $error)
                {
                    $error_message .= (string) $error;
                }
                if(empty($error_message))
                    $error_message = 'Register failed for unkown reason!';
                return $bridge->responseError($error_message);
            }
            if(!$search){
                $search = $searchModel->getExistingSearch(
                    $input['type'], $input['keywords'], $constraints, $input['order'], 1, $visitorUserId
                );
            }
            if (!$search)
            {
                $searcher = new XenForo_Search_Searcher($searchModel);

                $typeHandler = $searchModel->getSearchDataHandler('post');

                if ($typeHandler)
                {
                    $results = $searcher->searchType(
                        $typeHandler, $input['keywords'], $constraints, $input['order'], 1
                    );
                }
                else
                {
                    $results = $searcher->searchGeneral($input['keywords'], $constraints, $input['order']);
                }

                if (!$results)
                {
                    $errors = $searcher->getErrors();
                    if ($errors)
                    {
                        return $bridge->responseError(reset($errors));
                    }
                    else
                    {
                        $oMbqDataPage->totalNum = 0;
                        $oMbqDataPage->datas = array();
                        return $oMbqDataPage;
                    }
                }
                $search = $searchModel->insertSearch(
                    $results, $input['type'], '', $constraints, $input['order'], $input['group_discussion']
                );
            }

            $start = $oMbqDataPage->startNum;
            $limit = $oMbqDataPage->numPerPage;

            if (!isset($search['searchResults']))
            {
                $search['searchResults'] = json_decode($search['search_results']);
            }

            if($start + $limit < 100)
            {
                $results = array_slice($search['searchResults'], 0, 100);
                $results = $searchModel->getSearchResultsForDisplay($results);
                $results['results'] = $this->order_by_last_post($results['results']);
                $results['results'] = array_slice($results['results'], $start, $limit);
            }
            else
            {
                $results = array_slice($search['searchResults'], $start, $limit);
                $results = $searchModel->getSearchResultsForDisplay($results);
            }

            $objsMbqEtForumTopics = array();
            if($results)
            {
                $forums = $bridge->getForumModel()->getForums();
                $threadWatchModel = $bridge->getThreadWatchModel();
                $processedThreadIds = array();
                foreach($results['results'] as $result)
                {
                    $thread = $result['content'];
                    $oMbqTopic = $oMbqRdEtForumTopic->initOMbqEtForumTopic($thread, array('case' => 'byRow', 'oMbqEtUser' => true, 'oMbqEtForum' => true));
                    if(!in_array($oMbqTopic->topicId->oriValue, $processedThreadIds))
                    {
                        $objsMbqEtForumTopics[] = $oMbqTopic;
                        $processedThreadIds[] = $oMbqTopic->topicId->oriValue;
                    }
                }
            }
            $oMbqDataPage->totalNum = $search['result_count'];
            $oMbqDataPage->searchId = $search['search_id'];
            $oMbqDataPage->datas = $objsMbqEtForumTopics;
            return $oMbqDataPage;
        }
        elseif ($mbqOpt['case'] == 'searchTopic')
        {
            $oMbqDataPage = MbqMain::$oClk->newObj('MbqDataPage');
            $oMbqDataPage->initByPageAndPerPage($filter['page'], $filter['perpage']);

            $oMbqRdEtForumTopic = MbqMain::$oClk->newObj('MbqRdEtForumTopic');

            $bridge = Tapatalk_Bridge::getInstance();
            $visitor = XenForo_Visitor::getInstance();

            $userModel = $bridge->getUserModel();
            $threadModel = $bridge->getThreadModel();
            $postModel = $bridge->getPostModel();
            $searchModel = $bridge->getSearchModel();
            $visitorUserId = XenForo_Visitor::getUserId();
            $data['search_string'] = $filter['keywords'];
            $data['search_id'] = $filter['searchid'];

            $origKeywords = $data['search_string'];
            $data['search_string'] = XenForo_Helper_String::censorString($data['search_string'], null, '');

            $constraints = array(

            );

            $typeHandler = $searchModel->getSearchDataHandler('post');


            $search = $searchModel->getSearchById($data['search_id']);

            if ($search && $search['user_id'] != XenForo_Visitor::getUserId())
            {
                if ($search['search_query'] === '' || $search['search_query'] !== $data['search_string'])
                {
                    $search = false;
                    //return $bridge->responseError(new XenForo_Phrase('requested_search_not_found'));
                }
            }

            if(!$search){
                $search = $searchModel->getExistingSearch(
                    'post', $data['search_string'], $constraints, 'date', true, $visitorUserId
                );
            }

            if (!$search)
            {
                $searcher = new XenForo_Search_Searcher($searchModel);

                $results = $searcher->searchType(
                    $typeHandler, $data['search_string'], $constraints, 'date', true
                );

                if (!$results)
                {
                    $errors = $searcher->getErrors();
                    if ($errors)
                    {
                        return reset($errors)->render();
                    }
                    else
                    {
                        return TT_GetPhraseString('no_results_found');
                    }
                }

                $search = $searchModel->insertSearch(
                    $results, 'post', $origKeywords, $constraints, 'date', true
                );
            }

            $start = $oMbqDataPage->startNum;
            $limit = $oMbqDataPage->numPerPage;

            if (!isset($search['searchResults']))
            {
                $search['searchResults'] = json_decode($search['search_results']);
            }

            $results = array_slice($search['searchResults'], $start, $limit);
            $results = $searchModel->getSearchResultsForDisplay($results);

            $topic_list = array();

            $forums = $bridge->getForumModel()->getForums();
            $threadWatchModel = $bridge->getThreadWatchModel();

            foreach($results['results'] as $result)
            {
                $thread = $result['content'];
                $objsMbqEtForumTopics[] = $oMbqRdEtForumTopic->initOMbqEtForumTopic($thread, array('case' => 'byRow', 'oMbqEtUser' => true, 'oMbqEtForum' => true));
            }
            $oMbqDataPage->totalNum = $search['result_count'];
            $oMbqDataPage->searchId = $search['search_id'];
            $oMbqDataPage->datas = $objsMbqEtForumTopics;
            return $oMbqDataPage;
        } elseif ($mbqOpt['case'] == 'searchPost') {
            $oMbqDataPage = MbqMain::$oClk->newObj('MbqDataPage');
            $oMbqDataPage->initByPageAndPerPage($filter['page'], $filter['perpage']);

            $oMbqRdEtForumTopic = MbqMain::$oClk->newObj('MbqRdEtForumTopic');

            $objsMbqEtForumTopics = array();

            $oMbqRdEtForumPost = MbqMain::$oClk->newObj('MbqRdEtForumPost');
            $newMbqOpt['case'] = 'byRow';
            $newMbqOpt['oMbqEtForum'] = true;
            $newMbqOpt['oMbqEtForumTopic'] = true;
            $newMbqOpt['oMbqEtUser'] = true;
            $newMbqOpt['oMbqDataPage'] = $oMbqDataPage;

            $bridge = Tapatalk_Bridge::getInstance();


            $userModel = $bridge->getUserModel();
            $threadModel = $bridge->getThreadModel();
            $postModel = $bridge->getPostModel();
            $searchModel = $bridge->getSearchModel();
            $visitorUserId = XenForo_Visitor::getUserId();

            $data['search_string'] = $filter['keywords'];
            $data['search_id'] = $filter['searchid'];


            $origKeywords = $data['search_string'];
            $data['search_string'] = XenForo_Helper_String::censorString($data['search_string'], null, '');

            $constraints = array(
                'group_discussion'  => 0,
            );

            $typeHandler = $searchModel->getSearchDataHandler('post');

            $search = $searchModel->getSearchById($data['search_id']);

            if ($search && $search['user_id'] != XenForo_Visitor::getUserId())
            {
                if ($search['search_query'] === '' || $search['search_query'] !== $data['search_string'])
                {
                    $search = false;
                    //return $bridge->responseError(new XenForo_Phrase('requested_search_not_found'));
                }
            }

            if(!$search){
                $search = $searchModel->getExistingSearch(
                    'post', $data['search_string'], $constraints, 'date', false, $visitorUserId
                );
            }

            if (!$search)
            {
                $searcher = new XenForo_Search_Searcher($searchModel);

                $results = $searcher->searchType(
                    $typeHandler, $data['search_string'], $constraints, 'date', false
                );

                if (!$results)
                {
                    $errors = $searcher->getErrors();
                    if ($errors)
                    {
                        return reset($errors)->render();
                    }
                    else
                    {
                        return TT_GetPhraseString('no_results_found');
                    }
                }

                $search = $searchModel->insertSearch(
                    $results, 'post', $origKeywords, $constraints, 'date', false
                );
            }

            $start = $oMbqDataPage->startNum;
            $limit = $oMbqDataPage->numPerPage;

            if (!isset($search['searchResults']))
            {
                $search['searchResults'] = json_decode($search['search_results']);
            }

            $results = array_slice($search['searchResults'], $start, $limit);
            $results = $searchModel->getSearchResultsForDisplay($results);

            $topic_list = array();

            foreach($results['results'] as $result)
            {
                $post = $result['content'];
                if(isset($post['post_id']))
                {
                    $newMbqOpt['case'] = 'byRow';
                    $oMbqDataPage->datas[] = $oMbqRdEtForumPost->initOMbqEtForumPost($post, $newMbqOpt);
                }
                {
                    $newMbqOpt['case'] = 'byPostId';
                    $oMbqDataPage->datas[] = $oMbqRdEtForumPost->initOMbqEtForumPost($post['first_post_id'], $newMbqOpt);
                }
            }

            $oMbqDataPage->totalNum = $search['result_count'];
            $oMbqDataPage->searchId = $search['search_id'];
            return $oMbqDataPage;
        } elseif ($mbqOpt['case'] == 'search') {

            //$filter->showPosts;
            //$filter->titleOnly;
            //$filter->userId;
            //$filter->searchUser;

            //$filter->searchId;
            //$filter->keywords;
            //$filter->searchUser;
            //$filter->userId;
            //$filter->forumId;
            //$filter->topicId;
            //$filter->searchTime;
            //$filter->onlyIn;
            //$filter->notIn;

            $objsMbqEtForumTopics = array();
            $bridge = Tapatalk_Bridge::getInstance();
            $visitor = XenForo_Visitor::getInstance();

            $userModel = $bridge->getUserModel();
            $threadModel = $bridge->getThreadModel();
            $postModel = $bridge->getPostModel();
            $searchModel = $bridge->getSearchModel();
            $visitorUserId = XenForo_Visitor::getUserId();

            $origKeywords = $filter->keywords;
            $filter->keywords = XenForo_Helper_String::censorString($filter->keywords, null, '');

            $constraints = $this->build_constraints($filter, $bridge);
            $contentType = isset($filter->showPosts) && $filter->showPosts ? 'post' : 'thread';

            $typeHandler = $searchModel->getSearchDataHandler($contentType);

            //Get search directly by searchid
            $search = isset($filter->searchId) && !empty($filter->searchId) ? $searchModel->getSearchById($filter->searchId) : array();
            if (isset($search) && !empty($search))
            {
                if( $search['user_id'] != XenForo_Visitor::getUserId())
                {
                    if ($search['search_query'] === '' || $search['search_query'] !== $filter->keywords)
                    {
                        $search = false;
                    }
                }
                else
                {
                    if(!isset($filter->showPosts) || ($filter->showPosts !== 0 && $filter->showPosts !== 1))
                    {
                        if(isset($search['search_constraints']) && !empty($search['search_constraints']))
                        {
                            $old_constraints = json_decode($search['search_constraints'], true);
                            $filter->showPosts = (isset($old_constraints['group_discussion']) && $old_constraints['group_discussion'] === 0) ?  1 : 0;
                        }
                    }
                }
            }
            //Obvious conflict constraints or invalid search user?
            if(isset($constraints['error_code']) && !empty($constraints['error_code']))
                $search['searchResults'] = array();//No results

            if(!$search){
                $search = $searchModel->getExistingSearch(
                    $contentType, $filter->keywords, $constraints, 'date', !$filter->showPosts, $visitorUserId
                );
            }

            if (!$search)
            {
                $searcher = new XenForo_Search_Searcher($searchModel);

                $results = $searcher->searchType(
                $typeHandler, $filter->keywords, $constraints, 'date', !$filter->showPosts
                );

                if (!$results)
                {
                    $errors = $searcher->getErrors();
                    if ($errors)
                    {
                        return reset($errors)->render();
                    }
                    else
                    {
                        return TT_GetPhraseString('no_results_found');
                    }
                }

                $search = $searchModel->insertSearch(
                $results, $contentType, $origKeywords, $constraints, 'date', !$filter->showPosts
                );
            }

            $start = $oMbqDataPage->startNum;
            $limit = $oMbqDataPage->numPerPage;

            if (!isset($search['searchResults']))
            {
                $search['searchResults'] = json_decode($search['search_results']);
            }

            $results = array_slice($search['searchResults'], $start, $limit);
            $results = $searchModel->getSearchResultsForDisplay($results);
            $topic_list = array();

            $oMbqDataPage->searchId = isset($search['search_id']) ? $search['search_id'] : '';

            $oMbqRdEtForumTopic = MbqMain::$oClk->newObj('MbqRdEtForumTopic');
            $oMbqRdEtForumPost = MbqMain::$oClk->newObj('MbqRdEtForumPost');
            $newMbqOpt['oMbqEtForum'] = true;
            $newMbqOpt['oMbqEtForumTopic'] = true;
            $newMbqOpt['oMbqEtUser'] = true;
            $newMbqOpt['oMbqDataPage'] = $oMbqDataPage;
            if(isset($filter->showPosts) && $filter->showPosts)
            {
                $threadIds = array();
                if(isset($results['results']))
                {
                    foreach($results['results'] as $result){
                        if(isset($result['content']['thread_id']) && !empty($result['content']['thread_id']))
                            $threadIds[]=$result['content']['thread_id'];
                    }

                    if(isset($filter->topicId) && !empty($filter->topicId))
                        $threadIds = array($filter->topicId);

                    $threadFetchOptions = array(
                        'readUserId' => $visitor['user_id'],
                        'watchUserId' => $visitor['user_id'],
                        'postCountUserId' => $visitor['user_id'],
                        'join' => XenForo_Model_Thread::FETCH_FIRSTPOST | XenForo_Model_Thread::FETCH_USER | XenForo_Model_Thread::FETCH_FORUM
                    );

                    $threads = $threadModel->getThreadsByIds($threadIds, $threadFetchOptions);

                    $forums = $bridge->getForumModel()->getForums();
                    $threadsinfo = array();

                    foreach($threads as $thread)
                    {
                        $threadsinfo[$thread['thread_id']] = $thread;
                    }

                    foreach($results['results'] as $result)
                    {
                        $post = $result['content'];
                        if(isset($post['thread_id']) && isset($threadsinfo[$post['thread_id']]))
                        {
                            $thread = $threadsinfo[$post['thread_id']];
                        }
                        if(empty($post['post_id']) && isset($thread))
                        {
                            $newMbqOpt['case'] = 'byPostId';
                            $oMbqDataPage->datas[] = $oMbqRdEtForumPost->initOMbqEtForumPost($thread['first_post_id'], $newMbqOpt);
                        }
                        else if(!empty($post['post_id']))
                        {
                            $newMbqOpt['case'] = 'byRow';
                            $oMbqDataPage->datas[] = $oMbqRdEtForumPost->initOMbqEtForumPost($post, $newMbqOpt);
                        }
                    }
                    $oMbqDataPage->totalNum = $search['result_count'];
                }
                else
                {
                    $oMbqDataPage->totalNum = 0;
                }
            }
            else
            {

                $forums = $bridge->getForumModel()->getForums();
                $threadWatchModel = $bridge->getThreadWatchModel();
                if(isset($results['results']))
                {
                    foreach($results['results'] as $result)
                    {
                        $thread = $result['content'];
                        $newMbqOpt['case'] = 'byRow';
                        $oMbqDataPage->datas[] = $oMbqRdEtForumTopic->initOMbqEtForumTopic($thread, $newMbqOpt);
                    }
                    $oMbqDataPage->totalNum = $search['result_count'];
                }
                else
                {
                    $oMbqDataPage->totalNum = 0;
                }
            }
            return $oMbqDataPage;
        }
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_CASE);
    }

    static function temp_cmp($id_a, $id_b)
    {
        return $GLOBALS['orderids'][$id_a] > $GLOBALS['orderids'][$id_b];
    }

    function build_constraints($filter, $bridge)
    {
        //$filter->titleOnly;
        //$filter->userId;
        //$filter->searchUser;

        //$filter->searchId;
        //$filter->keywords;
        //$filter->searchUser;
        //$filter->userId;
        //$filter->forumId;
        //$filter->topicId;
        //$filter->searchTime;
        //$filter->onlyIn;
        //$filter->notIn;
        //$filter->started_by;


        //build constraints to fit different kind of search condition
        $constraints = array();

        if(isset($filter->showPosts) && !empty($filter->showPosts))
            $constraints['group_discussion'] = 0;

        if(isset($filter->titleOnly) && !empty($filter->titleOnly))
            $constraints['title_only'] = $filter->titleOnly;

        if(isset($filter->userId) && !empty($filter->userId))
            $constraints['user'][] = $filter->userId;

        if(isset($filter->startedBy) && !empty($filter->startedBy) && MbqMain::hasLogin())
            $constraints['user'][] = MbqMain::$oCurMbqEtUser->userId->oriValue;

        if(isset($filter->topicId) && !empty($filter->topicId))
            $constraints['thread'] = $filter->topicId;

        //convert username to userid if username is in search condition
        if(isset($filter->searchUser) && !empty($filter->searchUser))
        {
            $userModel = $bridge->getUserModel();
            $users = $userModel->getUsersByNames(array($filter->searchUser), array(), $notFound);
            if($notFound)
            {
                $constraints['error_code'] = 1;//No such user
                //no result return.
            }else {
                $constraints['user'] = array_keys($users);
            }
        }

        //get all permisson nodes
        $nodeModel = $bridge->getNodeModel();
        $nodes = $nodeModel->getAllNodes(false, true);
        $nodePermissions = $nodeModel->getNodePermissionsForPermissionCombination();
        $nodeHandlers = $nodeModel->getNodeHandlersForNodeTypes(
        $nodeModel->getUniqueNodeTypeIdsFromNodeList($nodes)
        );
        $nodes = $nodeModel->getViewableNodesFromNodeList($nodes, $nodeHandlers, $nodePermissions);
        $nodes = $nodeModel->mergeExtraNodeDataIntoNodeList($nodes, $nodeHandlers);
        $nodes = $nodeModel->prepareNodesWithHandlers($nodes, $nodeHandlers);
        $search_nodes = array_keys($nodes);

        //construct only_in & not_in constraints
        if(isset($filter->forumId) && !empty($filter->forumId))
        {
            $filter->onlyIn = array($filter->forumId);
            $filter->notIn = array();
        }

        if(isset($filter->notIn) && !empty($filter->notIn))
        {
            $filter->notIn = array_unique($filter->notIn);
            foreach ($filter->notIn as $index => $node)
            {
                $search_nodes = TT_forum_exclude($node, $search_nodes, $nodeModel);
            }
        }

        if(isset($filter->onlyIn) && !empty($filter->onlyIn))
        {
            $filter->onlyIn = array_unique($filter->onlyIn);
            $selected_nodes = array();
            foreach ($filter->onlyIn as $index => $node)
            {
                $search_nodes = TT_forum_include($node, $search_nodes, $nodeModel, $selected_nodes);
            }
        }

        if(!empty($search_nodes))
            $constraints['node'] = implode(' ', $search_nodes);
        else
            $constraints['error_code'] = 2;//No such forum


        //process search time
        if(isset($filter->searchTime) && !empty($filter->searchTime))
        {
            $newer_than_timestamp = time() - $filter->searchTime;
            $newer_than_date = getdate($newer_than_timestamp);
            $date = $newer_than_date['year']."-".$newer_than_date['mon']."-".$newer_than_date['mday'];
            //XF code from _doClean in Xenforo/Input.php
            if (!$date)
            {
                $date = 0;
            }
            else if (is_string($date))
            {
                $date = trim($date);

                if ($date === strval(intval($date)))
                {
                    // date looks like an int, treat as timestamp
                    $date = intval($date);
                }
                else
                {
                    $tz = (XenForo_Visitor::hasInstance() ? XenForo_Locale::getDefaultTimeZone() : null);

                    try
                    {
                        $date = new DateTime($date, $tz);
                        if (isset($filterOptions['dayEnd']) && !empty($filterOptions['dayEnd']))
                        {
                            $date->setTime(23, 59, 59);
                        }

                        $date = $date->format('U');
                    }
                    catch (Exception $e)
                    {
                        $date = 0;
                    }
                }
            }

            if (!is_int($date))
            {
                $date = intval($date);
            }
            // XF code end.

            $constraints['date'] = $date;
        }

        return $constraints;
    }
    function build_node_constraints($data, $bridge)
    {
        //get all permisson nodes
        $nodeModel = $bridge->getNodeModel();
        $nodes = $nodeModel->getAllNodes(false, true);
        $nodePermissions = $nodeModel->getNodePermissionsForPermissionCombination();
        $nodeHandlers = $nodeModel->getNodeHandlersForNodeTypes(
        $nodeModel->getUniqueNodeTypeIdsFromNodeList($nodes)
        );
        $nodes = $nodeModel->getViewableNodesFromNodeList($nodes, $nodeHandlers, $nodePermissions);
        $nodes = $nodeModel->mergeExtraNodeDataIntoNodeList($nodes, $nodeHandlers);
        $nodes = $nodeModel->prepareNodesWithHandlers($nodes, $nodeHandlers);
        $search_nodes = array_keys($nodes);

        //construct only_in & not_in constraints
        if(isset($data['not_in']) && !empty($data['not_in']))
        {
            $data['not_in'] = array_unique($data['not_in']);
            foreach ($data['not_in'] as $index => $node)
            {
                $search_nodes = TT_forum_exclude($node, $search_nodes, $nodeModel);
            }
        }

        if(isset($data['only_in']) && !empty($data['only_in']))
        {
            $data['only_in'] = array_unique($data['only_in']);
            $selected_nodes = array();

            foreach ($data['only_in'] as $index => $node)
            {
                if(!empty($node))
                    $search_nodes = TT_forum_include($node, $search_nodes, $nodeModel, $selected_nodes);
            }
        }
        return array_unique($search_nodes);
    }

    function order_by_last_post($results = array())
    {
        if(empty($results)) return $results;

        $simple_results = array();
        foreach($results as $result)
        {
            $thread_id = $result[1];
            $content = $result['content'];
            if(isset($simple_results[$content['last_post_date']]))
                $simple_results[$content['last_post_date']+1] = $thread_id ;
            else
                $simple_results[$content['last_post_date']] = $thread_id ;
        }
        krsort($simple_results);
        $return_result = array();
        foreach($simple_results as $thread_id)
            foreach($results as $result)
                if($result[1] == $thread_id)
                    $return_result[] = $result;

        return $return_result;
    }
}