<?php 
session_start();
if(!empty($_SESSION["usuarioReal"]))
{
	$_SESSION["usuario"] = $_SESSION["usuarioReal"];
	unset($_SESSION["usuarioReal"]);
}
$session = $_SESSION;
unset($_SESSION["error"]);
unset($_SESSION["buscarTienda"]);
session_write_close();

require_once('phpfn/stringmanager.php');
require_once('phpfn/cndb.php');
$sM = new stringManager();
$con = conection();

$query = $con->prepare('SELECT * FROM `usuario` where idusuario = ?');
$query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
$query->execute();
$usuario = $query->fetch(PDO::FETCH_OBJ);

$query = $con->prepare('SELECT * FROM categoria1 WHERE idcategoria1 IN (SELECT idcategoria1 FROM producto)');
$query->execute(); 
$categorias = $query->fetchAll(PDO::FETCH_OBJ);

require_once('templates/headgeneral.php');
?>

<div class="genContentWrap group">

<?php 
	$query = $con->prepare("SELECT * FROM `anunciopub`
						WHERE  fecha_creacion + INTERVAL duracion DAY >= curdate()
						and terminado = 0
						and posicion like 'banner-top'
						order by rand()
						limit 0,3");
						$query->execute();
						$anuncio = $query->fetchAll(PDO::FETCH_OBJ);

if(!empty($anuncio[0]->image))
{
	?>
	<div class="adTop ads">
	<a href="<?php echo $anuncio[0]->link ?>"><img src="images/publicidad/<?php echo $anuncio[0]->image ?>"></a>
	</div>
	<?php 
}
?>

	<div id="categoryFilterWrapper">
		<h2 class="main-heading category-heading">Categorías</h2>
		<div class="categoryPane transTw">
			<?php 
			foreach($categorias as $c1)
				{
					?> 
					<h3 class="resultsSubCategory">
					<?php
						$link = $sM->remove_accents($c1->descripcion);
						$link = str_replace("-", " ", $link);
						$link = preg_replace('/\s\s+/', ' ', $link);
						$link = str_replace(" ", "-", $link);
						$link = strtolower($link);
						$link = preg_replace('/[^A-Za-z0-9\-]/', '', $link);
						?>
						<a href="lista/categoria/<?php echo $link . "-" . $c1->idcategoria1 ?>/"><?php echo $c1->descripcion ?></a>
					</h3>
						<?php
				}
			
			?>
		</div>
	</div>
	
	<div class="newItemsWrap group">

				<div class="featContWrap transTh group hmFeatStore active">
						
					<h4 class="featTab stores" data-tab="hmFeatStore"><i class="fa fa-shopping-cart"></i> Tiendas Nuevas</h4>
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
					order by fecha_creacion desc
					LIMIT 0,12");
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
					
					<h4 class="featTab articles" data-tab="hmFeatAds"><i class="fa fa-tags"></i> Artículos Recientes</h4>
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
												order by fecha_creacion desc
												limit 0,12
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
								<div class="featItem">
									<?php 
									if($p->enoferta == 1)
									{
										?>
										<br />
										<span class="priceDisc">-<?php echo number_format(100-(($p->preciooferta / $p->precio) * 100), 2) ?>%</span>
										<?php	
									}
									?>
									<a class="imgMask" href="articulo/<?php echo $link ?>-<?php echo $p->idproducto ?>">
										<span class="maskMidle">
											<img src="images/productos/thumb200/<?php echo $p->imagen ?>" >
										</span>
									</a>
									<a class="featItmName" href="articulo/<?php echo $link ?>-<?php echo $p->idproducto ?>"><?php echo $p->nombre ?></a>
									<span class="featItmPric <?php echo $p->enoferta == 1 ? "enoferta" : ""; ?>"><?php echo $p->moneda ?>$ <?php echo number_format($p->enoferta == 1 ? $p->preciooferta : $p->precio,2,'.',',') ?>
									</span>
									
								</div>
								<?php
							}
					?>
				</div>
					<div class="featContWrap transTh group hmFeatAds">
					
					<h4 class="featTab offers" data-tab="hmFeatAds"><i class="fa fa-tags"></i> Últimas ofertas</h4>
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
												WHERE  p.borrado = 0
												and p.activo = 1
												and p.enoferta = 1
												order by fecha_creacion desc
												limit 0,12
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
	
	<div class="adsWrap">
		<?php 
 	$query = $con->prepare("SELECT * FROM `anunciopub`
							WHERE  fecha_creacion + INTERVAL duracion DAY >= curdate()
							and terminado = 0
							and posicion like 'banner-right'
							order by rand()
							limit 0,3");
							$query->execute();
							$anuncio = $query->fetchAll(PDO::FETCH_OBJ);
		?>
		
			<?php 
			if(!empty($anuncio[0]->image))
			{
				?>
				<a class="top ads" href="<?php echo $anuncio[0]->link ?>"><img src="images/publicidad/<?php echo $anuncio[0]->image ?>"></a>
				<?php 
			}
			?>
		
		
			<?php 
			if(!empty($anuncio[1]->image))
			{
				?>
				<a class="mid ads" href="<?php echo $anuncio[1]->link ?>"><img src="images/publicidad/<?php echo $anuncio[1]->image ?>"></a>
				<?php 
			}
			?>
		
		
			<?php 
			if(!empty($anuncio[2]->image))
			{
				?>
				<a class="bottom ads" href="<?php echo $anuncio[2]->link ?>"><img src="images/publicidad/<?php echo $anuncio[2]->image ?>"></a>
				<?php
			}
			?>
		
	</div>
	<!--
<div class="featItmWrap">
	<h3 class="featuredItemsHead">Artículos destacados</h3>	
	<?php 
	 $query = $con->prepare('SELECT pp.*, p.nombre, p.precio, p.preciooferta, p.enoferta, ip.imagen, p.moneda
							FROM productopub pp
							JOIN producto p 
							on p.idproducto = pp.idproducto
							LEFT JOIN (select idproducto, max(`imagen`) as imagen from imagenproducto group by idproducto order by principal desc, fecha_creacion asc) ip
							on ip.idproducto = pp.idproducto
							WHERE pp.terminado = 0
							and pp.fecha_creacion + INTERVAL duracion DAY >= curdate()
							and p.borrado = 0
							and p.activo = 1
							order by rand()
							limit 0,4
							');
		$query->execute();
		$productosOferta = $query->fetchAll(PDO::FETCH_OBJ);
		foreach($productosOferta as $pO)
		{
			$link = $sM->remove_accents($pO->nombre);
			$link = str_replace("-", " ", $link);
			$link = preg_replace('/\s\s+/', ' ', $link);
			$link = str_replace(" ", "-", $link);
			$link = strtolower($link);
			$link = preg_replace('/[^A-Za-z0-9\-]/', '', $link);
			?>
			<div class="featItem">
				<?php 
				if($pO->enoferta == 1)
				{
				?>
					<span class="priceDisc">-<?php echo number_format(100-(($pO->preciooferta / $pO->precio) * 100), 2) ?>%</span>
				<?php	
				}
				?>
				<a href="articulo/<?php echo $link."-".$pO->idproducto; ?>" class="imgMask">
					<span class="maskMidle">
							<?php 
							if(empty($pO->imagen))
							{
							?>
								<img src="http://tumall.doimages/NoImage.png" alt="No image" title="No image">
							<?php 
							}
							else
							{
							?>
								<img src="images/productos/thumb150/<?php echo $pO->imagen ?>" title="<?php echo $pO->nombre; ?>" alt="<?php echo $pO->nombre; ?>" >
							<?php 
							}
							?>
					</span>
				</a>
				<a class="featItmName" href="articulo/<?php echo $link."-".$pO->idproducto; ?>"><?php echo $pO->nombre ?></a>
				<span class="featItmPric <?php echo $pO->enoferta == 1 ? "enoferta" : ""; ?>"><?php echo $pO->moneda ?>$ <?php echo number_format($pO->enoferta == 1 ? $pO->preciooferta : $pO->precio,2,'.',',') ?></span>
			</div>
			<?php
		}
	?>
			
	
	</div>
-->
</div>	

<script> //show/hides category list on mobile
	$('#categoryFilterWrapper').click(function(event){
		$('.categoryPane').addClass('shown');
		$('.categCloseBtn').remove();
		$(this).append('<i class="categCloseBtn fa fa-times-circle"></i>');
		
		event.stopPropagation();
	});
	
	$('html').click(function(event){
		$('.categoryPane').removeClass('shown');
		$('.categCloseBtn').remove();
	});
	
	$('#categoryFilterWrapper').on('click', '.categCloseBtn', function(event){
		$('.categoryPane').removeClass('shown');
		$('.categCloseBtn').remove();
		event.stopPropagation();
	});
	
</script>

<?php 
	require_once('templates/foo.php');

?>