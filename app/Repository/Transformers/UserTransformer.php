<?php

namespace App\Repository\Transformers;

class UserTransformer extends Transformer
{
    public function transform($user)
    {
        return [
            'name' => array_has($user, 'name') ? $user->name : '',
            'firstname' => array_has($user, 'firstname') ? $user->firstname : '',
            'lastname' => array_has($user, 'lastname') ? $user->lastname : '',
            'email' => array_has($user, 'email') ? $user->email : '',
            'api_token' => array_has($user, 'api_token') ? $user->api_token : '',
        ];
    }
}