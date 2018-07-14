<?php

namespace App\Repository\Transformers;

use function App\checkImage;

class PostsTransformer extends Transformer{
    public function transform($post){
        $postDescEx = explode('----------------------', $post->content);
        $postDesc = $postDescEx[0];
        return [
            'id' => $post->id,
            'title' => $post->title,
            'image' => checkImage($post->image),
            'description' => $postDesc,
            'tags' => $post->tag,
            'views' => $post->views,
            'headline' => $post->location,
            "author" => $post->user->fullname,
            "authorPhoto" => $post->user->profilePhoto(),
            "created_at" => $post->created_at,
            "updated_at" => $post->updated_at,
        ];
    }
}