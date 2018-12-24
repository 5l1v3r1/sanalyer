<?php
class Tapatalk_Listener_LoadClassMail
{
    public static function loadClassMailListener($class, &$extend)
    {
        if ($class == 'XenForo_Mail'){
            $extend[] = 'Tapatalk_Mail';
        }
    }
}