<?php
/**
 * Created by PhpStorm.
 * User: Abdullah
 * Date: 4.12.2018
 * Time: 17:39
 */

namespace App\Adapters;


interface AuthInterface
{
    /**
     * Check a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function byCredentials(array $credentials = []);

    /**
     * Authenticate a user via the id.
     *
     * @param  mixed  $id
     * @return bool
     */
    public function byId($id);

    /**
     * Get the currently authenticated user.
     *
     * @return mixed
     */
    public function user();
}