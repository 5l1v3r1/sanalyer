<?php
/**
 * Created by PhpStorm.
 * User: Abdullah
 * Date: 4.12.2018
 * Time: 18:23
 */

namespace App\Repository\Transformers;


class CommentTransformer extends Transformer
{
    private $user;

    public function __construct(UserTransformer $user)
    {
        $this->user = $user;
    }

    public function transform($comment)
    {

        $child = null;
        if (count($comment->children->all()) > 0) {
            $child = $this->transformCollection($comment->children->all());
        }
        return [
            'id' => $comment->id,
            'user' => $comment->user_id != null ? $comment->user->username : 'Misafir',
            'posts_id' => $comment->posts_id,
            'content' => $comment->content,
            "created_at" => $comment->created_at,
            "updated_at" => $comment->updated_at,
            "children" => $child
        ];
    }
}