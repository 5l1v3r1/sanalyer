<?php include( "header.php" ); ?>
<body>
<?php include( "menu.php" ); ?>
	<div class="container" style="padding: 10px 15px;">
		<hr>
			
		<div class="panel panel-default">
			<div class="panel-heading">Resim Galerisi</div>
			<div class="panel-body">
<?php
$dizin = "galeri";
$tutucu = opendir($dizin);
while($dosya = readdir($tutucu)){
if(is_file($dizin."/".$dosya))
$resim[] = $dosya;
}
closedir($tutucu);
 
$limit = 20;
$sf = $_GET["sf"];
if($sf < 1) $sf = 1;
$toplam = count($resim);

$kactan = ($sf-1) * $limit;
$kaca = ($kactan+$limit);
if($kaca > $toplam) $kaca = $toplam;
 
for($i=$kactan; $i < $kaca; $i++){
echo "
<div class='col-md-3'><a href='".$dizin."/".$resim[$i]."' target='_blank' class='thumbnail'>
<img onContextMenu='return false' src='".$dizin."/".$resim[$i]."'
width='200' border='0'></a></div>";
}
echo" </br></br></br>";
for($i=1; $i < $toplam / $limit; $i++){
if($sf == $i)
echo "$in"; else
echo "<a href='resim.php?sf=$i'>$i</a>n";
}
?>
			</div>
		</div>
		<hr>
		
		<footer>
			<p>© 2015 <?php echo "$sitebasligi"; ?> | Scripti Hazırlayan : sanalyer.com</p>
		</footer>
	</div>
</body></html>