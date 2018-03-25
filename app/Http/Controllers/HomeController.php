<?php

namespace App\Http\Controllers;

use function App\checkImage;
use App\User;
use Carbon\Carbon;
use Radkod\Posts\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as Controller;
use SEO;
use Illuminate\Support\Facades\App;

class HomeController extends Controller
{

    public function index()
    {
        $date = date('Y-m-d H:i:s');
        $posts = Posts::where('status', 1)->where('location', '!=', 5)->where('created_at', '<=', $date)->orderBy('created_at', 'DESC')->paginate(10);
        $slider = Posts::where('status', 1)->where('location', 1)->where('created_at', '<=', $date)->orderBy('created_at', 'DESC')->limit(4)->get();
        return view('frontend.home', compact('posts', 'slider'));
    }

    public function news()
    {
        SEO::setTitle('Tüm Haberler');
        SEO::setDescription('Sanalyer\'e son eklenen haberlere bu sayfadan ulaşabilirsiniz.');
        SEO::setCanonical(route("news"));
        SEO::opengraph()->setTitle('Tüm Haberler')
            ->setDescription('Sanalyer\'e son eklenen haberlere bu sayfadan ulaşabilirsiniz.')
            ->setUrl(route("news"));


        $date = date('Y-m-d H:i:s');
        $posts = Posts::where('type', 0)->where('status', 1)->where('location', '!=', 5)->where('created_at', '<=', $date)->orderBy('created_at', 'DESC')->paginate(10);
        return view('frontend.news', compact('posts'));
    }

    public function video()
    {
        SEO::setTitle('Tüm Videolar');
        SEO::setDescription('Sanalyer\'e son eklenen videolara bu sayfadan ulaşabilirsiniz.');
        SEO::setCanonical(route("video"));
        SEO::opengraph()->setTitle('Tüm Videolar')
            ->setDescription('Sanalyer\'e son eklenen videolara bu sayfadan ulaşabilirsiniz.')
            ->setUrl(route("video"));


        $date = date('Y-m-d H:i:s');
        $posts = Posts::where('type', 1)->where('status', 1)->where('location', '!=', 5)->where('created_at', '<=', $date)->orderBy('created_at', 'DESC')->paginate(10);
        return view('frontend.video', compact('posts'));
    }

    public function showProfile(Request $request){
        $date = date('Y-m-d H:i:s');
        $slug = $request->slug;
        $data = explode("-", $slug);
        $id = $data[count($data) - 1];
        $user = User::find($id);
        if($user == null){
            alert()->error('Böyle bir üye bulunmamakta');
            return redirect(route('home'));
        }
        $posts = Posts::where('created_at', '<=', $date)
            ->where('status', 1)
            ->where('author',$user->id)
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        SEO::setTitle($user->firstname.' '.$user->lastname.' adlı editörün tüm yazıları');
        SEO::setDescription($user->firstname.' '.$user->lastname.' adlı editörün tüm yazıları');
        SEO::setCanonical(route('show_profile',str_slug($user->name).'-'.$user->id));
        SEO::opengraph()->setTitle($user->firstname.' '.$user->lastname.' adlı editörün tüm yazıları')
            ->setDescription($user->firstname.' '.$user->lastname.' adlı editörün tüm yazıları')
            ->setUrl(route('show_profile',str_slug($user->name).'-'.$user->id));
        return view('auth.profile',compact('posts','user'));
    }

    public function search(Request $request){
        $search = trim($request->q);
        $date = date('Y-m-d H:i:s');

        SEO::setTitle($search.' ile ilgili arama sonuçları');
        SEO::opengraph()->setTitle($search.' ile ilgili arama sonuçları')
            ->setDescription($search.' ile ilgili arama sonuçları')
            ->setUrl(url()->full());

      
        $posts = Posts::where(function($q)use($search){
            return $q->where('title', 'like', '%'.$search.'%')
            ->orWhere('tag', 'like', '%'.$search.'%');
            })->where('status', 1)
            ->where('location', '!=', 5)
            ->where('created_at', '<=', $date)
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
            
        return view('frontend.detail.search', compact('search','posts'));
    }

