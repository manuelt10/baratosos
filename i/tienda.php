<?php 

session_start();
if(!empty($_SESSION["usuarioReal"]))
{
	$_SESSION["usuario"] = $_SESSION["usuarioReal"];
	unset($_SESSION["usuarioReal"]);
}
$session = $_SESSION;
unset($_SESSION["error"]);
session_write_close();
#$_GET["buscar"] = $session["buscarTienda"];

?>

<?php 
require_once('phpfn/cndb.php');
$con = conection();
$query = $con->prepare('SELECT * FROM `usuario` where idusuario = ?');
$query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
$query->execute();
$usuario = $query->fetch(PDO::FETCH_OBJ);
require_once('templates/headgeneral.php');
require_once('phpfn/stringmanager.php');
if(empty($session["itemsQty"]))
{
	$page_size = 20;
}
else {
	$page_size = $session["itemsQty"];
}


if($session["orderby"] == 1)
{
	$orderby = 'order by p.precio desc';
}
else if($session["orderby"] == 2)
{
	$orderby = 'order by p.precio asc';
}
else if($session["orderby"] == 3)
{
	$orderby = 'order by p.fecha_creacion desc';
}
else if($session["orderby"] == 4)
{
	$orderby = 'order by p.fecha_creacion asc';
}

$page = $_GET["page"];
		if(!$page)
		{
			$start = 0;
			$page  = 1;
		}
		else {
			$start = ($page - 1) * $page_size; 
		}
		 
echo "<script>console.log(' id: " . $_GET["id"] .  "');</script>";	
echo "<script>console.log(' buscar: " . $_GET["buscar"] .  "');</script>";
echo "<script>console.log(' categoria1: " . $_GET["categoria1"] .  "');</script>";
echo "<script>console.log(' categoria2: " . $_GET["categoria2"] .  "');</script>";
echo "<script>console.log(' page: " . $_GET["page"] .  "');</script>";

