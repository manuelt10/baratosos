<?php /* <div class="topBar">
	<h2 class="settHead">Wishlist y tiendas favoritas</h2>
</div>
<div class="settWrap editFavsWrapp">

<a class="tabItm" href="cliente/fav">Wishlist</a>
<a class="tabItm active">Tiendas favoritas</a>

<div class="favoritesWrap">
	<?php
	require_once('phpfn/stringmanager.php'); 
	$sM = new stringManager();
	$query = $con->prepare('SELECT * FROM `usuario` where idusuario in 
	(select idusuario_favorito from usuarios_fav where idusuario = :id)');
	$query->bindValue(':id',$session["usuario"], PDO::PARAM_INT);
	$query->execute();
	$usuarios = $query->fetchAll(PDO::FETCH_OBJ);
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
			<a class="itmImageMask itmListMask" href="tienda/<?php echo $link . "-" . $usr->idusuario ?>"> 
				
				<?php 
		    	if(!empty($usr->imagen))
				{
		    	?>
				
				<img class="itmListImg itmImage" <?php if($height > $width){  ?> width="<?php echo $baseDimm; ?>" height="<?php echo $modVal; ?>" style="top: -<?php echo $pos; ?>px;"  <?php }  else{ ?> height="<?php echo $baseDimm; ?>" width="<?php echo $modVal; ?>" style="left: -<?php echo $pos; ?>px;" <?php } ?> src="images/profile/cr/<?php echo $usr->imagen ?>">
				
				<?php
				
				}
				
				else{
				
				?>
				
				<img src="images/resources/storePNG-100.png" alt="No user picture" width="65" height="65" />
				
				<?php
				
				}
				
				?>
				
			</a>
			<div class="faveItmDetails">
				<a  class="favStoreName" href="tienda/<?php echo $link . "-" . $usr->idusuario ?>">
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
				url  : "phpfn/favorite_store.php",
				data : {idusuario : <?php echo $session["usuario"] ?>, idusuario_favorited : fusr}
			})
		})
	</script>
</div>
</div>*/ ?>