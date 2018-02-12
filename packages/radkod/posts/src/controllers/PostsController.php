<?php
namespace Radkod\Posts\Controllers;

use Radkod\Posts\Models\Posts;
use Illuminate\Routing\Controller;

class PostsController extends Controller{
    public static function home()
    {
        $products = Posts::with('category')->orderBy('updated_at','DESC')->paginate(20);
        return $products;
    }

}