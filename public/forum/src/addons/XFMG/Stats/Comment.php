<?php

namespace XFMG\Stats;

use XF\Stats\AbstractHandler;

class Comment extends AbstractHandler
{
	public function getStatsTypes()
	{
		return [
			'xfmg_comment' => \XF::phrase('xfmg_total_comments'),
			'xfmg_comment_like' => \XF::phrase('xfmg_comment_likes')
		];
	}

	public function getData($start, $end)
	{
		$db = $this->db();

		$comments = $db->fetchPairs(
			$this->getBasicDataQuery('xf_mg_comment', 'comment_date', 'comment_state = ?'),
			[$start, $end, 'visible']
		);

		$commentLikes = $db->fetchPairs(
			$this->getBasicDataQuery('xf_liked_content', 'like_date', 'content_type = ?'),
			[$start, $end, 'xfmg_comment']
		);

		return [
			'xfmg_comment' => $comments,
			'xfmg_comment_like' => $commentLikes
		];
	}
}