$query = $con->prepare("SELECT u.*, uf.favoritos, c.`estado` FROM `usuario` u
						LEFT JOIN (
						select idusuario_favorito, count(idusuario) as favoritos from usuarios_fav group by idusuario_favorito
						) uf
						ON uf.idusuario_favorito = u.idusuario 
						LEFT JOIN 
						certificacion c
						on c.idusuario = u.idusuario
						and c.`estado` = 'A'
						where u.idusuario = ?");
$query->bindValue(1,$_GET["id"], PDO::PARAM_INT);
$query->execute();
$usuarioTienda = $query->fetch(PDO::FETCH_OBJ);
$sM = new stringManageR();
$sql = "SELECT DISTINCT p.*,  i.imagen,  c.`estado`
							FROM producto p
							JOIN usuario u
							ON u.idusuario = p.idusuario
							LEFT JOIN 
							(select idproducto, max(`imagen`) as imagen from imagenproducto group by idproducto order by principal desc, fecha_creacion asc) i
							ON i.idproducto = p.idproducto
							LEFT JOIN 
							certificacion c
							on c.idusuario = u.idusuario
							and c.`estado` = 'A'
							WHERE u.suspendido = 0 
							AND u.baneado = 0
							AND p.activo = 1
							AND p.borrado = 0
							AND p.idusuario = :id
							";
	if(!empty($_GET["buscar"]))
	{
		$sql = $sql . '
					AND (p.nombre like :nombre or p.palabras_claves like :nombre)
		';
	}
	
	if(!empty($_GET["categoria1"]))
	{
		$sql = $sql . '
		AND p.idcategoria1 = :categoria1
		'; 
	}
							
	if(!empty($_GET["categoria2"]))
	{
		$sql = $sql . '
		AND p.idcategoria2 = :categoria2
		'; 
	}
	$query = $con->prepare($sql);
	if(!empty($_GET["categoria1"])) {$query->bindValue(':categoria1',$_GET["categoria1"], PDO::PARAM_INT);}
	if(!empty($_GET["categoria2"])) {$query->bindValue(':categoria2',$_GET["categoria2"], PDO::PARAM_INT);}
	$query->bindValue(':id',$_GET["id"], PDO::PARAM_INT);
	if(!empty($_GET["buscar"]))
	{
		$query->bindValue(':nombre', '%' . $_GET["buscar"] . '%', PDO::PARAM_INT);
	}
	$query->execute();
	$productosTienda = $query->fetchAll(PDO::FETCH_OBJ);
	
	$total = count($productosTienda);
	$total_pages = ceil($total / $page_size);

	
	$query = $con->prepare($sql . ' ' . $orderby .
	' LIMIT ' . $start .','. $page_size);
	$query->bindValue(':id',$_GET["id"], PDO::PARAM_INT);
	if(!empty($_GET["categoria1"])) {$query->bindValue(':categoria1',$_GET["categoria1"], PDO::PARAM_INT);}
	if(!empty($_GET["categoria2"])) {$query->bindValue(':categoria2',$_GET["categoria2"], PDO::PARAM_INT);}
	if(!empty($_GET["buscar"]))
	{
		$query->bindValue(':nombre', '%' . $_GET["buscar"] . '%', PDO::PARAM_INT);
	}
	$query->execute();
	$productosTienda = $query->fetchAll(PDO::FETCH_OBJ);
	$tienda_url = $sM->remove_accents($usuarioTienda->nombretienda);
	$tienda_url = str_replace("-", " ", $tienda_url);
	$tienda_url = preg_replace('/\s\s+/', ' ', $tienda_url);
	$tienda_url = str_replace(" ", "-", $tienda_url);
	$tienda_url = strtolower($tienda_url);
	$tienda_url = preg_replace('/[^A-Za-z0-9\-]/', '', $tienda_url);
?>
			
			
			
			
<div class="genContentWrap group stores">
	
<?php 
	$query = $con->prepare("SELECT * FROM `anunciopub`
						WHERE  fecha_creacion + INTERVAL duracion DAY >= curdate()
						and terminado = 0
						and posicion like 'banner-top'
						order by rand()
						limit 0,1");
						$query->execute();
						$anuncio = $query->fetchAll(PDO::FETCH_OBJ);

	if(!empty($anuncio[0]->image))
	{
		?>
		<div class="adTop ads">
			<a href="<?php echo $anuncio[0]->link ?>"><img src="/images/publicidad/<?php echo $anuncio[0]->image ?>"></a>
		</div>
		<?php 
	}
	?>
	
	<div id="categoryFilterWrapper">
		<h2 class="main-heading category-heading">Categorías</h2>
		<div class="categoryPane transTw"> 
			
			<?php 
			$query = $con->prepare('select * from categoria1
			where idcategoria1 in
			(
				select idcategoria1 
				from producto where idusuario = :id 
				and (nombre like :nombre or palabras_claves like :nombre)
				and borrado = 0
				and activo = 1
			)
			 	');
				$query->bindValue(':id',$_GET["id"], PDO::PARAM_INT);
				$query->bindValue(':nombre', '%' . $_GET["buscar"] . '%');
				$query->execute();
				$categoria1 = $query->fetchAll(PDO::FETCH_OBJ);
			  	foreach($categoria1 as $c1)
				{
						$link = $sM->remove_accents($c1->descripcion);
						$link = str_replace("-", " ", $link);
						$link = preg_replace('/\s\s+/', ' ', $link);
						$link = str_replace(" ", "-", $link);
						$link = strtolower($link);
						$link = preg_replace('/[^A-Za-z0-9\-]/', '', $link);
					?>
					<h3 class="resultsSubCategory">
						<a href="/<?php echo $tienda_url ?>-<?php echo $_GET["id"] ?>/1/<?php echo $link . "-" . $c1->idcategoria1 ?>/
							<?php echo !empty($_GET["buscar"]) ? "" . $_GET["buscar"] . "/": "" ?>"><?php echo $c1->descripcion ?></a>
					</h3>
					<?php 
					$query = $con->prepare('SELECT * FROM `categoria2` where idcategoria1 = :idcat
											and idcategoria2 in
												(
													select idcategoria1 
													from producto where idusuario = :id 
													and (nombre like :nombre or palabras_claves like :nombre)
													and borrado = 0
													and activo = 1
												)
					');
					$query->bindValue(':idcat',$c1->idcategoria1, PDO::PARAM_INT);
					$query->bindValue(':id',$_GET["id"], PDO::PARAM_INT);
					$query->bindValue(':nombre', '%' . $_GET["buscar"] . '%');
					$query->execute();
					$categoria2 = $query->fetchAll(PDO::FETCH_OBJ);
					if(!empty($categoria2))
					{
						?>
						<ul class="resultsCategoryList">
						<?php
						foreach($categoria2 as $c2)
						{
							$link = $sM->remove_accents($c2->descripcion);
							$link = str_replace("-", " ", $link);
							$link = preg_replace('/\s\s+/', ' ', $link);
							$link = str_replace(" ", "-", $link);
							$link = strtolower($link);
							$link = preg_replace('/[^A-Za-z0-9\-]/', '', $link);
							?>
							<li><h4><a href="/<?php echo $tienda_url ?>-<?php echo $_GET["id"] ?>/2/<?php echo $link . "-" . $c2->idcategoria2 ?>/
								<?php echo !empty($_GET["buscar"]) ? "" . $_GET["buscar"] . "/": "" ?>"><?php echo $c2->descripcion ?></a></h4></li>
							<?php
						}
						?>
						</ul>
						<?php
					}
				}
				?>
				<a class="view-more" href="/lista/">Ver otras categorías</a>
		</div>
	</div><!-- close categoryFilterWrapper -->
	
	<div class="resultsDisplayArea">
		<div class="storeDetailsWrap">

			<div id="storeBanner" style="background-image: 
			<?php 
			if(!empty($usuarioTienda->banner1))
			{
				?>
				url('/images/banners/cr/<?php echo $usuarioTienda->banner1 ?>');">
				<?php
			}	
			else{
				?>
					
				url('/images/resources/defaultBanner.png');">
					
				<?php 
			}
			
			?>		
			<?php 
				$query = $con->prepare('SELECT * FROM `usuarios_fav` where idusuario = ?
										and idusuario_favorito = ?
										');
				$query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
				$query->bindValue(2,$_GET["id"], PDO::PARAM_INT);
				$query->execute();
				$esFavorito = $query->fetchAll(PDO::FETCH_OBJ);
			?>
			</div>
			<div class="favWrap <?php echo count($esFavorito) > 0 ? "isFavorited" : "" ?>">
				<span class="cantidadFav"><?php echo $usuarioTienda->favoritos > 0 ? $usuarioTienda->favoritos : "0" ?></span>

				<span class="shareToBtn faveBtn last">
					<i class="fa fa-star"></i>
				</span>
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
								url  : "/phpfn/favorite_store.php",
								data : {idusuario : <?php echo $session["usuario"] ?>, idusuario_favorited : <?php echo $usuarioTienda->idusuario ?>}
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
			<div class="userProfilePic">
				<img src="
				<?php
				if(empty($usuarioTienda->imagen))
				{
					?>
					/images/resources/storePNG-100.png
					<?php
				}
				else
				{
					?>
					/images/profile/cr/<?php echo $usuarioTienda->imagen ?>
					<?php	
				}
				
				?>
				"/>
			</div>
			<?php 
			if($usuarioTienda->estado == 'A')
			{
				?>
				<div class="sellerType">
					<img src="/images/certVal.png" alt="Tienda certificada" title="Tienda Certificada (CFT)"  />
				</div>
				<?php
			}
			?>
			
			
			<h2 class="storeName"><?php echo $usuarioTienda->nombretienda ?></h2>
			<span class="showMoreClicky">Más información</span>
			<?php
			if(!empty($usuarioTienda->descripcion)){
			?>
			<p class="storeDescription storeInfoWrap"><?php echo $usuarioTienda->descripcion ?></p>
			
			<?php
			}
			
			?>
			
			<?php 
			$query = $con->prepare('SELECT ur.idredes_sociales, ur.idusuario, ur.nombre_red, rs.preurl, rs.descripcion, rs.class
									FROM usuarioredes ur
									JOIN redes_sociales rs
									ON ur.idredes_sociales = rs.idredes_sociales
									WHERE rs.activo = 1
									AND ur.idusuario = :idusuario');
			$query->bindValue(':idusuario', $usuarioTienda->idusuario, PDO::PARAM_INT);
			$query->execute();
			$usuariosredes = $query->fetchAll(PDO::FETCH_OBJ);
			
			if(!empty($usuariosredes)){
			
			?>
			
			<div class="storeSocialWrap storeInfoWrap">
				<h4 class="storeAddressTitle">Redes sociales</h4>
				<?php
				
				foreach($usuariosredes as $ur)
				{
					if(!empty($ur->nombre_red))
					{
					?>
						<i class="fa fa-<?php echo strtolower($ur->descripcion) ?>"></i>
						<span class="storeNetDescr <?php echo strtolower($ur->descripcion); ?>"><?php echo $ur->nombre_red ?></span>
						<br />
					<?php
					}
				}
				?>
			</div>
			
			<?php
			
			}
			
			?>
			
			<div class="storeAddressWrap storeInfoWrap">
				<?php 
				if(!empty($usuarioTienda->direccion1)){
				
				?>
					
					<h4 class="storeAddressTitle">Dirección</h4>
					<p class="storeAddress"><?php echo $usuarioTienda->direccion1." ".$usuarioTienda->direccion2 ?></p>
				
				<?php 
				}
				
				if(!empty($usuarioTienda->telefono1)){
				
				?>
				
					<h4 class="storePhonesTitle">Teléfonos</h4>
					<span class="storePhone princPhone"><?php echo $usuarioTienda->telefono1 ?></span>
					<span class="storePhone secondPhone"><?php echo $usuarioTienda->telefono2 ?></span>
			
				<?php 
				}
				?>
			</div>
		</div>
		
		<div class="resultsFilterTopHeader group">
			<div class="showXResults">
				<span class="filterLbl">Mostrar: </span>
				<ul class="xResultsList group">
					<li class="itemsQty <?php echo $page_size == 20 ? "selected" : ""; ?>">20</li>
					<li class="itemsQty <?php echo $page_size == 40 ? "selected" : ""; ?>">40</li>
					<li class="itemsQty <?php echo $page_size == 60 ? "selected" : ""; ?>">60</li>
				</ul>
			</div>
			<script>
				$('.itemsQty').click(function()
				{
					var itemQ = $(this).html();
					$.ajax({
						type : "POST",
						url : "/phpfn/cantidaditems.php",
						data : {itemsQty : itemQ}
					}).done(function()
					{
						location.reload();
					});
				})
			</script>
<!--
			<div class="togglesWrapper group">
				<span class="displayModeToggle one"><i class="icon-list"></i></span>
				<span class="displayModeToggle two active"><i class="icon-th-large"></i></span>
			</div>
-->
			
			
			<div class="orderByWrapper">
				<span class="filterLbl">Ordenar:</span>
				<div class="styledSelect sortBySel">
					<select class="normalSelect orderBySelection">
						<option value="1" <?php echo $session["orderby"]==1 ? "selected" : ""; ?>>Precio [Mayor a menor]</option>
						<option value="2" <?php echo $session["orderby"]==2 ? "selected" : ""; ?>>Precio [Menor a mayor]</option>
						<option value="3" <?php echo $session["orderby"]==3 ? "selected" : ""; ?>>Fecha [Más reciénte]</option>
						<option value="4" <?php echo $session["orderby"]==4 ? "selected" : ""; ?>>Fecha [Más antiguo]</option>
					</select>
				</div>
			</div>
			<script>
			$('.orderBySelection').change(function(){
				var ord = $(this).val();
				$.ajax({
					type: "POST",
					url : "/phpfn/setorderby",
					data: {orderby : ord}
				}).done(function(){
					location.reload();
				})
			})
		</script>
			
			<form id="searchInStore">
				<button class="searchButton"><i class="fa fa-search"></i></button>
				<input type="text" class="txtField storeSearch search" placeholder="buscar en ésta tienda" value="<?php echo $_GET["buscar"] ?>"/>
			</form>
			<script>
			$('#searchInStore').submit(function( event )
				{
					var sear = $(this).children('.search').val();
					
					
					var url = '/<?php echo $tienda_url ?>-<?php echo $_GET["id"] ?>/';
					if(sear)
					{
						url = url + '' + sear + '/';
					}
					$(this).attr("action", url);
				}
			);
		</script>
			
		</div>
		<div class="storeDisplayContent">
			<?php 
			if(count($productosTienda) == 0)
			{
				?>
				<p class="noContentMsg"><?php echo $usuarioTienda->nombretienda ?> no tiene artículos publicados en éste momento, regresa pronto!</p>
				<?php
			}
			foreach($productosTienda as $pT)
			{
				$link = $sM->remove_accents($pT->nombre);
				$link = str_replace("-", " ", $link);
				$link = preg_replace('/\s\s+/', ' ', $link);
				$link = str_replace(" ", "-", $link);
				$link = strtolower($link);
				$link = preg_replace('/[^A-Za-z0-9\-]/', '', $link);

				
				?>
				<div class="itemWrap">
					
					<?php 
					if($pT->enoferta == 1)
					{
					?>
						<span class="storeDiscTag">-<?php echo number_format(100-(($pT->preciooferta / $pT->precio) * 100), 2) ?>% </span>
					<?php	
					}
					?>
					<a href="/articulo/<?php echo $link."-".$pT->idproducto; ?>" class="itemImg">
						<?php 
						if(empty($pT->imagen))
						{
						?>
							<img src="/images/NoImage.png" alt="No image">
						<?php 
						}
						else
						{
						?>
							<img src="/images/productos/thumb400/<?php echo $pT->imagen ?>" alt="<?php echo $pT->nombre; ?>" title="<?php echo $pT->nombre; ?>">
						<?php 
						}
						
						?>
					</a>
					<div class="itemDescrip">
						<a href="/articulo/<?php echo $link."-".$pT->idproducto; ?>" class="itemListName"><?php echo $pT->nombre; ?></a>
						<br />
						<span class="itemListPrice <?php echo $pT->enoferta == 1 ? "enoferta" : ""; ?>"><?php echo $pT->moneda ?>$ <?php echo $pT->enoferta == 1 ? number_format($pT->preciooferta,2,'.',',') : number_format($pT->precio,2,'.',',') ?></span>									
					</div>
				</div>
				<?php
			}
			?>	
										
			
		
			
		</div>
	
		<div class="paginationWrap">
			<?php
			$query = $con->prepare('select * from categoria1
									where idcategoria1 = :idcat1');
				$query->bindValue(':idcat1',$_GET["categoria1"], PDO::PARAM_INT);
				$query->execute();
				$categoria1 = $query->fetch(PDO::FETCH_OBJ);
				$cat1 = $sM->remove_accents($categoria1->descripcion);
				$cat1 = str_replace("-", " ", $cat1);
				$cat1 = preg_replace('/\s\s+/', ' ', $cat1);
				$cat1 = str_replace(" ", "-", $cat1);
				$cat1 = strtolower($cat1);
				$cat1 = preg_replace('/[^A-Za-z0-9\-]/', '', $cat1);
			if($total_pages > 1)
				{
					if($page == 1){
							
					?>
					
						<a class="pageControl prevPage inactive"><i class="fa fa-angle-left transOn"></i></a>
					
					<?php
					}
					
					else{
						?>
						
						<a class="pageControl prevPage" href="/<?php echo $tienda_url ?>-<?php echo $_GET["id"] ?>/
									<?php echo !empty($_GET["categoria1"]) ? "1/" . $cat1 . "-" . $_GET["categoria1"] . "/": "" ?>
									<?php echo !empty($_GET["categoria2"]) ? "2/" . $_GET["categoria2"] . "/": "" ?>
									<?php echo !empty($_GET["buscar"]) ? "" . $_GET["buscar"] . "/": "" ?>
									<?php echo  "p/" . ($page-1) ?>">
							
							<i class="fa fa-angle-left transOn"></i>
						</a>
						<?
					}
						
					for($i = 1; $i <= $total_pages; $i++)
					{
						if($page == $i)
						{
							?>
							<span class="curentPage pageNumber"><?php echo $page; ?></span>
							<?
						} 
						else
							{
								?>
								<a class="toPage pageNumber" href="/<?php echo $tienda_url ?>-<?php echo $_GET["id"] ?>/
									<?php echo !empty($_GET["categoria1"]) ? "1/" . $cat1 . "-" . $_GET["categoria1"] . "/": "" ?>
									<?php echo !empty($_GET["categoria2"]) ? "2/" . $_GET["categoria2"] . "/": "" ?>
									<?php echo !empty($_GET["buscar"]) ? "" . $_GET["buscar"] . "/": "" ?>
									<?php echo "p/" . $i ?>
									">
									<?php  echo $i; ?>
								</a>
								<?php
							}
					}
					
					if($page == $total_pages){
							
					?>
					
						<a class="pageControl nextPage inactive"><i class="fa fa-angle-right transOn"></i></a>
					
					<?php
					}
					
					else{
						?>
						
						<a class="pageControl nextPage" href="/<?php echo $tienda_url ?>-<?php echo $_GET["id"] ?>/
									<?php echo !empty($_GET["categoria1"]) ? "1/" . $cat1 . "-" . $_GET["categoria1"] . "/": "" ?>
									<?php echo !empty($_GET["categoria2"]) ? "2/" . $_GET["categoria2"] . "/" : "" ?>
									<?php echo !empty($_GET["buscar"]) ? "" . $_GET["buscar"] . "/" : "" ?>
									<?php echo "p/" . ($page+1) ?>/">
									
							
							<i class="fa fa-angle-right transOn"></i>
						</a>
						<?
					}
					
				}
			?>
	
		</div>
	
		<?php 
			require_once('templates/featuredItems.php');
		?>
		
	</div> <!-- close resultsDisplayArea -->
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
				<a class="top ads" href="<?php echo $anuncio[0]->link ?>"><img src="/images/publicidad/<?php echo $anuncio[0]->image ?>"></a>
				<?php 
			}
			?>
		
		
			<?php 
			if(!empty($anuncio[1]->image))
			{
				?>
				<a class="mid ads" href="<?php echo $anuncio[1]->link ?>"><img src="/images/publicidad/<?php echo $anuncio[1]->image ?>"></a>
				<?php 
			}
			?>
		
		
			<?php 
			if(!empty($anuncio[2]->image))
			{
				?>
				<a class="bottom ads" href="<?php echo $anuncio[2]->link ?>"><img src="/images/publicidad/<?php echo $anuncio[2]->image ?>"></a>
				<?php
			}
			?>
		
</div>

</div>
		
<script> //expands store info wrapper

$('.showMoreClicky').on('click', function(){
	
	if(!$(this).hasClass('active')){
		$(this).parent().addClass('expanded');
		$(this).addClass('active');
		$(this).text('Menos información');
	}
	
	else{
		$(this).parent().removeClass('expanded');
		$(this).removeClass('active');
		$(this).text('Más información');
	}
	
	
});
	
</script>		
			
<script> //sets min width on body and header depending on browser windows current width
	
	if(!$.support.leadingWhitespace){
		var minWidth = $('#body').width();
		
		if($('body').width() >= 1225){
			$('#body, .headerCenter').addClass('store').css({
				'min-width': 1285
			});
		
		}
		
		else{
			$('#body, .headerCenter').addClass('store').css({
				'min-width': minWidth
			});	
		
		}
	}
</script>
			
<script> //show/hides category list
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