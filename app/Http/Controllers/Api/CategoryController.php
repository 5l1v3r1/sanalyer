<?php

namespace App\Http\Controllers\Api;

use App\Repository\Transformers\CategoriesTransformer;
use App\Repository\Transformers\PostsTransformer;
use Radkod\Posts\Models\Category;
use Radkod\Posts\Models\Posts;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{

    protected $categoriesTransformer;
    protected $postsTransformer;

    public function __construct(CategoriesTransformer $categoriesTransformer, PostsTransformer $postsTransformer)
    {
        $this->categoriesTransformer = $categoriesTransformer;
        $this->postsTransformer = $postsTransformer;
    }

    public function categories(){
        $categories = Category::all();
        return $this->respond([
            'status' => 'success',
            'status_code' => $this->getStatusCode(),
            'message' => 'Categories',
            'data' => $this->categoriesTransformer->transform($categories)
        ]);
    }

    public function category(Request $request){
        $date = date('Y-m-d H:i:s');
        $posts = Posts::where('status', 1)->where('category', $request['id'])
            ->where('location', '!=', 5)
            ->where('created_at', '<=', $date)->orderBy('created_at', 'DESC')->paginate(10);


        $post = Posts::where('status', 1)->where('category', $request['id'])
            ->where('location', '!=', 5)
            ->where('location', '=', 1)
            ->where('created_at', '<=', $date)->orderBy('created_at', 'DESC')->limit(5)->get();

        if($posts->total() == 0){
            return $this->respondInternalError('Post Not Found');
        }

        return $this->respondWithPagination(
            $posts,
            [
                'headline' => $this->postsTransformer->transformCollection($post->all()),
                'posts' => $this->postsTransformer->transformCollection($posts->all())
            ],
            "Records Found!");
    }
}
