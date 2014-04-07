<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<meta name="HandheldFriendly" content="true" />

<title>Baratosos - Lo que buscas, mucho mas barato</title>

<link rel="shortcut icon" href="favicon.png" />

<link rel="stylesheet" href="style/style-main.css" type="text/css"/>
<link rel="stylesheet" href="style/style-general.css" type="text/css"/>
<link rel="stylesheet" href="style/FontAwesome/css/font-awesome.css" type="text/css"/>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'/>

<script type='text/javascript' src='Scripts/jquery-1.10.2.js'></script>



<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-45873793-1', 'tumall.do');
  ga('send', 'pageview');

</script>

</head>
<body>
	<div id="container" class="landing">
		<div id="header">
			<div class="quickLinksWrap">
				<a class="quickLink" href="http://tumall.doregistro">Vende en Tu Mall</a>
				<a class="quickLink" href="http://tumall.donovedades">Novedades</a>
				<a class="quickLink" href="http://tumall.doFAQ">Ayuda</a>
				<a class="quickLink" href="http://tumall.dotiendas">Lista de tiendas</a>
			</div>
			<div class="headerCenter transTw">
				
				<span class="mobSearchBtn"></span>
			
				<div class="menuTopRight">
					
					<div class="menuWrap">
						<?php 
						if($usuario->idtipousuario == 3)
						{
							?>
							<a class="hdLinks" href="administracion/usuarios">Administrar</a>
							<?php
						}
						else if($usuario->idtipousuario == 2)
						{
							?>
							<a class="hdLinks" href="<?php 
	                                        echo $session["autenticado"] ? "productos/lista/" : "accesar";
	                                        ?>">Mi cuenta</a>
							<a class="hdLinks" href="
	                                           <?php echo $session["autenticado"] ? "productos/nuevo/" : "accesar";
	                                        ?>">Publicar</a>
							<?php
						}
						else if($usuario->idtipousuario == 1)
						{
							?>
							<a class="hdLinks" href="cliente/fav">Mi cuenta</a>
							<?php
						}
						else
							{
								?>
								<a class="hdLinks loginBtn" href="<?php 
		                                        echo $session["autenticado"] ? "productos/lista/" : "accesar";
		                                        ?>">Login/Registro</a>
								<?php
							}
	                    if($session["autenticado"])
						{
							?>
							<a class="hdLinks logoutBtn" href="phpfn/salir">Salir</a>
							<?php
						}
	                    ?>
					</div>                                        
				</div>
			</div>
		</div> <!-- close header -->

		<div id="body" class="group">
