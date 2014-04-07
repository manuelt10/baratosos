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
	
	$query = $con->prepare("SELECT p.* , u.nombre as nombre_usuario, u.nombretienda, c.`estado`, u.correo, u.nombretienda, u.telefono1, u.telefono2, u.suspendido, u.imagen, pf.favoritos
							FROM producto p 
							JOIN usuario u
							ON u.idusuario = p.idusuario
							LEFT JOIN
							(SELECT idproducto, count(*) as favoritos from productos_fav group by idproducto) pf
							ON pf.idproducto = p.idproducto
							LEFT JOIN 
							certificacion c
							on c.idusuario = u.idusuario
							and c.`estado` = 'A'
							WHERE p.idproducto = :idproducto");
	$query->bindValue(':idproducto', $_GET["id"], PDO::PARAM_INT);
	$query->execute();
	$producto = $query->fetch(PDO::FETCH_OBJ);
	
	$query = $con->prepare('SELECT * FROM imagenproducto where idproducto = :idproducto order by principal desc, fecha_creacion asc');
	$query->bindValue(':idproducto', $_GET["id"], PDO::PARAM_INT);
	$query->execute();
	$imagenproducto = $query->fetchAll(PDO::FETCH_OBJ);
	
	$query = $con->prepare("select cp.idcaracteristica_producto, cp.descripcion, count(ic.imagen) as cantidadimagen from 
							caracteristica_producto cp 
							join imagencaracteristica ic
							on ic.idcaracteristica_producto = cp.idcaracteristica_producto
							where cp.idproducto = :idproducto
							and cp.descripcion <> ''
							group by cp.idcaracteristica_producto, cp.descripcion
							having count(ic.imagen) > 0
							");
	$query->bindValue(':idproducto', $_GET["id"], PDO::PARAM_INT);
	$query->execute();
	$caracteristica_producto = $query->fetchAll(PDO::FETCH_OBJ);
	/*
	if(($producto->activo == 0) or ($producto->suspendido == 1) or ($producto->borrado == 0))
	{
		header('location: http://tumall.dohome');
	}*/
	
	require_once('templates/headgeneral.php');

	$URL = "$_SERVER[REQUEST_URI]";
?>

		<div class="genContentWrap items-sect-wrap group">
				<?
				if(($producto->activo == 0) or  ($producto->borrado == 1))
					{
						?>
						<p class="deletedItemMsg">Este artículo ya no está disponible.</p>
						<?php
					}
					?>
				<div id="itemDetailsWrapper" class="group">
					<div class="itemPreviewsWrapper">
						<div class="itemMainPrev">
							
							<?php 
							if(!empty($imagenproducto[0]->imagen))
							{
								?>
								<img src="images/productos/<?php echo $imagenproducto[0]->imagen ?>" alt="<?php echo $producto->nombre ?>" />
								<?php 
							}
							
							else{
								
							
							?>
							
								<img src="images/NoImage.png" alt="No image">
							
							<?php
							
							}
							
							?>
							
						</div>
						<div class="itemSmallerPrevWrapper">
							<?php 
							$smallImgCount = 0;
							
							if(count($imagenproducto) > 1){
								foreach($imagenproducto as $iP)
								{
									list($width, $height) = getimagesize("images/productos/thumb150/".$iP->imagen."");
									$baseDimm = 50;
				
									if($height > $width){
										$ratio = $width/$baseDimm;
										$modVal = $height;
									}
									
									else{
										$ratio = $height/$baseDimm;
										$modVal = $width;
									}
									
									$modVal /= $ratio; 
									$pos = ($modVal - $baseDimm)/2
								
									?> 
									<div class="smallPrev <?php echo $smallImgCount == 0 ? "selectedItem" : "" ?>">
										<img <?php if($height > $width){  ?> width="<?php echo $baseDimm; ?>" height="<?php echo $modVal; ?>" style="top: -<?php echo $pos; ?>px;"  <?php }  else{ ?> height="<?php echo $baseDimm; ?>" width="<?php echo $modVal; ?>" style="left: -<?php echo $pos; ?>px;" <?php } ?> src="images/productos/thumb150/<?php echo $iP->imagen ?>" alt="<?php echo $producto->nombre ?>" />
									</div>
									<?php
								$smallImgCount++;
								}
							}
							?>
							
						</div>
									
					</div>

					<div class="itemInfoWrapper">	
						<h2 class="itemName"><?php echo $producto->nombre ?></h2>
						
						<span class=" itemPrice <?php echo $producto->enoferta == 1 ? "enoferta" : ""; ?>"><?php echo $producto->moneda ?>$ <?php echo $producto->enoferta == 1 ?  number_format($producto->preciooferta,2,'.',',') : number_format($producto->precio,2,'.',',')  ?></span>
						<?php 
						if($producto->enoferta == 1)
						{
							?>
							<span class="origPrice"><?php echo $producto->moneda ?>$ <?php echo number_format($producto->precio,2,'.',',') ?></span>
							<span class="priceDisc">-<?php echo number_format(100-(($producto->preciooferta / $producto->precio) * 100), 2) ?>%</span>
							<?php	
						}
						?>
						
						<span class="datePosted">Publicado el: <?php echo date("d-m-Y", strtotime($producto->fecha_creacion)); ?></span>
						<span class="itemID">art-<a href="#"><?php echo $producto->idproducto ?></a></span>
						<?php 
						if(count($caracteristica_producto))
						{
						?>
							<label>Otras opciones: </label>
							<select class="caractProduct">
								<option value="0"> Predeterminada</option>
								<?php 
								foreach($caracteristica_producto as $cp)
								{
																			?>
										<option value="<?php echo $cp->idcaracteristica_producto ?>"><?php echo $cp->descripcion ?></option>
										<?php
								}
								?>
							</select>
						<?php
						}
						?>
						<div class="actionBar">
							<span class="actBarTitle">Compartir y agregar a favoritos:</span>
							<a href="https://twitter.com/share?url=http%3A%2F%2Fwww%2Etumall%2Edo<?php echo $URL; ?>
								&text=Ver <?php echo $producto->nombre . ", de " . $producto->nombretienda;?>
								&via=TuMall" 
								class="shareToBtn socialShare twettBtn transTw">
								<i class="fa fa-twitter"></i>
							</a>
							
							<a href="https://www.facebook.com/sharer.php?
								s=100
								&p[url]=http%3A%2F%2Fwww%2Etumall%2Edo<?php echo $URL; ?>
								&p[title]=Ver <?php echo $producto->nombre . ", de " . $producto->nombretienda;?> en Tu Mall
								&p[images][0]=http://www.tumall.doimages/productos/<?php echo $imagenproducto[0]->imagen; ?>
								&p[summary]="
							class="shareToBtn socialShare fbShareBtn transTw">
							<i class="fa fa-facebook"></i>
							</a>								
							
							<a href="mailto:?subject=Mira éste artículo que encontré en Tu Mall&amp;body=Hola, mira el artículo que encontré en Tu Mall: <?php echo $producto->nombre."   http://tumall.do".$URL; ?>" class="shareToBtn messageBtn transTw"><i class="fa fa-envelope"></i></a>
							<?php 
							$query = $con->prepare('SELECT * FROM `productos_fav` where idusuario = ?
													and idproducto = ?
							');
							$query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
							$query->bindValue(2,$producto->idproducto, PDO::PARAM_INT);
							$query->execute();
							$esFavorito = $query->fetchAll(PDO::FETCH_OBJ);
							?>
							<div class="favWrap <?php echo count($esFavorito) > 0 ? "isFavorited" : "" ?>">
								<span class="cantidadFav"><?php echo $producto->favoritos > 0 ? $producto->favoritos : "0" ?></span>
								<span class="shareToBtn faveBtn">
									
										<i class="fa fa-star"></i>
								</span>
							</div>
							
							<?php 
								if($usuario->idtipousuario == 1)
								{
									
									?>
								<script>
										$('.favWrap').click(function(){
											
											if($(this).hasClass('isFavorited')){
												$(this).removeClass('isFavorited');
											}
											
											else{
												$(this).addClass('isFavorited');
											}
											
											$.ajax({
												type : "POST",
												url  : "phpfn/favorite_products.php",
												data : {idusuario : <?php echo $session["usuario"] ?>, idproducto : <?php echo $producto->idproducto ?>}
											}).done(function(html)
											{
												$('.cantidadFav').html(html);
											})
										})
									</script>
									<?php
								}
								?>
						</div>
					</div>
					
					<?php 
						$link = $sM->remove_accents($producto->nombretienda);
						$link = str_replace("-", " ", $link);
						$link = preg_replace('/\s\s+/', ' ', $link);
						$link = str_replace(" ", "-", $link);
						$link = strtolower($link);
						$link = preg_replace('/[^A-Za-z0-9\-]/', '', $link);
					
					?>
					<div class="sellerInfoWrapper">
						<a class="sellerLogo" href="<?php echo $link . "-" . $producto->idusuario ?>/">
							<?php 
							if(!empty($producto->imagen)){
							?>
								<img class="sellerLogoImg" src="images/profile/cr/<?php echo $producto->imagen ?>" />
							<?php	
							}
							else{
								?>
								
								<img class="sellerLogoImg" src="images/resources/storePNG-100.png" />
								
								<?php 
							}
							
							?>
						</a>
						
						<?php 
						if($producto->estado == 'A')
						{
							?>
							<div class="sellerType">
								<img src="images/certVal.png" alt="Tienda certificada" title="Tienda Certificada (CFT)"  />
							</div>
							<?php
						}
						?>
						
						<a href="<?php echo $link . "-" . $producto->idusuario ?>/" class="sellerDet sellerName"><?php echo $producto->nombretienda ?></a>
						<span class="sellerInfoHead">Datos del vendedor</span>
						<a href="mailto:<?php echo $producto->correo ?>" class="sellerDet actionButton sendMessage"><?php echo $producto->correo ?> <i class="fa fa-envelope"></i></a>
						<span class="sellerDet actionButton phone"><?php echo empty($producto->telefono1) ? $producto->telefono2 : $producto->telefono1; ?><i class="fa fa-phone"></i></span>
						<?php 
						$query = $con->prepare('SELECT ur.idredes_sociales, ur.idusuario, ur.nombre_red, rs.preurl, rs.descripcion, rs.class
												FROM usuarioredes ur
												JOIN redes_sociales rs
												ON ur.idredes_sociales = rs.idredes_sociales
												WHERE rs.activo = 1
												AND ur.idusuario = :idusuario');
						$query->bindValue(':idusuario', $producto->idusuario, PDO::PARAM_INT);
						$query->execute();
						$usuariosredes = $query->fetchAll(PDO::FETCH_OBJ);
						
						?>
						<?php 
						foreach($usuariosredes as $ur)
						{
							if(!empty($ur->nombre_red))
							{
							?>
								<span class="sellerDet  <?php echo strtolower($ur->descripcion); ?>"><?php echo $ur->nombre_red ?><i class="fa fa-<?php echo strtolower($ur->descripcion) ?>"></i></span>
							<?php
							}
						}
						?>
					</div>

				</div> <!--item top area -->
				
				<h3 class="itemDescrTitle">Descripción del artículo</h3>
				<div id="itemDescriptionWrapper"> 
					<div id="itemDescription" class="content"><?php echo $producto->descripcion; ?></div>
				</div><!--item bottom area -->
				
<div class="featItmWrap itemsFeatItmWrap">
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
						limit 0,6
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
</div>	
<div class="adsWrap">
		<?php 
 	$query = $con->prepare("SELECT * FROM `anunciopub`
							WHERE  fecha_creacion + INTERVAL duracion DAY >= curdate()
							and terminado = 0
							and posicion = 'banner-right'
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

<script> /* window pop-ups for social sharing */
	$('.socialShare').click(function(event){
		var URL = $(this).attr('href');
		window.open(URL, 'targetWindow', 'toolbar=no, location=no, status=no, scrollbars=yes, resizable=yes, width=700, height=450, top=50, left=200');
		return false;		
	});
</script>	

<script>
	$('.caractProduct').change(function(){
		var idCar = $(this).val();
		var prod = <?php echo $_GET["id"] ?>;
		$.ajax({
			type : "POST", 
			url  : "phpfn/showimgcaract",
			data : {idCaracteristica : idCar, idproducto : prod}
		}).done(function(html)
		{
			$('.itemPreviewsWrapper').html(html);
		})
	})
</script>
			
<script> //selects image to display on hover
	$('#itemDetailsWrapper').on('mouseenter', '.smallPrev', function(event){
		var hoverElem = $(this).children('img'),
			previewDir = hoverElem.attr('src').split("/")[3],
			previewSrc = hoverElem.attr('src').split("/")[5];
			
			$('.smallPrev').removeClass('selectedItem');
			$(this).addClass('selectedItem');
			
			$('.previewImage').removeClass('active');
			
			if($(this).hasClass('alt')){
				$('.itemMainPrev img').attr('src', 'images/' + previewDir + '/' +previewSrc);
			}
			
			else{
				$('.itemMainPrev img').attr('src', 'images/productos/' +previewSrc);
			}
			
	});
	
</script>			
			
			
			
<?php 
	require_once('templates/foo.php');
?>