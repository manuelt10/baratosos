<?php 
require_once('phpfn/stringmanager.php');
								$sM = new stringManager();
if(empty($_GET["buscar"]))
{
	$_GET["buscar"] = '';
}
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
else
	{
		$orderby = 'order by p.fecha_creacion desc';
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
		
		if(!empty($_GET["categoria2"]) and empty($_GET["buscar"])) {
			$query = $con->prepare('SELECT c2.*, c1.descripcion as descripcion2 
									FROM `categoria2` c2
								    join categoria1 c1
								    ON c2.idcategoria1 = c1.idcategoria1
								    where c2.idcategoria2 = :categoria2');
			$query->bindValue(':categoria2',$_GET["categoria2"], PDO::PARAM_INT);
							$query->execute();
							$categoria2 = $query->fetch(PDO::FETCH_OBJ);
			$categoria1 = $categoria2->idcategoria1;
		}
		else if(!empty($_GET["categoria1"]) and empty($_GET["buscar"]))
			{
				$query = $con->prepare('SELECT c1.descripcion as descripcion2 
									FROM categoria1 c1
								    where c1.idcategoria1 = :categoria1');
			$query->bindValue(':categoria1',$_GET["categoria1"], PDO::PARAM_INT);
							$query->execute();
							$categoria2 = $query->fetch(PDO::FETCH_OBJ);	
			}
		
		$sql = "SELECT DISTINCT p.*,  i.imagen,  c.`estado`, u.nombretienda, u.idusuario
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
							";
							
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
						
						$sql = $sql . '
							AND (p.nombre like :nombre or p.palabras_claves like :nombre)';
							
				$query = $con->prepare($sql);
				if(!empty($_GET["categoria1"])) {$query->bindValue(':categoria1',$_GET["categoria1"], PDO::PARAM_INT);}
				if(!empty($_GET["categoria2"])) {$query->bindValue(':categoria2',$_GET["categoria2"], PDO::PARAM_INT);}
				$query->bindValue(':nombre','%' . $_GET["buscar"] . '%');
				$query->execute();					
	$productos = $query->fetchAll(PDO::FETCH_OBJ);
	
	$total = count($productos);
	$total_pages = ceil($total / $page_size);
 
	$query = $con->prepare($sql . '
					' . $orderby .'
							
							LIMIT ' . $start .','. $page_size);
				
				if(!empty($_GET["categoria1"]))	{$query->bindValue(':categoria1',$_GET["categoria1"], PDO::PARAM_INT);}
				if(!empty($_GET["categoria2"])) {$query->bindValue(':categoria2',$_GET["categoria2"], PDO::PARAM_INT);}
				$query->bindValue(':nombre','%' . $_GET["buscar"] . '%');
				$query->execute();
	$productos = $query->fetchAll(PDO::FETCH_OBJ);
	
?>


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
	
	<?php if(!empty($categoria2->descripcion2)){ ?>
	<h3 class="selectedCategory"><?php echo $categoria2->descripcion2; ?></h3>
	
	<?php } ?>
	
	<h2 class="main-heading category-heading">Categorías</h2>
		<div class="categoryPane transTw">
			<?php 
			if(!empty($_GET["buscar"]))
			{
				require_once('busqueda/categoria_bus.php');
			}
			else if (!empty($categoria1))
			{
				require_once('busqueda/categoria_nobus.php');
			}
			?>
		</div>
	</div>
			
				
<div class="resultsDisplayArea">	
	<div class="resultsFilterTopHeader group">
		<h2 class="resultsCount"><?php echo $total ?> resultado(s)</h2>
		<div class="showXResults">
			<span class="filterLbl">Mostrar: </span>
			<ul class="xResultsList group">
				<li class="itemsQty <?php echo $page_size == 20 ? "selected" : ""; ?>">20</li>
				<li class="itemsQty <?php echo $page_size == 40 ? "selected" : ""; ?>">40</li>
				<li class="itemsQty last <?php echo $page_size == 60 ? "selected" : ""; ?>">60</li>
			</ul>
		</div>
		<script>
			$('.itemsQty').click(function()
			{
				var itemQ = $(this).html();
				$.ajax({
					type : "POST",
					url : "phpfn/cantidaditems.php",
					data : {itemsQty : itemQ}
				}).done(function()
				{
					location.reload();
				});
			})
		</script>
		 
		<div class="orderByWrapper">
			<span class="filterLbl">Ordenar por:</span>
			<div class="styledSelect sortBySel">
				<select class="normalSelect orderBySelection">
					<option value="1" <?php echo $session["orderby"]==1 ? "selected" : ""; ?>>Precio [Mayor a menor]</option>
					<option value="2" <?php echo $session["orderby"]==2 ? "selected" : ""; ?>>Precio [Menor a mayor]</option>
					<option value="3" <?php echo $session["orderby"]==3 ? "selected" : ""; ?>>Fecha [Mayor a menor]</option>
					<option value="4" <?php echo $session["orderby"]==4 ? "selected" : ""; ?>>Fecha [Menor a mayor]</option>
				</select>
			</div>
		</div>
	</div>
	<script>
		$('.orderBySelection').change(function(){
			var ord = $(this).val();
			$.ajax({
				type: "POST",
				url : "phpfn/setorderby",
				data: {orderby : ord}
			}).done(function(){
				location.reload();
			})
		})
	</script>

	<div class="resultsWrapper">
		
		<?php 
		if($total > 0){		
		$item_cont = 0;
		foreach($productos as $p)
		{
			$item_cont++;
			$link = $sM->remove_accents($p->nombre);
			$link = str_replace("-", " ", $link);
			$link = preg_replace('/\s\s+/', ' ', $link);
			$link = str_replace(" ", "-", $link);
			$link = strtolower($link);
			$link = preg_replace('/[^A-Za-z0-9\-]/', '', $link);
		
			?>
			<div class="resultsItemWrapper group <?php echo $total == $item_cont ? "last" : "" ?>">
				<a href="articulo/<?php echo $link."-".$p->idproducto; ?>" class="pictureWrapper">
						<?php 
						if(empty($p->imagen))
						{
						?>
							<img src="images/NoImage.png" alt="No image" title="No image">
						<?php 
						}
						else
						{
						?>
							<img src="images/productos/thumb200/<?php echo $p->imagen ?>" title="<?php echo $p->nombre; ?>" alt="<?php echo $p->nombre; ?>" >
						<?php 
						}
						?>
				</a>
				<div class="resultsItemDescrip">
					<h3 class="resultsItemName"><a href="articulo/<?php echo $link."-".$p->idproducto; ?>"><?php echo $p->nombre; ?></a></h3>
					<div class="resultsItemPrice">
						<span class="<?php echo $p->enoferta == 1 ? "enoferta" : ""; ?>"><?php echo $p->moneda ?>$ <?php echo number_format($p->enoferta == 1 ? $p->preciooferta : $p->precio,2,'.',',') ?></span>
						<?php 
						if($p->enoferta == 1)
						{
							?>
							<br />
							<span class="origPrice"><?php echo $p->moneda ?>$ <?php echo number_format($p->precio,2,'.',',') ?></span>
							<span class="priceDisc">-<?php 
								echo number_format(100-(($p->preciooferta / $p->precio) * 100), 2)
								?>%</span>
							<?php	
						}
						?> 
					</div>
					
					<?php
					
					$storeLink = $sM->remove_accents($p->nombretienda);
					$storeLink = str_replace("-", " ", $storeLink);
					$storeLink = preg_replace('/\s\s+/', ' ', $storeLink);
					$storeLink = str_replace(" ", "-", $storeLink);
					$storeLink = strtolower($storeLink);
					$storeLink = preg_replace('/[^A-Za-z0-9\-]/', '', $storeLink);
					
					?>
					
					<script>
						console.log("<?php echo $storeLink; ?>")
					</script>
					
					<div class="resultSellerWrap">
						de <a class="resultSellerName" href="<?php echo $storeLink."-".$p->idusuario."/"; ?>"><?php echo $p->nombretienda; ?></a>
						<?php 
						if($p->estado == 'A')
						{
							?>
							<div class="sellerType">
								<img src="images/certVal.png" alt="Tienda certificada" title="Tienda Certificada (CFT)"  />
							</div>
							<?php
						}
						?>	
					</div>
					


					
				</div>
				<div class="resultItemBtnWrapper">
					<span class="button actionsBtn">Acciones <i class="fa fa-share-square-o"></i></span>
				</div>

			</div>
			<?php
		}
		}
		else{
			?>
			
			<p class="noContentMsg">No se encontraron artículos con "<?php echo $_GET["buscar"]; ?>". </p>
			<p class="noContentMsg mega">:(</p>
			<?php
		}
		
		?>
	
	
	
		<div class="paginationWrap">
			<?php
			if(!empty($_GET["categoria1"]))
			{
				$query = $con->prepare('SELECT c1.*
									FROM categoria1 c1
								    where c1.idcategoria1 = :categoria1');
				$query->bindValue(':categoria1',$_GET["categoria1"], PDO::PARAM_INT);
				$query->execute();
				$cat1 = $query->fetch(PDO::FETCH_OBJ);	
				$ct1 = $sM->remove_accents($cat1->descripcion);
				$ct1 = str_replace("-", " ", $ct1);
				$ct1 = preg_replace('/\s\s+/', ' ', $ct1);
				$ct1 = str_replace(" ", "-", $ct1);
				$ct1 = strtolower($ct1);
				$ct1 = preg_replace('/[^A-Za-z0-9\-]/', '', $ct1);
			}
			if(!empty($_GET["categoria2"]))
			{
				
				$query = $con->prepare('SELECT c2.*
									FROM `categoria2` c2
								    where c2.idcategoria2 = :categoria2');
				$query->bindValue(':categoria2',$_GET["categoria2"], PDO::PARAM_INT);
				$query->execute();
				$cat2 = $query->fetch(PDO::FETCH_OBJ);
				$ct2 = $sM->remove_accents($cat2->descripcion);
				$ct2 = str_replace("-", " ", $ct2);
				$ct2 = preg_replace('/\s\s+/', ' ', $ct2);
				$ct2 = str_replace(" ", "-", $ct2);
				$ct2 = strtolower($ct2);
				$ct2 = preg_replace('/[^A-Za-z0-9\-]/', '', $ct2);
			}
			
			$busqueda = $_GET["buscar"];
			if($total_pages > 1)
				{
					
					if($page == 1){
						
					?>
					
						<a class="pageControl prevPage inactive"><i class="fa fa-angle-left transOn"></i></a>
					
					<?php
					}
					
					else{
						?>
						
						<a class="pageControl prevPage" href="lista/
						<?php echo empty($_GET["categoria1"]) ? "" : "categoria/$ct1-$cat1->idcategoria1/"  ?>
						<?php echo empty($_GET["categoria2"]) ? "" : "categoria2/$ct2-$cat2->idcategoria2/" ?>
						<?php echo empty($_GET["buscar"]) ? "" : "buscar/$busqueda/" ?>
						<?php echo ($page-1); ?>">
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
								<a class="toPage pageNumber" href="lista/
								<?php echo empty($_GET["categoria1"]) ? "" : "categoria/$ct1-$cat1->idcategoria1/"  ?>
								<?php echo empty($_GET["categoria2"]) ? "" : "categoria2/$ct2-$cat2->idcategoria2/" ?>
								<?php echo empty($_GET["buscar"]) ? "" : "buscar/$busqueda/" ?>
								<?php echo $i ?>">
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
						
						<a class="pageControl nextPage" href="lista/
						<?php echo empty($_GET["categoria1"]) ? "" : "categoria/$ct1-$cat1->idcategoria1/"  ?>
						<?php echo empty($_GET["categoria2"]) ? "" : "categoria2/$ct2-$cat2->idcategoria2/" ?>
						<?php echo empty($_GET["buscar"]) ? "" : "buscar/$busqueda/" ?>
						<?php echo ($page+1); ?>">
								<i class="fa fa-angle-right transOn"></i>
						</a>
						<?
					}
					
					
					
				}
			?>
		</div>
	
	
	
	</div>
	
	<div class="featItmWrap">
	<h2 class="featuredItemsHead">Artículos destacados</h2>		
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
				<h3><a class="featItmName" href="articulo/<?php echo $link."-".$pO->idproducto; ?>"><?php echo $pO->nombre ?></a></h3>
				<span class="featItmPric <?php echo $pO->enoferta == 1 ? "enoferta" : ""; ?>"><?php echo $pO->moneda ?>$ <?php echo number_format($pO->enoferta == 1 ? $pO->preciooferta : $pO->precio,2,'.',',') ?></span>
			</div>
			<?php
		}
	?>
				
		
	</div>
	
	
</div>

<div class="adsWrap side">
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
				