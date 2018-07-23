<?php

namespace App\Http\Controllers\Filemanager;

/**
 * Class DemoController.
 */
class DemoController extends LfmController
{
    /**
     * @return mixed
     */
    public function index()
    {
        return view('laravel-filemanager::demo');
    }
}
