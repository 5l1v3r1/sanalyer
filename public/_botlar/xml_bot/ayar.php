<?php
error_reporting(0);

$host="localhost";
$db_k_adi="root";
$db_sifre="";
$db_adi="ruya_tabirleri";
$baglan = new mysqli($host,$db_k_adi,$db_sifre,$db_adi); 
$baglan->set_charset('utf8mb4');
?>
