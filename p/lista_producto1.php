<?php
if(!empty($_GET["id"]))
{
	$session["usuario"] = $_GET["id"]; 
}
require_once('phpfn/stringmanager.php');
$sM = new stringManager();
$query = $con->prepare('SELECT * FROM `producto` where idusuario = :id and `borrado` = 0
						and (nombre like :search or palabras_claves like :search)
');
$query->bindValue(':id',$session["usuario"], PDO::PARAM_INT);
$query->bindValue(':search','%' . $session["search"] . '%');
$query->execute();
$producto = $query->fetchAll(PDO::FETCH_OBJ);
$total = count($producto);
$page_size = 20; 
$page = $_GET["page"];
		if(!$page)
		{
			$start = 0;
			$page  = 1;
		}
		else {
			$start = ($page - 1) * $page_size; 
		}

	$total_pages = ceil($total / $page_size);

	$prevPage = $page-1;
	$nextPage = $page+1;
	

$query = $con->prepare('SELECT * FROM `producto` where idusuario = :id and `borrado` = 0 
						and (nombre like :search or palabras_claves like :search)
LIMIT ' . $start .','. $page_size);
$query->bindValue(':id',$session["usuario"], PDO::PARAM_INT);
$query->bindValue(':search','%' . $session["search"] . '%');
$query->execute(); 
$producto = $query->fetchAll(PDO::FETCH_OBJ);
?>

<div class="topBar">
	<?php 
	if(!empty($_GET["id"]))
	{
		?>
		<h2 class="settHead">Lista de los artículos de <?php echo $usr2->nombre; ?></h2>
		<?php
	}
	else
	{
	?>
		<h2 class="settHead">Lista de tus artículos</h2>
	<?php		
	}
	?>
</div> 

<?
if(!empty($_GET["id"]))
	{
		$query = $con->prepare('SELECT * FROM `usuario` where idusuario = ? ');
		$query->bindValue(1,$_GET["id"], PDO::PARAM_INT);
		$query->execute();
		$usr2 = $query->fetch(PDO::FETCH_OBJ);
	
		$link = $sM->remove_accents($usr2->nombretienda);
		$link = str_replace("-", " ", $link);
		$link = preg_replace('/\s\s+/', ' ', $link);
		$link = str_replace(" ", "-", $link);
		$link = strtolower($link);
		$link = preg_replace('/[^A-Za-z0-9\-]/', '', $link);
	
	?>
		<a class="go-store-btn setting-extra-btn" href="/<?php echo $link . "-" . $usr2->idusuario ?>/">Ir a tienda</a>
	<?php 
	}
else
	{
	$link = $sM->remove_accents($usuario->nombretienda);
		$link = str_replace("-", " ", $link);
		$link = preg_replace('/\s\s+/', ' ', $link);
		$link = str_replace(" ", "-", $link);
		$link = strtolower($link);
		$link = preg_replace('/[^A-Za-z0-9\-]/', '', $link);
	
	?>
		<a class="go-store-btn setting-extra-btn" href="/<?php echo $link . "-" . $usuario->idusuario ?>/">Ir a tienda</a>
					
	<?php 
	}
	?>							
   
<div class="settWrap itmListWrap">
	<div class="listActionsWrap listSearchWrap">
		<input type="text" class="listSrchField txtField" placeholder="Buscar en tu tienda" value="<?php echo $session["search"] ?>">
			<span type="button" class="formActionBtn listSearchBtn">Buscar</span>
		<?php 
		if(!empty($session["search"]))
		{
			?>
			<button type="button" class="removeSrchBtn">Borrar</button>
			<?php
		}
		?>
		<?php 
		if(!empty($_GET["id"]))
		{
			$i = $_GET["id"];
			$url = "href='http://tumall.do/administracion/usr/$i";
		}
		else
			{
				$url= "href='http://tumall.do/productos/lista/";
			}
		?>
		<div class="settingsPagination">
			<?php
			if($total_pages > 1)
				{
					echo $page == 1 ? 1 : (($page-1)*$page_size)+1 ?> al <?php echo $page == $total_pages ? $total : ($page*$page_size) ?> de <span><?php echo $total; ?></span>
							
						<a <?php echo $page > 1 ? "$url/page/$prevPage'" : "" ?> class="pageCtrls prevPage <?php echo $page > 1 ? "active" : "inactive" ?>"><i class="fa fa-angle-left transOn"></i></a>
		
		
						<a <?php echo $page < $total_pages ? "$url/page/$nextPage'" : "" ?> class="pageCtrls nextPage <?php echo $page < $total_pages ? "active" : "inactive" ?>"><i class="fa fa-angle-right transOn"></i></a>
							
				<?		
					
				}
			?>
		</div>
		
	</div>
	
<?php
if(count($producto) == 0)
{
	?>
	<p class="noContentMsg">Aún no has publicado artículos, <a href="http://tumall.do/productos/nuevo">agrega algunos</a>!</p>
	<?php
}
foreach($producto as $pd)
{
    $query = $con->prepare('SELECT * FROM `imagenproducto` where `idproducto` = ?  LIMIT 0,1');
    $query->bindValue(1,$pd->idproducto, PDO::PARAM_INT);
    $query->execute();
    $imagenProducto = $query->fetch(PDO::FETCH_OBJ);
    
    list($width, $height) = getimagesize("images/productos/thumb200/".$imagenProducto->imagen."");
	$baseDimm = 85;
								
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
	<div class="itemDetWrapp fluidTransTw <? if($oddCnt%2==1){ echo "odd";} echo $pd->activo == 1 ? "" : " inactive"; $oddCnt++  ?>">
	    
    	<?php 
    	if(!empty($imagenProducto->imagen))
		{
    	?>
    	<span class="itmImageMask itmListMask">
        	<img class="itmListImg itmImage" <?php if($height > $width){  ?> width="<?php echo $baseDimm; ?>" height="<?php echo $modVal; ?>" style="top: -<?php echo $pos; ?>px;"  <?php }  else{ ?> height="<?php echo $baseDimm; ?>" width="<?php echo $modVal; ?>" style="left: -<?php echo $pos; ?>px;" <?php } ?> src="/images/productos/thumb200/<?php echo $imagenProducto->imagen; ?>" >
        </span>
        <?php 
		}
		
		else{
			?>
			<span class="itmImageMask transOn itmListMask noImg"><i class="fa fa-camera"></i></span>
			<?php
		}
        ?>
	    
	    <div class="itmListDescrWrap">
	        <span class="itmListName transOn itmListDescr"><?php echo $pd->nombre ?></span>
	        <span class="itmListPrice transOn itmListDescr"><?php echo $pd->moneda ?>$ <?php echo number_format($pd->precio,2,'.',',') ?></span>
	        <span class="itmListID itmListDescr">ID: <?php echo $pd->idproducto ?></span>
	    </div>
		<div class="itmListBtnWrapp">
			<span class="openBtn"><i class="fa fa-chevron-right fluidTransTw"></i></span>
			<?php 
			if(!empty($_GET["id"]))
			{
				?>
				<a class="itmListBtn editBtn" href="/administracion/prod/<?php echo $pd->idproducto ?><?php echo empty($_GET["id"]) ? "" : "/" . $_GET["id"]; ?>">Editar</a>
				<?php
			}
			else
			{
				?>
				<a class="itmListBtn editBtn" href="/productos/lista/<?php echo $pd->idproducto ?><?php echo empty($_GET["id"]) ? "" : "/" . $_GET["id"]; ?>">Editar</a>
				<?php
			}
			?>
	    	
	        <button type="button" class="itmListBtn itmStatBtn <?php echo $pd->activo == 1 ? "active" : "inactive"; ?>"><?php echo $pd->activo == 1 ? "Inactivar" : "Activar"; ?></button>
	        <input type="hidden" class="idproducto" value="<?php echo $pd->idproducto ?>">
	        <button type="button" class="itmListBtn itmRemoveBtn">Eliminar</button>
	    </div>
	    
	</div>
	<?php
	}
?>
</div>

<script> //clears search terms in user list
	$('.removeSrchBtn').click(function()
	{
		var srh = $(this).siblings('.listSrchField').val();
		$.ajax({
			type : "POST",
			url : "/p/list_func/reset_srh.php"
		}).done(function(){
			location.reload();
		})
	})

	
	//executes user list search when clicked

	$('.listSearchBtn').click(function()
	{
		var srh = $(this).siblings('.listSrchField').val();
		$.ajax({
			type : "POST",
			url : "/p/list_func/set_search.php",
			data : {search : srh}
		}).done(function(){
			location.reload();
		})
	})
</script>

<script> //show/hides item's edit buttons when on mobile
	$('.openBtn').click(function(event){
		
		if($(this).hasClass('active')){
			$(this).removeClass('active');
			$(this).parent().parent('.itemDetWrapp').removeClass('active');
		}
		
		else{
			$('.openBtn').removeClass('active');
			$('.itemDetWrapp').removeClass('active');
			$(this).parent().parent('.itemDetWrapp').addClass('active');
			$(this).addClass('active');
		}
		
	});
	
	$('.itmStatBtn').on('click', function(){
		$('.openBtn').removeClass('active');
		$(this).parent().parent('.itemDetWrapp').removeClass('active');
	});
	
</script>

<script>
	$('.itmRemoveBtn').click(function()
	{
		if(confirm('Desear elminar este producto?'))
		{
			var producto = $(this).siblings('.idproducto').val();
			$(this).parents('.itemDetWrapp').remove();
			
			$.ajax({
				type: "POST",
				url: "/p/list_func/rem_product.php",
				data: {idproducto : producto}
			});
		}
	});
</script>
<script>
	$('.itmStatBtn').click(function()
	{
		var producto = $(this).siblings('.idproducto').val();
		if($(this).html() == 'Activar')
		{
			$(this).html('Inactivar');
			$(this).removeClass('inactive');
			$(this).parent().parent().removeClass('inactive');
			
		}else
		{
			$(this).html('Activar');
			$(this).addClass('inactive');
			$(this).parent().parent().addClass('inactive');
		}
		$.ajax({
			type: "POST",
			url: "/p/list_func/change_status.php",
			data: {idproducto : producto}
		});
	});
</script>