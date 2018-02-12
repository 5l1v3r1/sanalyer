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
$link = "https://www.sanalyer.com/comments.php";	
$site = CurlBaglan($link);

$i=0; 
$sanalyer = json_decode($site);
foreach($sanalyer as $mydata) 
{
	 $id = $sanalyer_array[$i][0] = $mydata->id; 
	 $posts_id = $sanalyer_array[$i][0] = $mydata->posts_id; 
	 $content = $sanalyer_array[$i][0] = addslashes($mydata->content); 
	 $status = $sanalyer_array[$i][0] = addslashes($mydata->status); 
	 $parent_id = $sanalyer_array[$i][0] = addslashes($mydata->parent_id); 
	 $created_at = $sanalyer_array[$i][0] = $mydata->created_at; 

	 $insert = $baglan->query("INSERT INTO comments SET
		id = '$id',
		posts_id = '$posts_id',
		content = '$content',
		status = '$status',
		parent_id = '$parent_id',
		created_at = '$created_at',
		updated_at = '$created_at'");
		if($insert){
			echo 'eklendi<br>';
		}else{
			echo '<h4 class="alert_error">Mysql Hatası : '.mysqli_error($baglan).'</h4>';
		}
     $i++;
}


?>