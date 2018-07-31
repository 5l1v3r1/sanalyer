<?php

namespace App\Http\Controllers\Developer;

use Illuminate\Http\Request;
use SEO;
use App\Http\Controllers\Controller;

class DeveloperController extends Controller
{
    public function docs(){
        SEO::setTitle('Sanalyer API Documentation');
        SEO::setDescription('Sanalyer API and SDK Documentation');
        SEO::setCanonical(route('docs'));
        SEO::opengraph()->setTitle('Sanalyer API Documentation')
            ->setDescription('Sanalyer API and SDK Documentation')
            ->setUrl(route('docs'));
        return view("api.docs");
    }
}