    public function sitemapDetail($page = 0, Request $request)
    {
        $date = date('Y-m-d H:i:s');
        $page = intval($page);
        $take = 500;
        $skip = $page * $take;
        $sitemap = App::make('sitemap');
        $sitemap->setCache('sitemap_' . $page, 60);
        if (!$sitemap->isCached() || !empty($request->debug)) {
            $posts = Posts::skip($skip)->take($take)->where('status', 1)->where('location', '!=', 5)->where('created_at', '<=', $date)->orderBy('created_at', 'ASC')->get();

            foreach ($posts as $p) {
                $images = array();
                $images[] = array(
                    'url' => checkImage($p->image),
                    'title' => $p->title
                );
                if ($p->type == 0) {
                    $url = route('show_post', str_slug($p->title) . '-' . $p->id);
                } else {
                    $url = route('show_video', str_slug($p->title) . '-' . $p->id);
                }
                $sitemap->add($url, Carbon::parse($p->updated_at), 0.5, "weekly", $images);
            }
        }
        return $sitemap->render('xml');
    }

    public function sitemap()
    {
        $limit = 500;
        $date = date('Y-m-d H:i:s');
        $count = Posts::where('status', 1)->where('location', '!=', 5)->where('created_at', '<=', $date)->orderBy('created_at', 'DESC')->count();
        $total = intval(floor($count / $limit));
        $sitemap = App::make('sitemap');
        $sitemap->setCache('sitemap_index', 60);
        for ($i = 0; $i <= $total; $i++) {
            $sitemap->addSitemap(route('sitemap',$i), Carbon::parse($date));
        }
        return $sitemap->render('sitemapindex');
    }

    public function rss()
    {
        $date = date('Y-m-d H:i:s');
        $take = 20;
        $feed = App::make('feed');
        $feed->setCache('feed', 60);
        if (!$feed->isCached()) {
            $posts = Posts::take($take)->where('status', 1)->where('location', '!=', 5)->where('created_at', '<=', $date)->orderBy('created_at', 'DESC')->get();
            $feed->ctype = "text/xml";
            $feed->title = env('APP_NAME') . ' RSS Servisi';
            $feed->description = env('APP_DESC');
            $feed->logo = asset('rk_content/rss.png');
            $feed->link = route('feed');
            $feed->setDateFormat('datetime');
            $feed->pubdate = $posts[0]->created_at;
            $feed->lang = 'tr';
            $feed->setShortening(true);
            $feed->setTextLimit(200);
            foreach ($posts as $p) {
                $postDescEx = explode('----------------------', $p->content);
                $postDesc = $postDescEx[0];
                $postContent = $postDescEx[1];
                $image = ['url' => checkImage($p->image), 'type' => 'image/jpeg'];
                if ($p->type == 0) {
                    $url = route('show_post', str_slug($p->title) . '-' . $p->id);
                } else {
                    $url = route('show_video', str_slug($p->title) . '-' . $p->id);
                }
                $feed->add($p->title, $p->user->firstname . ' ' . $p->user->lastname, $url, $p->updated_at, $postDesc, $postContent, $image, $p->category()->first()->title);
            }
        }

        return $feed->render('rss');
    }

    public function feed()
    {
        $date = date('Y-m-d H:i:s');
        $take = 20;
        $rss = App::make('feed');
        $rss->setCache('rss', 60);
        if (!$rss->isCached()) {
            $posts = Posts::take($take)->where('status', 1)->where('location', '!=', 5)->where('created_at', '<=', $date)->orderBy('created_at', 'DESC')->get();
            $rss->ctype = "text/xml";
            $rss->title = env('APP_NAME') . ' Feed Servisi';
            $rss->description = env('APP_DESC');
            $rss->logo = asset('rk_content/rss.png');
            $rss->link = route('feed');
            $rss->setDateFormat('datetime');
            $rss->pubdate = $posts[0]->created_at;
            $rss->lang = 'tr';
            $rss->setShortening(true);
            $rss->setTextLimit(200);
            foreach ($posts as $p) {
                $postDescEx = explode('----------------------', $p->content);
                $postDesc = $postDescEx[0];
                $postContent = $postDescEx[1];
                $image = ['url' => checkImage($p->image), 'type' => 'image/jpeg'];
                if ($p->type == 0) {
                    $url = route('show_post', str_slug($p->title) . '-' . $p->id);
                } else {
                    $url = route('show_video', str_slug($p->title) . '-' . $p->id);
                }
                $rss->add($p->title, $p->user->firstname . ' ' . $p->user->lastname, $url, $p->updated_at, $postDesc, $postContent, $image, $p->category()->first()->title);
            }
        }

        return $rss->render('atom');
    }

}
