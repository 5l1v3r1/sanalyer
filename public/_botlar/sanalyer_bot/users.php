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
$link = "https://www.sanalyer.com/users.php";	
$site = CurlBaglan($link);

$i=0; 
$sanalyer = json_decode($site);
foreach($sanalyer as $mydata) 
{
	 $id = $sanalyer_array[$i][0] = $mydata->id; 
	 $name = $sanalyer_array[$i][0] = $mydata->name; 
	 $email = $sanalyer_array[$i][0] = addslashes($mydata->email); 
	 $firstname = $sanalyer_array[$i][0] = addslashes($mydata->firstname); 
	 $lastname = $sanalyer_array[$i][0] = addslashes($mydata->lastname); 
	 $biography = $sanalyer_array[$i][0] = addslashes($mydata->biography); 
	 $rank = $sanalyer_array[$i][0] = addslashes($mydata->rank); 
	 $created_at = $sanalyer_array[$i][0] = $mydata->created_at; 

	 $insert = $baglan->query("INSERT INTO users SET
		id = '$id',
		name = '$name',
		email = '$email',
		password = '$2y$10$zh2bRu28HzkbDyXI3H0eKOK6QqCiSq2.ACHpd61K.PMHITpDVrsb.',
		firstname = '$firstname',
		lastname = '$lastname',
		biography = '$biography',
		rank = '$rank',
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