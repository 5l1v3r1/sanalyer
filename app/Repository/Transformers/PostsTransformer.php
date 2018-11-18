<?php

namespace App\Repository\Transformers;

use function App\checkImage;
use function App\YoutubeID;

class PostsTransformer extends Transformer{
    public function transform($post){
        $postDescEx = explode('----------------------', $post->content);
        $postDesc = $postDescEx[0];
        if($post->type == 1){
            $video = YoutubeID($post->video);
        }else{
            $video = NULL;
        }
        return [
            'id' => $post->id,
            'title' => $post->title,
            'image' => checkImage($post->image),
            'description' => $postDesc,
            'type' => intval($post->type),
            'views' => intval($post->views),
            'headline' => intval($post->location),
            "categoryId" => intval($post->category),
            'video' => $video,
            "categoryName" => $post->category()->first()->title,
            "author" => $post->user->fullname,
            "authorPhoto" => $post->user->profilePhoto(),
            "created_at" => $post->created_at,
            "updated_at" => $post->updated_at,
        ];
    }
}