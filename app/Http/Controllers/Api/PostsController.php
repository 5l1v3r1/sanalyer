<?php

namespace App\Http\Controllers\Api;

use App\Repository\Transformers\PostsTransformer;
use App\Repository\Transformers\PostTransformer;
use Radkod\Posts\Models\Posts;
use Illuminate\Http\Request;

class PostsController extends ApiController
{

    protected $postsTransformer;
    protected $postTransformer;

    public function __construct(PostsTransformer $postsTransformer, PostTransformer $postTransformer)
    {
        $this->postsTransformer = $postsTransformer;
        $this->postTransformer = $postTransformer;
    }

    public function posts(Request $request){
        $date = date('Y-m-d H:i:s');
        if($request['type']){
            $posts = Posts::where('status', 1)->where('location', '!=', 5)->where('type', $request['type'])
                ->where('created_at', '<=', $date)->orderBy('created_at', 'DESC')->paginate(10);
            $post = Posts::where('status', 1)->where('location', '!=', 5)->where('type', $request['type'])
                ->where('location', '=', 1)
                ->where('created_at', '<=', $date)->orderBy('created_at', 'DESC')->limit(5)->get();
        }else{
            $posts = Posts::where('status', 1)->where('location', '!=', 5)
                ->where('created_at', '<=', $date)->orderBy('created_at', 'DESC')->paginate(10);
            $post = Posts::where('status', 1)->where('location', '!=', 5)
                ->where('location', '=', 1)
                ->where('created_at', '<=', $date)->orderBy('created_at', 'DESC')->limit(5)->get();
        }
        return $this->respondWithPagination($posts, [
            'headline' => $this->postsTransformer->transformCollection($post->all()),
            'posts' => $this->postsTransformer->transformCollection($posts->all())
        ], 'Records Found!');
    }

    public function post(Request $request){
        $id = $request->id;
        $post = Posts::find($id);
        if($post == NULL){
            return $this->respondInternalError('Post Not Found');
        }
        return $this->respond([
            'status' => 'success',
            'status_code' => $this->getStatusCode(),
            'message' => 'Post Detail',
            'post' => $this->postTransformer->transform($post)
        ]);
    }
}
