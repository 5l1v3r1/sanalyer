<?php

namespace App\Http\Controllers;

use App\User;
use function App\checkImage;
use GuzzleHttp\Client;
use Radkod\Posts\Models\Comments;
use Radkod\Posts\Models\Posts;
use Radkod\Xenforo2\XenforoBridge\Contracts\Factory as ForumUser;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use SEO;

class HomeAjaxController extends Controller
{

    public $user;

    public function __construct(ForumUser $user)
    {
        $this->user = $user;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function header()
    {
        if ($this->user->check()) {
            $user = $this->user->user();
            $userPhoto = $this->user->photo('s');
            $logout = $this->user->logout();
            return view('layouts.auth.modal.login', compact('user', 'userPhoto', 'logout'));
        } else {
            return view('layouts.auth.modal.signIn');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function commentsSend(Request $request){
        $content = $request['content'];
        $posts_id = $request['thread_id'];

        $comment = new Comments();
        if ($this->user->check()) {
            $comment->user_id = $this->user->id();
        }
        if ($this->user->check() == true && $this->user->user()->is_moderator == 1) {
            $comment->status = intval(1);
        }else{
            $comment->status = intval(0);
        }
        if (isset($request->comment_id)){
            $comment->parent_id = $request['comment_id'];
        }
        $comment->posts_id = $posts_id;
        $comment->content = $content;
        $comment->save();
        return response()->json(['status' => 'done']);
    }

    public function comments(Request $request)
    {
        $thread = $request['thread_id'];
        if (!$this->user->check()) {
            $pp = asset('/rk_content/images/noavatar.png');
        } else {
            $pp = $this->user->photo();
        }
        $commentsTotal = Comments::where('status', 1)
            ->where('posts_id', $thread)
            ->count();
        $comments = Comments::select('comments.*')->where('posts_id', $thread)
            ->where('status', 1)
            ->where('parent_id', 0)
            ->with('children')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        $userCheck = $this->user->check();
        return view('frontend.comments', compact('pp','comments','thread','commentsTotal', 'userCheck'));
    }

    public function commentsLoad(Request $request)
    {
        $thread = $request['thread_id'];
        if (!$this->user->check()) {
            $pp = asset('/rk_content/images/noavatar.png');
        } else {
            $pp = $this->user->photo();
        }

        $comments = Comments::select('comments.*')->where('posts_id', $thread)
            ->where('status', 1)
            ->where('parent_id', 0)
            ->with('children')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        $userCheck = $this->user->check();
        return view('frontend.commentsLoad', compact('pp','comments','thread', 'userCheck'));
    }

    public function favorite_check()
    {
        if (!$this->user->check()) {
            return 'false';
        } else {
            return 'true';
        }
    }

    public function favorite_check_post()
    {
        return 'İs Coming :)';
    }

    public function ping()
    {
        $client = new Client();
        $result = $client->post('http://www.google.com/ping', [
            'sitemap' => request()->url()
        ]);
        dd($result);
        return 'Done';
    }

    public function username_check(Request $request)
    {
        $name = User::where('name', $request->name)->first();

        if ($name != null) {
            return response()->json(["message" => "Geçersiz Kullanıcı Adı", "valid" => "false"]);
        } else {
            return response()->json([
                'message' => '',
                'valid' => 'true'
            ]);
        }
    }

    public function email_check(Request $request)
    {

        $email = User::where('email', $request->email)->first();

        if ($email != null) {
            return response()->json(["message" => "Geçersiz email adresi", "valid" => false]);
        } else {
            return response()->json([
                'message' => $request->email,
                'valid' => true
            ]);
        }
    }

    public function user_update(Request $request)
    {

        if (!Auth::check()) {
            return response()->json([
                'message' => "Giriş Yapınız"
            ]);
        }

        $user = User::find(Auth::user()->id);
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->biography = $request->biography;
        $user->save();
        $result = ["type" => "success", "method" => "update", "message" => "Hesap Güncellendi"];

        return $result;
    }

    public function update_photo(Request $request)
    {

        if (!Auth::check()) {
            return response()->json([
                'error_reason' => "not_supoorted_type"
            ]);
        }

        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);


        if ($validator->passes()) {


            $imageName = str_slug(Auth::user()->name) . '-' . Auth::user()->id . '.' . request()->photo->getClientOriginalExtension();
            request()->photo->move('rk_content/images/user-profile', $imageName);

            $user = User::find(Auth::user()->id);
            $user->photo = $imageName;
            $user->save();


            return response()->json(['status' => 'done']);
        }

        // return response()->json(['error_reason' => $validator->errors()->all()]);
        return response()->json([
            'error_reason' => "not_supoorted_type"
        ]);
    }

    public function get_content(Request $request)
    {
        if (!$request->filter_type) {
            $filter_type = 'all';
        } else {
            $filter_type = $request->filter_type;
        }
        $date = date('Y-m-d H:i:s');

        if ($filter_type == "all") {
            $posts = Posts::where('status', 1)->where('location', '!=', 5)->where('created_at', '<=', $date)->orderBy('created_at', 'DESC')->paginate(10);
        } else {
            if ($filter_type == 'news') {
                $type = 0;
            } elseif ($filter_type == 'video') {
                $type = 1;
            }
            $posts = Posts::where('status', 1)->where('location', '!=', 5)->where('created_at', '<=', $date)->where('type', $type)->orderBy('created_at', 'DESC')->paginate(10);
        }

        return view('frontend.inc.home_content', compact('posts'));
    }

    public function get_previous(Request $request)
    {
        $date = date('Y-m-d H:i:s');
        $id = $request->id;
        $posts = Posts::where('status', 1)->where('id', $id)->where('type', 0)->where('created_at', '<=', $date)->first();
        // $posts = Posts::where('status', 1)->where('location', '!=', 5)->where('id', $id)->where('type', 0)->where('created_at', '<=', $date)->first();
        $postDescEx = explode('----------------------', $posts->content);
        $postDesc = $postDescEx[0];
        $postContent = $postDescEx[1];
        $prev = Posts::where('status', 1)->where('location', '!=', 5)->where('type', 0)->where('created_at', '<', $posts->created_at)->where('created_at', '<=', $date)->orderBy('created_at', 'DESC')->first();
        if($prev){
            $prevDescEx = explode('----------------------', $prev->content);
            $prevDesc = $prevDescEx[0];
            $prevContent = $prevDescEx[1];
        }
        $postTag = explode(',', $posts->tag);
        SEO::setTitle($posts->title);
        SEO::setDescription($postDesc);
        SEO::setCanonical(route('show_post', $posts->full_url));
        SEO::metatags()->addKeyword($postTag);
        SEO::opengraph()->setTitle($posts->title)
            ->setDescription($postDesc)
            ->setUrl(route('show_post', $posts->full_url))
            ->addImages([checkImage($posts->image), asset("rk_content/preview.png")])
            ->setType('article')
            ->setArticle([
                'published_time' => $posts->created_at->toW3CString(),
                'modified_time' => $posts->created_at->toW3CString(),
                'author' => $posts->user->firstname . ' ' . $posts->user->lastname,
                'section' => $posts->category()->first()->title,
                'tag' => $postTag
            ]);

        return view('frontend.posts_prev', compact('posts', 'prev', 'postDesc', 'postContent', 'prevDesc', 'prevContent'));
    }

    public function ajaxThreads(){
        if(!$this->user->check()){
            alert()->error('Giriş Yapınız');
            return redirect(route('home'));
        }
       /* $post = Posts::where('author',$user->id)->orderBy('id','desc')->select(['id','title','created_at'])->get();
        return ['data'=>$post];*/
        $posts = Posts::where('author',$this->user->id())->get();

        return DataTables::of($posts)->make();
    }

    public function ajaxImageUpload(Request $request){
        $name = sha1(microtime()) . "." . $request->file('file')->getClientOriginalExtension();
        $request->file('file')->move(public_path('resimler'), $name);
        if($request['froala'] == 'true'){
            return stripslashes(response()->json(['link' => env('APP_URL').'/resimler/'.$name])->content());
        }

    }

    public function ajaxImageDelete(Request $request){

        if($this->user->user()->is_admin == 1 || $this->user->user()->is_moderator == 1){
            \File::delete([
                getcwd() . '/resimler/'. $request['name'],
            ]);

            return $request['name']. ' deleting';
        }else {
            return 'not is admin';
        }

    }

    public function ajaxLoadImages(){
        // Array of image links to return.
        $response = array();

        // Image types.
        $image_types = array(
            "image/gif",
            "image/jpeg",
            "image/pjpeg",
            "image/jpeg",
            "image/pjpeg",
            "image/png",
            "image/x-png"
        );

        $ignored = array('.', '..', '.svn', '.htaccess', '.thumbs', 'error_log');
        $files = array();
        foreach (scandir(getcwd() . '/resimler') as $file) {
            if (in_array($file, $ignored)) continue;
            /*if (in_array(mime_content_type(getcwd() . '/resimler/'.$file), $image_types)) {
                $files[$file] = filemtime(getcwd() . '/resimler/' . $file);
            }*/
            $files[$file] = filemtime(getcwd() . '/resimler/' . $file);
        }

        arsort($files);

        // $files = array_slice(array_keys($files), 0, 500);
        $files = array_keys($files);

        foreach ($files as $file){
            array_push($response, [
                'url'=>env('APP_URL').'/resimler/'.$file,
                'thumb'=>env('APP_URL').'/resimler/'.$file,
                'type'=>"image",
                /*'tag'=>$file,*/
                'name'=>$file,
            ]);
        }

        return response()->json($response);

    }

}