<?php
/**
 * Created by PhpStorm.
 * User: Abdullah
 * Date: 18.11.2018
 * Time: 15:34
 */

namespace App\Handlers;


use function App\forumAuth;

class ConfigHandler
{
    public function userField()
    {
        return forumAuth()->user()->user_id;
    }
}