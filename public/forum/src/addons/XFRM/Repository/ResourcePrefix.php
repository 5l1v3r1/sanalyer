<?php

namespace XFRM\Repository;

use XF\Repository\AbstractPrefix;

class ResourcePrefix extends AbstractPrefix
{
	protected function getRegistryKey()
	{
		return 'xfrmPrefixes';
	}

	protected function getClassIdentifier()
	{
		return 'XFRM:ResourcePrefix';
	}
}