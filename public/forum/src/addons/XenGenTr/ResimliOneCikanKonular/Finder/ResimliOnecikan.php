<?php

namespace XenGenTr\ResimliOneCikanKonular\Finder;

use XF\Mvc\Entity\Finder;

class ResimliOnecikan extends Finder
{
	public function searchTitle($match, $prefixMatch = false)
	{
		if ($match)
		{
			$this->whereOr(
				[
					$this->expression('CONVERT (%s USING utf8)', 'resimlionecikan_baslik'),
					'LIKE',
					$this->escapeLike($match, $prefixMatch ? '?%' : '%?%')
				]
			);
		}

		return $this;
	}
}