<?php include( "header.php" ); ?>

<body>
<?php include( "menu.php" ); ?>
	<div class="container" style="padding: 10px 15px;">
		<div class="row">
			<div class="col-md-4 visible-md visible-lg">

                <a href="https://www.radkod.com" target="_blank" title="Freelance Web Tasarım">
                    <img src="https://www.sanalyer.com/resimler/radkod_freelance_ekibi.png" alt="RadKod">
                </a>
			</div>
			<div class="col-md-8">
				<div class="well">
					<ul class="nav nav-tabs nav-justified">
						<li class="active"><a href="#tab-upload-local" data-toggle="pill">Bilgisayardan Yükle</a></li>
						
						
					</ul>
					<form id="upload_form" method="post" enctype="multipart/form-data" action="upload.php">
						<div class="tab-content" style="padding: 10px 0;">
							<div id="tab-upload-local" class="tab-pane active">
								<div class="row">
									<div class="col-md-9">
										<input name="resim" type="file" id="resim" class="form-control">
									</div>
									<div class="col-md-3">
										<button type="submit" class="btn pull-right btn-success">Yükle</button>
									</div>
								</div>
								<!--
								<span class="help-block" i18n:translate="">drag_hint</span>
								-->
							</div>
						</div>
					</form>
					<div id="upload_progress" class="progress progress-striped active hidden" style="margin-bottom: 0;">
					  <div class="progress-bar" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="1" style="width: 100%">
					    <span>Yükleniyor</span>
					  </div>
					</div>
				</div>
				<div class="well"><?php echo "$sitebasligi"; ?> ücretsiz resim yükleme servisidir. Yüklediğiniz resimleri internette arkadaşlarınızla, blogunuzda, web sitelerinde, forumlarda veya sosyal ağlarda paylaşabilirsiniz.</div>
				
			</div>
		</div>
		<hr>
		<footer style="overflow: hidden;">
			<p>© 2015 <?php echo "$sitebasligi"; ?> | Scripti Hazırlayan : Sanalyer.com</p>
		</footer>
	</div>
	
	<script type="text/javascript">
	$(function() {
		
		$('#upload_form').submit(function(event) {
			
			$('#upload_progress').removeClass('hidden');
			
			$(this).find('button').attr('disabled', 'disabled');
		});
	});
	</script>
</body></html>