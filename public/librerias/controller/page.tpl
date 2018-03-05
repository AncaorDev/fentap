<?php 
$page = '<!DOCTYPE html>
<html lang="es">
<head>
<?php include HTML_DIR."includes/favicon.inc"; ?>
<?php include HTML_DIR."includes/metas.inc" ?>
<?php include HTML_DIR."includes/archivos.inc" ?>
<title><?=$view?></title>
</head>
<body >
<?php include HTML_DIR."includes/cabecera-top.inc" ?>
<section id="barra-nav">
<div id="cont-nav" class="container">
	<div  class="row div-nav" >
		<nav class="navbar navbar-default navbar-ancaor" data-spy="affix" data-offset-top="190">
		  <div class="nav-cf container-fluid">
		    <div class="navbar-header">
		      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		        <span class="sr-only"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
		      <a class="navbar-brand" href="<?=HOME?>">
		         <img style="max-width:40px; margin-top: -10px;" src="<?=DIR_RS?>images/logo.png">
		      </a>
		      <a href="<?=HOME?>" class="navbar-brand">Ancaor</a>
		    </div>
		    <?php include HTML_DIR."includes/nav.inc" ?>
		</div>	
	</div>
</div>	
</section>
<section id="body">
	<div class="container cont-body">
		<div class="row row-body">
			<?php include HTML_DIR."page/data/data-".$view.".phtml"; ?>
		</div>
	</div>
</section>
<?php include HTML_DIR."includes/redes.inc" ?>
<?php include HTML_DIR."includes/footer.inc" ?>
</body>
</html>';
?>