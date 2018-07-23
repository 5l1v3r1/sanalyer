<?php include( "header.php" ); ?>
<body>
<?php include( "menu.php" ); ?>
	<div class="container" style="padding: 10px 15px;">
		<hr>
			
		<div class="panel panel-default">
<?php
$dosya = str_replace(" ", "_", $_FILES[resim][name]);
?>
			<div class="panel-heading"><? echo "$dosya"; ?></div>
			<div class="panel-body">
			<?php 
 function uzantibul($file) {  
    $array = explode('.',$file);  
    $key   = count($array) -1;  
    $ext   = $array[$key];  
    return $ext;  
    }   
$kaynak = $_FILES["resim"]["tmp_name"];
$dosya = str_replace(" ", "_", $_FILES[resim][name]); 
$s=strtolower(uzantibul($dosya));
$os = array("gif", "png", "jpg", "jpeg", "bmp","tif"); 
if (!in_array($s, $os)) {  
echo("Geçersiz Dosya Türü");  
exit();  
}  
$hedef  = "../resimler/upload/".$dosya;
if (file_exists($hedef)) { 
    $hmz = substr(md5(uniqid(rand())),0,8);  
    $hedef = "../resimler/upload/$hmz-".$dosya;
}  
move_uploaded_file($kaynak,$hedef);  
?>
				<div class="row">
					<div class="col-md-3 visible-md visible-lg">
						<a href="#" class="thumbnail">
							<img style="max-width: 190px; max-height: 300px;" src="<? echo "$siteadresiniz/$hedef"; ?>">
						</a>
					</div>
					<div class="col-md-9">
						<form class="form-horizontal" role="form">
							<div class="form-group">
								<label for="link-html" class="col-sm-3 control-label">Web Siteleri: <abbr title="Zengin Metin İşaret Dili" style="font-size: x-small;">html</abbr></label>
								<div class="col-sm-9">
									<input id="link-html" title="Zengin Metin İşaret Dili" class="form-control" style="font-size: x-small;" onClick="this.select();" value="<a href=&quot;<? echo "$siteadresiniz/$hedef"; ?>&quot;><img src=&quot;<? echo "$siteadresiniz/$hedef"; ?>&quot; /></a>">
								</div>
							</div>
							<div class="form-group">
								<label for="link-bbcode" class="col-sm-3 control-label">Forumlar: <abbr title="Mesaj Panosu Kodu" style="font-size: x-small;">bbcode</abbr></label>
								<div class="col-sm-9">
									<input id="link-bbcode" title="Mesaj Panosu Kodu" class="form-control" style="font-size: x-small;" onClick="this.select();" value="[url=<? echo "$siteadresiniz/$hedef"; ?>][img]<? echo "$siteadresiniz/$hedef"; ?>[/img][/url]">
								</div>
							</div>
							<div class="form-group">
								<label for="link-markdown" class="col-sm-3 control-label">Metinler: <abbr title="Biçimlendirme Kodu" style="font-size: x-small;">markdown</abbr></label>
								<div class="col-sm-9">
									<input id="link-markdown" title="Biçimlendirme Kodu" class="form-control" style="font-size: x-small;" onClick="this.select();" value="[![image](<? echo "$siteadresiniz/$hedef"; ?>)](<? echo "$siteadresiniz/$hedef"; ?>)">
								</div>
							</div>
							<div class="form-group">
								<label for="link-web" class="col-sm-3 control-label">Resim Adresi:</label>
								<div class="col-sm-6">
									<input id="link-web" class="form-control" style="font-size: x-small;" onClick="this.select();" value="<? echo "$siteadresiniz/$hedef"; ?>">
								</div>
								
							</div>
							<div class="form-group">
								<label for="link-direct" class="col-sm-3 control-label">Doğrudan Erişim:</label>
								<div class="col-sm-6">
									<input id="link-direct" class="form-control" style="font-size: x-small;" onClick="this.select();" value="<? echo "$siteadresiniz/$hedef"; ?>">
								</div>
								
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<hr>
		
		<footer>
			<p>© 2015 <?php echo "$sitebasligi"; ?> | Scripti Hazırlayan : Sanalyer.com</p>
		</footer>
	</div>
</body></html>