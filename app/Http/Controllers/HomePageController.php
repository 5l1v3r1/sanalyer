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

class HomePageController extends Controller
{

    public function abouts(){
        SEO::setTitle('Hakkımızda');
        SEO::setDescription('Sanalyer Hakkında');
        SEO::setCanonical(route('abouts'));
        SEO::opengraph()->setTitle('Hakkımızda')
            ->setDescription('Sanalyer Hakkında')
            ->setUrl(route('abouts'));
        return view('frontend.static_page.abouts');
    }

    public function corporate(){
        SEO::setTitle('Künye');
        SEO::setDescription('Sanalyer Künye');
        SEO::setCanonical(route('corporate'));
        SEO::opengraph()->setTitle('Künye')
            ->setDescription('Sanalyer Künye')
            ->setUrl(route('corporate'));
        return view('frontend.static_page.corporate');
    }

    public function privacy(){
        SEO::setTitle('Gizlilik Politikası');
        SEO::setDescription('Sanalyer Gizlilik Politikası');
        SEO::setCanonical(route('privacy'));
        SEO::opengraph()->setTitle('Gizlilik Politikası')
            ->setDescription('Sanalyer Gizlilik Politikası')
            ->setUrl(route('privacy'));
        return view('frontend.static_page.privacy');
    }

    public function contact(){
        SEO::setTitle('İletişim');
        SEO::setDescription('Sanalyer İletişim');
        SEO::setCanonical(route('contact'));
        SEO::opengraph()->setTitle('İletişim')
            ->setDescription('Sanalyer İletişim')
            ->setUrl(route('contact'));
        return view('frontend.static_page.contact');
    }

}
