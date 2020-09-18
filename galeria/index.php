<?php

$id_os = $_GET['id_os'];

$link = new mysqli('localhost', 'root', '', 'help_desk_ecos');
$link->set_charset('utf8');

$sql = "SELECT * FROM ordem_servico WHERE id_os = '$id_os'";
$result_os = $link->query($sql);
$protocolo = mysqli_fetch_object($result_os)->protocolo;

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Imagens</title>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"> 
        <meta name="description" content="Responsive Image Gallery with jQuery" />
        <meta name="keywords" content="jquery, carousel, image gallery, slider, responsive, flexible, fluid, resize, css3" />
		<meta name="author" content="Codrops" />
		<link rel="shortcut icon" href="../favicon.ico"> 
        <link rel="stylesheet" type="text/css" href="css/demo.css" />
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<link rel="stylesheet" type="text/css" href="css/elastislide.css" />
		<link href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow&v1' rel='stylesheet' type='text/css' />
		<link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css' />
		<noscript>
			<style>
				.es-carousel ul{
					display:block;
				}


			</style>
		</noscript>
		<script id="img-wrapper-tmpl" type="text/x-jquery-tmpl">	
			<div class="rg-image-wrapper">
				{{if itemsCount > 1}}
					<div class="rg-image-nav">
						<a href="#" class="rg-image-nav-prev">Previous Image</a>
						<a href="#" class="rg-image-nav-next">Next Image</a>
					</div>
				{{/if}}
				<div class="rg-image"></div>
				<div class="rg-loading"></div>
				<div class="rg-caption-wrapper">
					<div class="rg-caption" style="display:none;">
						<p></p>
					</div>
				</div>
			</div>
		</script>

    </head>
    <body>
		<div class="container">
			<div class="header">
				<div class="clr"></div>
			</div><!-- header -->
			
			<div class="content">
				<h1>Imagens da OS <?php echo $protocolo; ?></h1>
				<div id="rg-gallery" class="rg-gallery">
					<div class="rg-thumbs">
						<!-- Elastislide Carousel Thumbnail Viewer -->
						<div class="es-carousel-wrapper">
							<div class="es-nav">
								<span class="es-nav-prev">Previous</span>
								<span class="es-nav-next">Next</span>
							</div>
							<div class="es-carousel">
								<ul>
									<?php

										$pasta = $protocolo.'/';
										$arquivos = glob("$pasta{*.jpeg,*.jpg,*.png,*.gif,*.bmp,*.jfif}", GLOB_BRACE);
										foreach($arquivos as $img){

											$descricao_array = explode(' ', explode('/', explode('.',$img)[0])[1]);
											$descricao = '';

											foreach ($descricao_array as $key => $value) {
												$descricao.=$value.'-';
											}


											//echo "<script>alert('$descricao')</script>";

											?>
											<li>
												<a href="<?php echo $img; ?>">
													<img width="100" height="100" src="<?php echo $img; ?>" data-large="<?php echo $img; ?>" alt="image01" data-description=<?php echo $descricao; ?> />
												</a>
											</li>

										<?php } ?>
									
									
									
								</ul>
							</div>
						</div>
						<!-- End Elastislide Carousel Thumbnail Viewer -->
					</div><!-- rg-thumbs -->
				</div><!-- rg-gallery -->
				<p class="sub">Site? <a href="http://www.ecospb.com.br" target="_blank">http://www.ecospb.com.br</a></p>
			</div><!-- content -->
		</div><!-- container -->
		<!-- Scripts-->
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.tmpl.min.js"></script>
		<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
		<script type="text/javascript" src="js/jquery.elastislide.js"></script>
		<script type="text/javascript" src="js/gallery.js"></script>
    </body>
</html>