<?php

namespace App\Http\Controllers;

use Radkod\Posts\Models\Comments;
use function App\checkImage;
use Lullabot\AMP\AMP;
use function App\YoutubeID;
use Carbon\Carbon;
use Radkod\Posts\Models\Category;
use Radkod\Posts\Models\Posts;
use Radkod\Xenforo2\XenforoBridge\Contracts\Factory as User;
use Toolkito\Larasap\SendTo;
use Illuminate\Routing\Controller as Controller;
use SEO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomePostsController extends Controller
{

    public $user;

    /**
     * HomePostsController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function showPost($slug)
    {
        $data = explode("-", $slug);
        $id = $data[count($data) - 1];
        $date = date('Y-m-d H:i:s');
        $posts = Posts::where('id', $id)
            ->where('created_at', '<=', $date)
            ->where('type', 0)
            ->first();
        if ($posts == null){
            alert()->error('Aradıgınız içerik bulunmamakta.');
            return redirect(route('home'));
        }

        $posts->hitUpdate();

        $postDescEx = explode('----------------------', $posts->content);
        $postDesc = $postDescEx[0];
        $postContent = $postDescEx[1];
        $category = Category::where('id', $posts->category)->first();
        $prev = Posts::where('created_at', '<', $posts->created_at)
            ->where('created_at', '<=', $date)
            ->where('type', 0)
            ->where('location', '!=', 5)
            ->orderBy('created_at', 'DESC')
            ->first();
        if($prev){
            $prevDescEx = explode('----------------------', $prev->content);
            $prevDesc = $prevDescEx[0];
            $prevContent = $prevDescEx[1];
        }
        $postTag = explode(',', $posts->tag);
        SEO::setTitle($posts->title);
        SEO::setDescription($postDesc);
        SEO::setCanonical(route('show_post', $posts->full_url));
        # SEO::metatags()->addKeyword($postTag);
        SEO::opengraph()->setTitle($posts->title)
            ->setDescription($postDesc)
            ->setUrl(route('show_post', $posts->full_url))
            ->addImages([checkImage($posts->image)])
            ->setType('article')
            ->setArticle([
                'published_time' => $posts->created_at->toW3CString(),
                'modified_time' => $posts->created_at->toW3CString(),
                'author' => $posts->user->firstname . ' ' . $posts->user->lastname,
                'section' => $posts->category()->first()->title,
                'tag' => $postTag
            ]);

        return view('frontend.detail.posts', compact('posts', 'prev', 'postDesc', 'postContent', 'prevDesc', 'prevContent', 'category'));
    }

    public function showPostAMP($slug)
    {
        $amp = new AMP();
        $date = date('Y-m-d H:i:s');
        $data = explode("-", $slug);
        $id = $data[count($data) - 1];
        $posts = Posts::where('id', $id)
            ->where('created_at', '<=', $date)
            ->where('type', 0)
            ->first();
        if ($posts == null){
            alert()->error('Aradıgınız içerik bulunmamakta.');
            return redirect(route('home'));
        }
        $postDescEx = explode('----------------------', $posts->content);
        $postDesc = $postDescEx[0];
        $postContent = $postDescEx[1];
        $postContent = str_replace('src="resimler/', 'src="'.env("APP_URL").'/resimler/', $postContent);
        $posts->hitUpdate();

        $amp->loadHtml($postContent);
       /* $amp->loadHtml($postContent, ['add_stats_html_comment' => true]);*/
        $postContent = $amp->convertToAmpHtml();

        $category = Category::where('id', $posts->category)->first();
        $prev = Posts::where('created_at', '<', $posts->created_at)
            ->where('created_at', '<=', $date)
            ->where('type', 0)
            ->orderBy('created_at', 'DESC')
            ->first();
        $prevDescEx = explode('----------------------', $prev->content);
        $prevDesc = $prevDescEx[0];
        $prevContent = $prevDescEx[1];
        $postTag = explode(',', $posts->tag);
        SEO::setTitle($posts->title);
        SEO::setDescription($postDesc);
        SEO::setCanonical(route('show_post', $posts->full_url));
        # SEO::metatags()->addKeyword($postTag);
        SEO::opengraph()->setTitle($posts->title)
            ->setDescription($postDesc)
            ->setUrl(route('show_post', $posts->full_url))
            ->addImages([checkImage($posts->image)])
            ->setType('article')
            ->setArticle([
                'published_time' => $posts->created_at->toW3CString(),
                'modified_time' => $posts->created_at->toW3CString(),
                'author' => $posts->user->firstname . ' ' . $posts->user->lastname,
                'section' => $posts->category()->first()->title,
                'tag' => $postTag
            ]);

        return view('frontend.detail.posts_amp', compact('posts', 'prev', 'postDesc', 'postContent', 'prevDesc', 'prevContent', 'category'));
    }

    public function showVideo($slug)
    {
        $date = date('Y-m-d H:i:s');
        $data = explode("-", $slug);
        $id = $data[count($data) - 1];
        $posts = Posts::where('id', $id)
            ->where('created_at', '<=', $date)
            ->where('type', 1)
            ->first();
        if ($posts == null){
            alert()->error('Aradıgınız içerik bulunmamakta.');
            return redirect(route('home'));
        }
        $posts->hitUpdate();
        $postDescEx = explode('----------------------', $posts->content);
        $postDesc = $postDescEx[0];
        $postContent = $postDescEx[1];
        $prev = Posts::where('created_at', '<', $posts->created_at)
            ->where('created_at', '<=', $date)
            ->where('type', 1)
            ->orderBy('created_at', 'DESC')
            ->take(9)
            ->get();
        $postTag = explode(',', $posts->tag);
        SEO::setTitle($posts->title);
        SEO::setDescription($postDesc);
        SEO::setCanonical(route('show_video', $posts->full_url));
        # SEO::metatags()->addKeyword($postTag);
        SEO::opengraph()->setTitle($posts->title)
            ->setDescription($postDesc)
            ->setUrl(route('show_video', $posts->full_url))
            ->addImages([checkImage($posts->image)])
            ->setType('video')
            ->setArticle([
                'published_time' => $posts->created_at->toW3CString(),
                'modified_time' => $posts->created_at->toW3CString(),
                'author' => $posts->user->firstname . ' ' . $posts->user->lastname,
                'section' => $posts->category()->first()->title,
                'tag' => $postTag
            ])
            ->addVideo('http://www.youtube.com/embed/' . YoutubeID($posts->video), [
                'secure_url' => 'https://www.youtube.com/embed/' . YoutubeID($posts->video),
                'type' => 'text/html',
                'width' => 640,
                'height' => 360
            ]);
        return view('frontend.detail.video', compact('posts', 'prev', 'postDesc', 'postContent'));
    }

    public function showCategory($slug)
    {
        $date = date('Y-m-d H:i:s');
        $data = explode("-", $slug);
        $id = $data[count($data) - 1];
        $category = Category::find($id);
        if ($category == null){
            alert()->error('Aradıgınız kategori bulunmamakta.');
            return redirect(route('home'));
        }
        $posts = Posts::where('created_at', '<=', $date)
            ->where('status', 1)
            ->where('location', '!=', 5)
            ->orderBy('created_at', 'DESC')
            ->where('category', $id)
            ->paginate(10);
        SEO::setTitle($category->title);
        SEO::setDescription($category->content);
        SEO::setCanonical(route('show_category',str_slug($category->title).'-'.$category->id));
        SEO::opengraph()->setTitle($category->title)
            ->setDescription($category->content)
            ->setUrl(route('show_category',str_slug($category-> title).'-'.$category->id));
        return view('frontend.detail.category', compact('posts', 'category'));
    }

    public function newPost(){
        $category = Category::get();
        $type = 0;
        SEO::setTitle('Yeni Yazı Ekle');
        return view('frontend.new_post',compact('category','type'));
    }

    public function newPostPost(Request $request){
        $validator = Validator::make($request->all(), [
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'title' => 'required|min:6',
            'short_desc' => 'required',
            'content_full' => 'required',
            'category' => 'required',
            'location' => 'required',
            'tag' => 'required',
            'date' => 'required'
        ]);
        $contents = $request->short_desc."----------------------".$request->content_full;
        if($this->user->user()->is_admin == 1 || $this->user->user()->is_moderator == 1){
            $status = 1;
            $responseText = 'İçerik Başarıyla Oluşturuldu.';
        }else{
            $status = 0;
            $responseText = 'İçerik Onaya Sunuldu.';
        }
        $post = new Posts;
        if ($validator->passes()) {
            if($request->file('image')){
                $imageName = str_slug($request->title) .'.' . request()->image->getClientOriginalExtension();
                request()->image->move(public_path('resimler'), $imageName);
                $post->image = $imageName;
            }else{
                $post->image = 'yok.png';
            }
            $date = Carbon::createFromFormat('d-m-Y H:i:s', $request->date)->toDateTimeString();
            $post->title = $request->title;
            $post->content = $contents;
            $post->author = $this->user->id();
            $post->status = $status;
            $post->type = 0;
            $post->category = $request->category;
            $post->location = $request->location;
            $post->tag = $request->tag;
            $post->created_at = $date;
            $post->updated_at = $date;
            $post->save();

            // share social media
            if($post->status == 1){
                $tags = explode(',', $post->tag);

                $tag = "";

                foreach ($tags as $item){
                    $tag .= '#'.str_replace('-', '_', str_slug($item)). ' ';
                }

                SendTo::Twitter(
                    $post->title.' '.route('show_post',$post->full_url). ' '.$tag
                );

                /*SendTo::Facebook(
                    'photo',
                    [
                        'photo' => asset('resimler/'.$post->image),
                        'message' => $post->title.' '.route('show_post',$post->full_url). ' '.$tag
                    ]
                );*/
            }


            alert()->success($responseText);
            return redirect('home');
        }
        return redirect()->back()->withErrors($validator)->withInput();
    }

    public function newVideo(){
        $category = Category::get();
        $type = 1;
        SEO::setTitle('Yeni Video Ekle');
        return view('frontend.new_post',compact('category','type'));
    }

    public function newVideoPost(Request $request){
        $validator = Validator::make($request->all(), [
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'title' => 'required|min:6',
            'short_desc' => 'required',
            'content_full' => 'required',
            'category' => 'required',
            'video' => 'required',
            'tag' => 'required',
            'location' => 'required',
            'date' => 'required'
        ]);
        $contents = $request->short_desc."----------------------".$request->content_full;
        if($this->user->user()->is_admin == 1 || $this->user->user()->is_moderator == 1){
            $status = 1;
            $responseText = 'İçerik Başarıyla Oluşturuldu.';
        }else{
            $status = 0;
            $responseText = 'İçerik Onaya Sunuldu.';
        }
        $post = new Posts;
        if ($validator->passes()) {
            if($request->file('image')){
                $imageName = str_slug($request->title) .'.' . request()->image->getClientOriginalExtension();
                request()->image->move(public_path('resimler'), $imageName);
                $post->image = $imageName;
            }else{
                $post->image = 'yok.png';
            }
            $date = Carbon::createFromFormat('d-m-Y H:i:s', $request->date)->toDateTimeString();
            $post->title = $request->title;
            $post->content = $contents;
            $post->author = $this->user->id();
            $post->status = $status;
            $post->type = 1;
            $post->category = $request->category;
            $post->video = $request->video;
            $post->location = $request->location;
            $post->tag = $request->tag;
            $post->created_at = $date;
            $post->updated_at = $date;
            $post->save();

            // share social media
            if($post->status == 1){
                $tags = explode(',', $post->tag);

                $tag = "";

                foreach ($tags as $item){
                    $tag .= '#'.str_replace('-', '_', str_slug($item)). ' ';
                }

                SendTo::Twitter(
                    $post->title.' '.route('show_video',$post->full_url). ' '.$tag
                );

                /*SendTo::Facebook(
                    'photo',
                    [
                        'photo' => asset('resimler/'.$post->image),
                        'message' => $post->title.' '.route('show_video',$post->full_url). ' '.$tag
                    ]
                );*/
            }

            alert()->success($responseText);
            return redirect('home');
        }
        return redirect()->back()->withErrors($validator)->withInput();
    }

    public function threads(){
        SEO::setTitle('İçeriklerim');
        return view('frontend.threads');
    }

    public function editPost($id, Request $request){
        $post = Posts::where('type',0)->find($id);
        if($post == null){
            alert()->error('İçerik bulunamadı')->persistent("Kapat");
            return redirect(route('threads'));
        }
        $category = Category::get();
        $type = 0;
        if($this->user->user()->is_admin == 1 || $this->user->user()->is_moderator == 1){

        }elseif($this->user->id() != $post->author){
            alert()->error('Bu içerik size ait değil');
            return redirect(route('home'));
        }else{
            alert()->error('Bu içerik size ait değil');
            return redirect(route('home'));
        }
        return view('frontend.post_edit',compact('post','category','type'));
    }

    public function editVideo($id, Request $request){
        $post = Posts::where('type',1)->find($id);
        if($post == null){
            alert()->error('İçerik bulunamadı')->persistent("Kapat");
            return redirect(route('threads'));
        }
        $category = Category::get();
        $type = 1;
        if($this->user->user()->is_admin == 1 || $this->user->user()->is_moderator == 1){

        }elseif($this->user->id() != $post->author){
            alert()->error('Bu içerik size ait değil');
            return redirect(route('home'));
        }else{
            alert()->error('Bu içerik size ait değil');
            return redirect(route('home'));
        }
        return view('frontend.post_edit',compact('post','category','type'));
    }

    public function editPostP($id, Request $request){
        $validator = Validator::make($request->all(), [
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'title' => 'required|min:6',
            'short_desc' => 'required',
            'content_full' => 'required',
            'category' => 'required',
            'location' => 'required',
            'tag' => 'required',
            'date' => 'required'
        ]);
        $post = Posts::find($id);
        if($post == null){
            alert()->error('İçerik bulunamadı')->persistent("Kapat");
            return redirect(route('threads'));
        }
        $contents = $request->short_desc."----------------------".$request->content_full;
        if($this->user->user()->is_admin == 1 || $this->user->user()->is_moderator == 1){
            $status = 1;
            $responseText = 'İçerik Başarıyla Güncelleştirildi.';
        }else{
            $status = 0;
            $responseText = 'İçerik Onaya Sunuldu.';
        }
        if ($validator->passes()) {
            $date = Carbon::createFromFormat('d-m-Y H:i:s', $request->date)->toDateTimeString();
            if ($request->file('image')) {
                $imageName = str_slug($request->title) . '.' . request()->image->getClientOriginalExtension();
                request()->image->move(public_path('resimler'), $imageName);
                $post->image = $imageName;
            }
            $post->title = $request->title;
            $post->content = $contents;
            $post->status = $status;
            $post->type = 0;
            $post->category = $request->category;
            $post->video = $request->video;
            $post->location = $request->location;
            $post->tag = $request->tag;
            $post->created_at = $date;
            $post->save();
            alert()->success($responseText);
            return redirect(route('threads'));
        }
        return redirect()->back()->withErrors($validator)->withInput();
    }

    public function editVideoP($id, Request $request){
        $validator = Validator::make($request->all(), [
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'title' => 'required|min:6',
            'short_desc' => 'required',
            'video' => 'required',
            'content_full' => 'required',
            'category' => 'required',
            'location' => 'required',
            'tag' => 'required',
            'date' => 'required'
        ]);
        $post = Posts::find($id);
        if($post == null){
            alert()->error('İçerik bulunamadı')->persistent("Kapat");
            return redirect(route('threads'));
        }
        $contents = $request->short_desc."----------------------".$request->content_full;
        if($this->user->user()->is_admin == 1 || $this->user->user()->is_moderator == 1){
            $status = 1;
            $responseText = 'İçerik Başarıyla Güncelleştirildi.';
        }else{
            $status = 0;
            $responseText = 'İçerik Onaya Sunuldu.';
        }
        if ($validator->passes()) {
            $date = Carbon::createFromFormat('d-m-Y H:i:s', $request->date)->toDateTimeString();
            if ($request->file('image')) {
                $imageName = str_slug($request->title) . '.' . request()->image->getClientOriginalExtension();
                request()->image->move(public_path('resimler'), $imageName);
                $post->image = $imageName;
            }
            $post->title = $request->title;
            $post->content = $contents;
            $post->status = $status;
            $post->type = 1;
            $post->category = $request->category;
            $post->video = $request->video;
            $post->location = $request->location;
            $post->tag = $request->tag;
            $post->created_at = $date;
            $post->save();
            alert()->success($responseText);
            return redirect(route('threads'));
        }
        return redirect()->back()->withErrors($validator)->withInput();
    }

    public function deletePost($id){
        $post = Posts::find($id);
        if($post == null){
            alert()->error('İçerik bulunamadı')->persistent("Kapat");
            return redirect(route('threads'));
        }
        if($this->user->user()->is_admin == 1){
            $post->delete();
            alert()->success('İçerik Silindi');
            return redirect(route('threads'));
        }
        alert()->error('Lütfen bizimle iletişime geçiniz.','Bunu yapmaya yetkiniz yok.')->persistent("Kapat");
        return redirect(route('threads'));
    }

    public function commentsDetail($id){
        $post = Posts::find($id);
        $comments = Comments::where('posts_id', $id)
            ->where('status', 1)
            ->where('parent_id', 0)
            ->orderBy('created_at', 'DESC')->paginate(10);
        return view('frontend.detail.comment_detail', compact('comments', 'post'));
    }

}
