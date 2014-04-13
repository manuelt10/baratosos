<div class="topBar">
	<h2 class="settHead">Wishlist y tiendas favoritas</h2>
</div>
<div class="settWrap editFavsWrapp">

	<div class="tabNavWrap transTw">
		<span class="tabItm wishTab password active" data-tab="wishSec">Wishlist</span>
		<span class="tabItm favTab" data-tab="favSec">Tiendas favoritas</span>
	</div>

<div class="favoritesWrap optionForm tabWrap transTw wishSec">
	<?php
	require_once('phpfn/stringmanager.php'); 
	$sM = new stringManager();
	$query = $con->prepare('SELECT p.*, i.imagen, u.nombre as nombreusuario, u.nombretienda FROM `producto` p
		LEFT JOIN 
		(select idproducto, max(`imagen`) as imagen from imagenproducto group by idproducto order by principal desc, fecha_creacion asc) i
		ON i.idproducto = p.idproducto
		JOIN usuario u
		on u.idusuario = p.idusuario
	where p.idproducto in 
	(select idproducto from productos_fav where idusuario = :id)');
	$query->bindValue(':id',$session["usuario"], PDO::PARAM_INT);
	$query->execute();
	$producto = $query->fetchAll(PDO::FETCH_OBJ);
	
	$total_prod = count($producto);
	
	if($total_prod > 0){
	
		foreach($producto as $pro)
		{
			$link = $sM->remove_accents($pro->nombre);
			$link = str_replace("-", " ", $link);
			$link = preg_replace('/\s\s+/', ' ', $link);
			$link = str_replace(" ", "-", $link);
			$link = strtolower($link);
			$link = preg_replace('/[^A-Za-z0-9\-]/', '', $link);
			
			$link2 = $sM->remove_accents($pro->nombretienda);
			$link2 = str_replace("-", " ", $link2);
			$link2 = preg_replace('/\s\s+/', ' ', $link2);
			$link2 = str_replace(" ", "-", $link2);
			$link2 = strtolower($link2);
			$link2 = preg_replace('/[^A-Za-z0-9\-]/', '', $link2);
			
			
			list($width, $height) = getimagesize("images/productos/thumb150/".$pro->imagen."");
			$baseDimm = 65;
										
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
			<div class="faveItemWrap">
				<a class="itmImageMask itmListMask" href="/articulo/<?php echo $link."-".$pro->idproducto; ?>">
					<img class="itmListImg itmImage" <?php if($height > $width){  ?> width="<?php echo $baseDimm; ?>" height="<?php echo $modVal; ?>" style="top: -<?php echo $pos; ?>px;"  <?php }  else{ ?> height="<?php echo $baseDimm; ?>" width="<?php echo $modVal; ?>" style="left: -<?php echo $pos; ?>px;" <?php } ?> src="/images/productos/thumb150/<?php echo $pro->imagen ?>">
				</a>
				<div class="faveItmDetails">
					<a href="/articulo/<?php echo $link."-".$pro->idproducto; ?>"><?php echo $pro->nombre ?></a>
					<br />
					<a class="favStoreName" href="/<?php echo $link2. "-" . $pro->idusuario ?>"><?php echo $pro->nombretienda ?></a>
				</div>
				<input type="hidden" value="<?php echo $pro->idproducto ?>" class="producto_fav">
				<button class="removeFav formActionBtn">Remover</button>
			</div>
			<?php
		}
		?>
		<script>
			$('.removeFav').click(function(){
				var fpro = $(this).siblings('.producto_fav').val();
				$(this).parent().remove();
				$.ajax({
					type : "POST",
					url  : "/phpfn/favorite_products.php",
					data : {idusuario : <?php echo $session["usuario"] ?>, idproducto : fpro}
				})
			})
		</script>
		
	<?php
	
	}
	
	else{
	
	?>
	
	<p class="noContentMsg">Aún no has agregado nada a tu wishlist, <a href="http://tumall.do">agrega artículos</a>!</p>
	
	<?php
	
	}
	
	?>	
		

	<div class="ctrlBtnWrap">
		<span class="tabNavBack backBtn formActionBtn back">Atrás</span>
	</div>
	
</div>


<div class="favoritesWrap optionForm tabWrap transTw favSec hidden">
	<?php
	require_once('phpfn/stringmanager.php'); 
	$sM = new stringManager();
	$query = $con->prepare('SELECT * FROM `usuario` where idusuario in 
	(select idusuario_favorito from usuarios_fav where idusuario = :id)');
	$query->bindValue(':id',$session["usuario"], PDO::PARAM_INT);
	$query->execute();
	$usuarios = $query->fetchAll(PDO::FETCH_OBJ);
	
	$total_users = count($usuarios);
	
	if($total_users > 0){
	
		foreach($usuarios as $usr)
		{
			$link = $sM->remove_accents($usr->nombretienda);
			$link = str_replace("-", " ", $link);
			$link = preg_replace('/\s\s+/', ' ', $link);
			$link = str_replace(" ", "-", $link);
			$link = strtolower($link);
			$link = preg_replace('/[^A-Za-z0-9\-]/', '', $link);
			
			list($width, $height) = getimagesize("images/productos/profile/cr/".$usr->imagen."");
			$baseDimm = 65;
										
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
			<div class="faveItemWrap">
				<a class="itmImageMask itmListMask" href="/<?php echo $link . "-" . $usr->idusuario ?>"> 
					
					<?php 
			    	if(!empty($usr->imagen))
					{
			    	?>
					
					<img class="itmListImg itmImage" <?php if($height > $width){  ?> width="<?php echo $baseDimm; ?>" height="<?php echo $modVal; ?>" style="top: -<?php echo $pos; ?>px;"  <?php }  else{ ?> height="<?php echo $baseDimm; ?>" width="<?php echo $modVal; ?>" style="left: -<?php echo $pos; ?>px;" <?php } ?> src="/images/profile/cr/<?php echo $usr->imagen ?>">
					
					<?php
					
					}
					
					else{
					
					?>
					
					<img src="/images/resources/storePNG-100.png" alt="No user picture" width="65" height="65" />
					
					<?php
					
					}
					
					?>
					
				</a>
				<div class="faveItmDetails">
					<a  class="favStoreName" href="/<?php echo $link . "-" . $usr->idusuario ?>">
						<?php echo $usr->nombretienda ?>
					</a>
				</div>
				<input class="favorited_user" type="hidden" value="<?php echo $usr->idusuario ?>">
				<button class="removeFav formActionBtn" type="button">Remover</button>
			</div>
			<?php
		}
		?>
		
		<script>
			$('.removeFav').click(function(){
				var fusr = $(this).siblings('.favorited_user').val();
				$(this).parent().remove();
				$.ajax({
					type : "POST",
					url  : "/phpfn/favorite_store.php",
					data : {idusuario : <?php echo $session["usuario"] ?>, idusuario_favorited : fusr}
				})
			})
		</script>
	
	<?php
	
	}
	
	else{
	
	?>

	<p class="noContentMsg">Aún no has agregado ninguna tienda a tus favoritos, <a href="http://tumall.do/tiendas">agrega algunas</a>!</p>

		
	<?php
	}
	
	?>	
		
		<div class="ctrlBtnWrap">
			<span class="tabNavBack backBtn formActionBtn back">Atrás</span>
		</div>	
	
</div>

</div>

<script>
	//shows corresponding form on click/tap
	$('.tabItm').click(function(){
		
		var tab = $(this).data('tab'),
			formHeight = $('.'+tab).height();
			
		$('.settWrap').css('min-height', formHeight+75);
		
		$('.tabItm').removeClass('active');
		$(this).addClass('active');
		
		
		$('.tabWrap').addClass('hidden').removeClass('shown');
		$('.'+tab).removeClass('hidden').addClass('shown');

		
		//mobile specific events
		//separated from prev func to avoid confusion
		$(this).parent().addClass('hidden');
		$('.tabWrap').removeClass('shown');
		$('.'+tab).addClass('shown');
	});
	
	$('.backBtn').click(function(){
		$('.tabWrap').removeClass('shown');
		$('.tabNavWrap').removeClass('hidden');
	});

	
</script>