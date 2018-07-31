<?php
namespace Radkod\Admin\Controllers;

use Radkod\Posts\Models\Posts;
use Illuminate\Routing\Controller;

class AdminController extends Controller {

    public static function home()
    {
        return view('admin.index');
    }

    public function posts(){
        $posts = Posts::orderBy('id','desc')->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

}