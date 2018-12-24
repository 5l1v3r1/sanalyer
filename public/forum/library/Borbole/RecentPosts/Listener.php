<?php

//Recent posts sidebar by borbole
class Borbole_RecentPosts_Listener
{
    public static function controller($class, &$extend)
    {
        if ($class == 'XenForo_ControllerPublic_Forum')
		{
			$extend[] = 'Borbole_RecentPosts_ControllerPublic';
		}
    }
}
