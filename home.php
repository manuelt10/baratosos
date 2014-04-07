<?php 
session_start();
$session = $_SESSION;
unset($_SESSION["error"]);
unset($_SESSION["buscarLista"]); 
unset($_SESSION["buscarTienda"]);
session_write_close();
?>

<?php 
        require_once('phpfn/cndb.php');
		require_once('phpfn/stringmanager.php');
		require_once('phpfn/mysqlManager.php');
		$db = new mysqlManager();
        $con = conection();
		$sM = new stringManager();
        $query = $con->prepare('SELECT * FROM `usuario` where idusuario = ?');
        $query->bindValue(1,$session["usuario"]);
        $query->execute();
        $usuario = $query->fetch(PDO::FETCH_OBJ);
		
	require_once('templates/headLand.php');
        
?>
		<!-- 	<img class="homeLogo" src="images/logo-max.png" alt="TuMall Logo" /> -->
			
			<h1 class="homeLogo">Tu Mall</h1>
			
			<form id="searchForm" class="hmSrchWrap" action="#">
					
					<input class="hmSrchTxt transTw" type="text" />
					<button class="btn-search">
						<i class="fa fa-search"></i>
					</button>

			</form>
			<script>
			
				$('#searchForm').submit(function( event )
					{
						
						var sear = $(this).children('.hmSrchTxt').val();
						var sel = $('.selectCategory').val();
						var url = 'lista/';
					
							if(sel)
							{
								url = url + 'categoria/' + sel + '/';
							}
							if(sear)
							{
								url = url + 'buscar/' + sear + '/';
							}
							$(this).attr("action", url);
						});
			</script>
		
<!--
		<div class="videoCont">
			<iframe src="//www.youtube.com/embed/BPYQcaUD2Go" frameborder="0" allowfullscreen></iframe>
		</div>	
