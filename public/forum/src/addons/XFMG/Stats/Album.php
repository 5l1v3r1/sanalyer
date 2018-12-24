<?php

namespace XFMG\Stats;

use XF\Stats\AbstractHandler;

class Album extends AbstractHandler
{
	public function getStatsTypes()
	{
		return [
			'xfmg_album' => \XF::phrase('xfmg_albums'),
			'xfmg_album_like' => \XF::phrase('xfmg_album_likes'),
			'xfmg_album_rating' => \XF::phrase('xfmg_album_ratings'),
			'xfmg_album_comment' => \XF::phrase('xfmg_album_comments')
		];
	}

	public function getData($start, $end)
	{
		$db = $this->db();

		$albums = $db->fetchPairs(
			$this->getBasicDataQuery('xf_mg_album', 'create_date', 'album_state = ?'),
			[$start, $end, 'visible']
		);

		$albumLikes = $db->fetchPairs(
			$this->getBasicDataQuery('xf_liked_content', 'like_date', 'content_type = ?'),
			[$start, $end, 'xfmg_album']
		);

		$albumRatings = $db->fetchPairs(
			$this->getBasicDataQuery('xf_mg_rating', 'rating_date', 'content_type = ?'),
			[$start, $end, 'xfmg_album']
		);

		$albumComments = $db->fetchPairs(
			$this->getBasicDataQuery('xf_mg_comment', 'comment_date', 'content_type = ?'),
			[$start, $end, 'xfmg_album']
		);

		return [
			'xfmg_album' => $albums,
			'xfmg_album_like' => $albumLikes,
			'xfmg_album_rating' => $albumRatings,
			'xfmg_album_comment' => $albumComments
		];
	}
}