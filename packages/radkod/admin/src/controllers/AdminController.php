<?php
namespace Radkod\Admin\Controllers;

use Radkod\Posts\Models\Posts;
use Illuminate\Routing\Controller;

class AdminController extends Controller {
    public static function home()
    {
        $tests = 'Admin Selam';
        return view('admin::index',compact('tests'));
    }

}