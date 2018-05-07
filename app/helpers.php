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

function numberFormat($number){
    return number_format($number, 2, ',', '.');
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
    $find = array( 'resimler');
    $replace = array( env('APP_URL').'/resimler');
    $text = strtolower(str_replace($find, $replace, $text));
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

function curlConnect($link){
    $ch  = curl_init();
    $hc = "Mozilla/5.0 (Windows; U; Windows NT 5.1; tr; rv:1.9.0.6) Gecko/2009011913 Firefox/3.0.6";
    curl_setopt($ch,CURLOPT_URL, $link);
    curl_setopt($ch,CURLOPT_USERAGENT, $hc);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_REFERER, "https://www.radkod.com");
    $Curl = curl_exec($ch);
    curl_close($ch);
    return $Curl;
}

function articleRewriter($content,$lang = 'tr'){
    $api_url = "https://dev.radkod.com/spinner/api.php";
    $api_key = "bfba36e79ad3b076f74411f98d2d9f33";
    $article = $content;
    $lang = $lang;

    $cookie=tempnam("/tmp","CURLCOOKIE");
    $agent = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7";
    $ch = curl_init();

    $data = "api_key=$api_key&article=$article&lang=$lang";
    curl_setopt($ch, CURLOPT_URL,"$api_url");
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_setopt($ch, CURLOPT_ENCODING,"gzip,deflate");
    curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: application/x-www-form-urlencoded","Accept: */*"));
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_REFERER, "https://www.sanalyer.com");
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,"$data");
    $html=curl_exec($ch);
    $lastUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

    return $html;
}
