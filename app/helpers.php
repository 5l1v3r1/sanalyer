<?php
namespace App;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Illuminate\Support\Facades\Blade;
use Radkod\Posts\Models\Category;

function checkImage($image)
{
    if (file_exists('resimler/' . $image)) {
        $image = asset('resimler/' . $image);
    }else{
        $image = asset('rk_content/images/no-img.png');
    }
    return $image;
}



function YoutubeID($url)
{
    if(strlen($url) > 11)
    {
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match))
        {
            return $match[1];
        }
        else
            return false;
    }

    return $url;
}

function img_amp($text){
   /* $find = array('<img', 'resimler/haber');
    $replace = array('<amp-img', env('APP_URL').'/resimler/haber');
    $text = strtolower(str_replace($find, $replace, $text));*/
    return $text;
}

function vendorBladeTemplate($string,$data){
    $php = Blade::compileString($string);
    $obLevel = ob_get_level();
    ob_start();
    extract($data,EXTR_SKIP);
    try{
        eval('?'.'>'.$php);
    }
    catch (\Exception $e){
        while(ob_get_level() > $obLevel) ob_end_clean();
        throw $e;
    }
    catch (\Throwable $e){
        while(ob_get_level() > $obLevel) ob_end_clean();
        throw new FatalThrowableError($e);
    }
    return ob_get_clean();
}
