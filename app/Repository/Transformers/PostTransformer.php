<?php

namespace App\Repository\Transformers;

use function App\checkImage;
use function App\YoutubeID;

class PostTransformer extends Transformer{
    public function transform($post){
        $postDescEx = explode('----------------------', $post->content);
        $postDesc = $postDescEx[0];
        $postCont = $postDescEx[1];
        $postCont = str_replace('src="resimler/', 'src="'.env("APP_URL").'/resimler/', $postCont);
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
            'content' => $postCont,
            'tags' => $post->tag,
            'type' => intval($post->type),
            'views' => intval($post->views),
            'video' => $video,
            'headline' => intval($post->location),
            "categoryId" => intval($post->category),
            "categoryName" => $post->category()->first()->title,
            "author" => $post->user->fullname,
            "authorPhoto" => $post->user->profilePhoto(),
            "created_at" => $post->created_at,
            "updated_at" => $post->updated_at,
        ];
    }
}