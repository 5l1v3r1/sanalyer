<?php

namespace XFMG\Pub\Controller;

use XF\Mvc\ParameterBag;

class AlbumComment extends AbstractComment
{
	protected function assertViewableAndCommentableContent(ParameterBag $params)
	{
		$album = $this->assertViewableAlbum($params->album_id);
		if ($album->canViewComments() && $album->canAddComment())
		{
			return $album;
		}
		else
		{
			throw $this->exception($this->noPermission());
		}
	}

	protected function getLinkPrefix()
	{
		return 'media/album-comments';
	}
}