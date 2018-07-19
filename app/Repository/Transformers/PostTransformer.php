<?php

namespace App\Repository\Transformers;

use function App\checkImage;
use function App\YoutubeID;
use Lullabot\AMP\AMP;

class PostTransformer extends Transformer{
    public function transform($post){
        $amp = new AMP();
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
            'type' => $post->type,
            'video' => $video,
            'views' => $post->views,
            'headline' => $post->location,
            "categoryId" => $post->category,
            "categoryName" => $post->category()->first()->title,
            "author" => $post->user->fullname,
            "authorPhoto" => $post->user->profilePhoto(),
            "created_at" => $post->created_at,
            "updated_at" => $post->updated_at,
        ];
    }
}