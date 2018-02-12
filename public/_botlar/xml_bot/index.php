<?php
ini_set('max_execution_time', 0); 
include 'ayar.php';
function CurlBaglan($link){
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
function sef_link($s) {
	$tr = array('ş','Ş','ı','I','İ','ğ','Ğ','ü','Ü','ö','Ö','Ç','ç','(',')','/',':',',',"'","!",'"');
	$eng = array('s','s','i','i','i','g','g','u','u','o','o','c','c','','','-','-','',"","","");
	$s = str_replace($tr,$eng,$s);
	$s = strtolower($s);
	$s = preg_replace("@[^a-z0-9\-_şıüğçİŞĞÜÇ]+@i","-",$s);
	$s = preg_replace('/&amp;amp;amp;amp;amp;amp;amp;amp;amp;.+?;/', '', $s);
	$s = preg_replace('/\s+/', '-', $s);
	$s = preg_replace('|-+|', '-', $s);
	$s = preg_replace('/#/', '', $s);
	$s = preg_replace('/[[:^print:]]/', '', $s);
	$s = str_replace('.', '', $s);
	$s = trim($s, '-');
	return $s;
}

$sayac = 1;
$link = "https://www.ruyatabirleri.com/sitemap_index.xml";
$temp = mb_convert_encoding(file_get_contents($link), 'UTF-8');
$xml = simplexml_load_string($temp);
foreach ($xml->children() as $row) {
	 
			$icerikxml =  $row->loc; 
			$temp = mb_convert_encoding(file_get_contents($icerikxml), 'UTF-8');
			$xml = simplexml_load_string($temp);
			foreach ($xml->children() as $row) {
				
					$tabirlink = $row->loc; 
					$site = CurlBaglan($tabirlink);
					preg_match_all('@<h2>(.*?)<\/h2>@si',$site,$baslik);
					preg_match_all('@<p>(.*?)<\/p>@si',$site,$detay);
					$tabir_adi = $baslik[1][0];
					$tabir_icerik = $detay[1][0];
					$tabir_link = sef_link($tabir_adi);
					
					$insert = $baglan->query("INSERT INTO ruya_tabirleri SET
						tabir_adi = '$tabir_adi',
						tabir_link = '$tabir_link',
						tabir_icerik = '$tabir_icerik'");
				
			}
	
	$sayac = $sayac++;
}

?>