<?php
namespace Radkod\Admin\Controllers;

use Radkod\Posts\Models\Comments;
use Radkod\Posts\Models\Posts;
use Illuminate\Http\Request;
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

    public function comments(Request $request){
        if($request['status'] == 0){
            $status = 0;
        }else{
            $status = 1;
        }
        $comments = Comments::where('status', $status)->orderBy('id','desc')->paginate(10);
        return view('admin.comments.index', compact('comments','status'));
    }

    public function comment_approve(Request $request){
        $comment = Comments::find($request['id']);
        if ($comment == null){
            alert()->error('Böyle bir yorum bulunamadı');
            return redirect()->back();
        }
        $comment->status = 1;
        $comment->save();
        alert()->success('Yorum Onaylandı');
        return redirect()->back();
    }

    public function comment_delete(Request $request){
        $comment = Comments::find($request['id']);
        if ($comment == null){
            alert()->error('Böyle bir yorum bulunamadı');
            return redirect()->back();
        }
        $comment->delete();
        alert()->success('Yorum Silindi');
        return redirect()->back();
    }

}