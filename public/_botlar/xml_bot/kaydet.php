<?php
if ($_POST){
				
				$reklam_baslik = p("reklam_baslik", true);
				$reklam_icerik = p("reklam_icerik");
				$reklam_yer = p("reklam_yer", true);
					
						$insert = $baglan->query("INSERT INTO reklamlar SET
						reklam_baslik = '$reklam_baslik',
						reklam_icerik = '$reklam_icerik',
						reklam_yer = '$reklam_yer'");
						
						if($insert){
								echo '
							eklendi
							
							';
							
						}else{
								echo .mysqli_error($baglan);
						}
						
					
				
				
			}
?>