-->
			
		
			<div class="carouselWrap">	
			<?	
			$cont = 1;
			$imagen = $db->selectRecord('galeria', NULL, Array("activo" => 1), Array('orden' => 'asc'));
			foreach($imagen->data as $row)
			{
				?>
				<a class="carouselImg fluidTransTw<?php echo $cont == 1 ? ' active' : ''; $cont++; ?>" href="<?php echo $row->url ?>">
					<img src="images/galeria/<?php echo $row->imagen ?>">
				</a>
				<?
			}
			?>
				
				<div class="controlWrap">
					<?
					$cont = 1;
					foreach($imagen->data as $row)
					{
					?>
						<span class="carouselCtrl fluidTransTw<?php echo $cont == 1 ? ' active' : ''; $cont++; ?>">
							<span class="controlPreview fluidTransOn">
								<img src="images/galeria/thumb150/<?php echo $row->imagen ?>" />
							</span>
						</span>
					
					<?
					}
					
					?>
					
				</div>
				
			</div>
			
			
			<script>
			
				$('.carouselWrap').children('.carouselImg').each(function(e){
					
					$(this).attr('data-id', e).addClass('' + e + '');
					
				});
				
				$('.controlWrap').children('.carouselCtrl').each(function(e){
					
					$(this).attr('data-id', e).addClass('' + e + '');
					
				});
			
				function makeTransitionFN(){
					var currentIMG = $('.carouselImg.active'),
						currentCtrl = $('.carouselCtrl.active'),
						nextIMG = currentIMG.next();
							
					currentIMG.removeClass('active').addClass('toLeft');	
					currentCtrl.removeClass('active');	
						
					if(!nextIMG.hasClass('carouselImg')){
						nextIMG = $('.carouselImg').first();
						$('.carouselImg').removeClass('toLeft');
					}
					
					var newID = nextIMG.data('id');
					
					currentCtrl = $('.carouselCtrl.' + newID);
					currentCtrl.addClass('active');
					nextIMG.addClass('active').removeClass('toLeft');
						
				}
				
				var transitionTimer = setInterval(makeTransitionFN, 4000);
				
				$('.carouselWrap').on({
					mouseenter: function(){
						clearInterval(transitionTimer);
					},
					mouseleave: function(){
						transitionTimer = setInterval(makeTransitionFN, 4000);
					}
				});
				
				$('.carouselCtrl').on('click', function(){
					
					var clickedCtrl = $(this),
						clickedID = clickedCtrl.data('id'),
						prevActiveID = $('.carouselCtrl.active').data('id');
						
						$('.carouselCtrl.active').removeClass('active');
						clickedCtrl.addClass('active');
						
						if(clickedID < prevActiveID){
							for(var init = clickedID; init < prevActiveID; init++){
								$('.carouselImg.' + init).removeClass('toLeft');
							}	
						}
						
						else{
							for(var init = prevActiveID; init < clickedID; init++){
								$('.carouselImg.' + init).addClass('toLeft');
							}	
						}
						
						$('.carouselImg.active').removeClass('active');
						$('.carouselImg.' + clickedID).addClass('active');
				});		
			</script>
			
			
			
			
			
		<div class="hmFeatGWrap group">

				<div class="featContWrap transTh group hmFeatStore active">
						
					<h4 class="featTab" data-tab="hmFeatStore"><i class="fa fa-shopping-cart"></i> Tiendas recomendadas</h4>
					<?php 
					$query = $con->prepare("SELECT * FROM `usuario` 
					WHERE `activo` = 1
					AND `suspendido` = 0
					AND `baneado` = 0
					AND idtipousuario = 2
					AND idusuario in (
					SELECT idusuario from certificacion where estado = 'A'
					)
					AND idusuario in 
					(
					SELECT idusuario from producto
					WHERE  borrado = 0
					and activo = 1
					)
					AND idusuario not in (2,4)
					order by RAND()
					LIMIT 0,1;");
			        $query->execute();
			        $usuarioEspecial = $query->fetch(PDO::FETCH_OBJ);
					$link = $sM->remove_accents($usuarioEspecial->nombretienda);
					$link = str_replace("-", " ", $link);
					$link = preg_replace('/\s\s+/', ' ', $link);
					$link = str_replace(" ", "-", $link);
					$link = strtolower($link);
					$link = preg_replace('/[^A-Za-z0-9\-]/', '', $link);
	 				#					AND idusuario not in (:idusuarioespecial)
					$query = $con->prepare("
					SELECT * FROM `usuario` 
					WHERE `activo` = 1
					AND `suspendido` = 0
					AND `baneado` = 0
					AND idtipousuario = 2
					AND idusuario not in (2,4)
					AND idusuario in 
					( 
					SELECT idusuario from producto
					WHERE  borrado = 0
					and activo = 1
					)
					and idusuario in 
					(
						SELECT idusuario FROM tiendapub
						WHERE fecha_creacion + INTERVAL duracion DAY >= curdate()
						and terminado = 0
					)
					order by RAND()
					LIMIT 0,6");
			        $query->bindValue(':idusuarioespecial',$usuarioEspecial->idusuario, PDO::PARAM_INT);
			        $query->execute();
			        $usuarios= $query->fetchAll(PDO::FETCH_OBJ);
					$link = $sM->remove_accents($usuarioEspecial->nombretienda);
					$link = str_replace("-", " ", $link);
					$link = preg_replace('/\s\s+/', ' ', $link);
					$link = str_replace(" ", "-", $link);
					$link = strtolower($link);
					$link = preg_replace('/[^A-Za-z0-9\-]/', '', $link);
					foreach($usuarios as $u)
					{
						$link = $sM->remove_accents($u->nombretienda);
						$link = str_replace("-", " ", $link);
						$link = preg_replace('/\s\s+/', ' ', $link);
						$link = str_replace(" ", "-", $link);
						$link = strtolower($link);
						$link = preg_replace('/[^A-Za-z0-9\-]/', '', $link);
						?>
						<a href="<?php echo $link ?>-<?php echo $u->idusuario ?>/" class="hmFeatItm stores" style="background:url(
							<?php 
							if(!empty($u->banner1))
							{
								echo "'images/banners/cr/$u->banner1'";
								
							}
							else {
								echo 'images/resources/defaultBanner.png';								
							}
							?>
							
							
							
							); background-size: cover; background-position: center center;">
							<span class="featuredPreview storePreview">
								<?php 
								if(empty($u->imagen))
								{
									?>
									<img src="images/resources/storePNG-100.png" >
									<?php
									
								}
								else {
									?>
									<img src="images/profile/cr/<?php echo $u->imagen ?>" >
									<?php	
								}
								?>
								
							</span>
							<span class="ftItmName ftStoreName"><?php echo $u->nombretienda ?></span>
						</a>
						<?php
					}
					?>
				</div>

				<div class="featContWrap transTh group hmFeatAds">
					
					<h4 class="featTab" data-tab="hmFeatAds"><i class="fa fa-tags"></i> Artículos destacados</h4>
					<?php 
						 $query = $con->prepare("SELECT pp.*, p.nombre, p.precio, p.preciooferta, p.enoferta, ip.imagen, p.moneda
												FROM productopub pp
												JOIN producto p 
												on p.idproducto = pp.idproducto
												JOIN (select idproducto, max(`imagen`) as imagen from imagenproducto group by idproducto order by principal desc, fecha_creacion asc) ip
												on ip.idproducto = pp.idproducto
												WHERE pp.terminado = 0
												and pp.fecha_creacion + INTERVAL duracion DAY >= curdate()
												and p.borrado = 0
												and p.activo = 1
												and p.enoferta = 1
												order by rand()
												limit 0,1
												");
							$query->execute();
							$productoEspecial = $query->fetch(PDO::FETCH_OBJ);
							if(empty($productoEspecial))
							{
								$query = $con->prepare("SELECT  p.idproducto ,p.nombre, p.precio, p.preciooferta, p.enoferta, ip.imagen, p.moneda
												FROM  producto p 
												LEFT JOIN (select idproducto, max(`imagen`) as imagen from imagenproducto group by idproducto order by principal desc, fecha_creacion asc) ip
												on ip.idproducto = p.idproducto
												WHERE  p.borrado = 0
												and p.activo = 1
												and p.enoferta = 0
												order by rand()
												limit 0,1
												");
								$query->execute();
								$productoEspecial = $query->fetch(PDO::FETCH_OBJ);
							}

						$link = $sM->remove_accents($productoEspecial->nombre);
						$link = str_replace("-", " ", $link);
						$link = preg_replace('/\s\s+/', ' ', $link);
						$link = str_replace(" ", "-", $link);
						$link = strtolower($link);
						$link = preg_replace('/[^A-Za-z0-9\-]/', '', $link);

					$query = $con->prepare('SELECT  p.idproducto ,p.nombre, p.precio, p.preciooferta, p.enoferta, ip.imagen, p.moneda
												FROM  producto p 
												JOIN (select idproducto, max(`imagen`) as imagen from imagenproducto group by idproducto order by principal desc, fecha_creacion asc) ip
												on ip.idproducto = p.idproducto
												WHERE  p.borrado = 0
												and p.activo = 1
												and p.enoferta = 0
												order by rand()
												limit 0,6
												');
								$query->bindValue(':idproductoespecial', $productoEspecial->idproducto, PDO::PARAM_INT);
								$query->execute();
								$productos = $query->fetchAll(PDO::FETCH_OBJ);
							foreach($productos as $p)
							{
								$link = $sM->remove_accents($p->nombre);
								$link = str_replace("-", " ", $link);
								$link = preg_replace('/\s\s+/', ' ', $link);
								$link = str_replace(" ", "-", $link);
								$link = strtolower($link);
								$link = preg_replace('/[^A-Za-z0-9\-]/', '', $link);
								?>
								<div class="hmFeatItm articles">
									<a class="featuredPreview itemPreview" href="articulo/<?php echo $link ?>-<?php echo $p->idproducto ?>">
										<img src="images/productos/thumb150/<?php echo $p->imagen ?>" >
										<i class="helper"></i>
									</a>
									<a class="ftItmName" href="articulo/<?php echo $link ?>-<?php echo $p->idproducto ?>"><?php echo $p->nombre ?></a>
									<span class="featuredItemPrice <?php echo $p->enoferta == 1 ? "enoferta" : ""; ?>"><?php echo $p->moneda ?>$ <?php echo number_format($p->enoferta == 1 ? $p->preciooferta : $p->precio,2,'.',',') ?>
									</span>
									<?php 
									if($p->enoferta == 1)
									{
										?>
										<br />
										<span class="priceDisc">-<?php echo number_format(100-(($p->preciooferta / $p->precio) * 100), 2) ?>%</span>
										<?php	
									}
									?>
								</div>
								<?php
							}
					?>
				</div>
					<div class="featContWrap transTh group hmFeatAds">
					
					<h4 class="featTab" data-tab="hmFeatAds"><i class="fa fa-usd"></i> Ofertas destacados</h4>
					<?php 
						 $query = $con->prepare("SELECT pp.*, p.nombre, p.precio, p.preciooferta, p.enoferta, ip.imagen, p.moneda
												FROM productopub pp
												JOIN producto p 
												on p.idproducto = pp.idproducto
												JOIN (select idproducto, max(`imagen`) as imagen from imagenproducto group by idproducto order by principal desc, fecha_creacion asc) ip
												on ip.idproducto = pp.idproducto
												WHERE pp.terminado = 0
												and pp.fecha_creacion + INTERVAL duracion DAY >= curdate()
												and p.borrado = 0
												and p.activo = 1
												and p.enoferta = 1
												order by rand()
												limit 0,1
												");
							$query->execute();
							$productoEspecial = $query->fetch(PDO::FETCH_OBJ);
							if(empty($productoEspecial))
							{
								$query = $con->prepare("SELECT  p.idproducto ,p.nombre, p.precio, p.preciooferta, p.enoferta, ip.imagen, p.moneda
												FROM  producto p 
												LEFT JOIN (select idproducto, max(`imagen`) as imagen from imagenproducto group by idproducto order by principal desc, fecha_creacion asc) ip
												on ip.idproducto = p.idproducto
												WHERE  p.borrado = 0
												and p.activo = 1
												and p.enoferta = 1
												order by rand()
												limit 0,1
												");
								$query->execute();
								$productosOfertaEspecial = $query->fetch(PDO::FETCH_OBJ);
							}

						$link = $sM->remove_accents($productoEspecial->nombre);
						$link = str_replace("-", " ", $link);
						$link = preg_replace('/\s\s+/', ' ', $link);
						$link = str_replace(" ", "-", $link);
						$link = strtolower($link);
						$link = preg_replace('/[^A-Za-z0-9\-]/', '', $link);

					$query = $con->prepare('SELECT  p.idproducto ,p.nombre, p.precio, p.preciooferta, p.enoferta, ip.imagen, p.moneda
												FROM  producto p 
												JOIN (select idproducto, max(`imagen`) as imagen from imagenproducto group by idproducto order by principal desc, fecha_creacion asc) ip
												on ip.idproducto = p.idproducto
												JOIN productopub pp
												ON p.idproducto = pp.idproducto
												WHERE  p.borrado = 0
												and p.activo = 1
												and pp.fecha_creacion + INTERVAL pp.duracion DAY >= curdate()
												and p.enoferta = 1
												order by rand()
												limit 0,6
												');
								$query->bindValue(':idproductoespecial', $productosOfertaEspecial->idproducto, PDO::PARAM_INT);
								$query->execute();
								$productosOferta = $query->fetchAll(PDO::FETCH_OBJ);
							foreach($productosOferta as $p)
							{
								$link = $sM->remove_accents($p->nombre);
								$link = str_replace("-", " ", $link);
								$link = preg_replace('/\s\s+/', ' ', $link);
								$link = str_replace(" ", "-", $link);
								$link = strtolower($link);
								$link = preg_replace('/[^A-Za-z0-9\-]/', '', $link);
								?>
								<div class="hmFeatItm articles">
									<a class="featuredPreview itemPreview" href="articulo/<?php echo $link ?>-<?php echo $p->idproducto ?>">
										<img src="images/productos/thumb150/<?php echo $p->imagen ?>" >
										<i class="helper"></i>
									</a>
									<a class="ftItmName" href="articulo/<?php echo $link ?>-<?php echo $p->idproducto ?>"><?php echo $p->nombre ?></a>
									<span class="featuredItemPrice <?php echo $p->enoferta == 1 ? "enoferta" : ""; ?>"><?php echo $p->moneda ?>$ <?php echo number_format($p->enoferta == 1 ? $p->preciooferta : $p->precio,2,'.',',') ?>
									</span>
									<?php 
									if($p->enoferta == 1)
									{
										?>
										<br />
										<span class="priceDisc">-<?php echo number_format(100-(($p->preciooferta / $p->precio) * 100), 2) ?>%</span>
										<?php	
									}
									?>
								</div>
								<?php
							}
					?>
				</div>
				
		</div>	

<script type="text/javascript">
	
		var phrases = new Array ("Smartphones", "Laptops", "Prendas de vestir", "Relojes", "Bebés", "Audífonos", "Electrónicos", "Mochilas", "Automóviles", "Ofertas", "Oferta del día", "Cámaras", "Cargadores", "Repuestos", "Materiales de oficina", "Ropa", "Jeans", "Reparaciones");
		
		function timeFN(){
			var newPhrase = phrases[Math.floor(Math.random() * phrases.length)];
			$('.hmSrchWrap .hmSrchTxt').attr('placeholder', newPhrase);
		}
		
		var timeInterval = setInterval(timeFN, 1200);
		
		$('.hmSrchTxt').on('focus', function(){
			clearInterval(timeInterval);
		}).on('blur', function(){
			timeInterval = setInterval(timeFN, 1200);
		});
		
		

</script>


<?php 
	require_once('templates/foo.php');

?>