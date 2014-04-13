
<?php 
require_once('phpfn/paginate.php'); 
$sql = 'SELECT * FROM `usuario` where (nombre like :nombre or nombretienda like :nombre) and idtipousuario <> 3 order by 1';
if(!empty($session["userType"]))
{
	$sql = 'SELECT * FROM `usuario` where (nombre like :nombre or nombretienda like :nombre) and idtipousuario = :tipo and idtipousuario <> 3 order by 1';
}
$query = $con->prepare($sql);
$query->bindValue(':nombre','%'. $session["searchUsr"] .'%');
if(!empty($session["userType"]))
{
	$query->bindValue(':tipo', $session["userType"], PDO::PARAM_INT);
}
$query->execute();
$usuarioAdm = $query->fetchAll(PDO::FETCH_OBJ);

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
$total = count($usuarioAdm);
$total_pages = ceil($total / $page_size);

$prevPage = $page-1;
$nextPage = $page+1;

$query = $con->prepare($sql . '  LIMIT ' . $start .','. $page_size );
$query->bindValue(':nombre','%'. $session["searchUsr"] .'%');
if(!empty($session["userType"]))
{
	$query->bindValue(':tipo', $session["userType"], PDO::PARAM_INT);
}
$query->execute();
$usuarioAdm = $query->fetchAll(PDO::FETCH_OBJ);
$oddCnt = 0;

?>

<div class="topBar">
	<h2 class="settHead">Administrar usuarios</h2>
</div>

	<a class="view-report-btn setting-extra-btn" href="/administracion/rep/users.php">Reporte de Tiendas</a>

<div class="settWrap userWrapper">
	<div class="listActionsWrap listSearchWrap group">
		<select class="usrTypeSelect">
			<option value="0" <?php echo $session["userType"] == 0 ? "selected" : "" ?>>Todos</option>
			<option value="1" <?php echo $session["userType"] == 1 ? "selected" : "" ?>>Normales</option>
			<option value="2" <?php echo $session["userType"] == 2 ? "selected" : "" ?>>Tiendas</option>
		</select>
		<input type="text" class="listSrchField txtField" placeholder="Buscar usuarios/tiendas" value="<?php echo $session["searchUsr"] ?>">
		<span type="button" class="formActionBtn listSearchBtn">Buscar</span>
		<?php 
		if(!empty($session["searchUsr"]))
		{
			?>
			<button type="button" class="removeSrchBtn">Borrar</button>
			<?php
		}
		?>
		
		
		<div class="settingsPagination">
			<?php
			if($total_pages > 1)
				{
					echo $page == 1 ? 1 : (($page-1)*$page_size)+1 ?> al <?php echo $page == $total_pages ? $total : ($page*$page_size) ?> de <span><?php echo $total; ?></span>
							
						<a <?php echo $page > 1 ? "href='/administracion/usuarios/page/$prevPage/'" : "" ?> class="pageCtrls prevPage <?php echo $page > 1 ? "active" : "inactive" ?>"><i class="fa fa-angle-left transOn"></i></a>
		
		
						<a <?php echo $page < $total_pages ? "href='/administracion/usuarios/page/$nextPage/'" : "" ?> class="pageCtrls nextPage <?php echo $page < $total_pages ? "active" : "inactive" ?>"><i class="fa fa-angle-right transOn"></i></a>
							
				<?		
					
				}
			?>
		</div>	
		
	</div>

<?php

foreach($usuarioAdm as $uA)
{ 

	$query = $con->prepare('SELECT * FROM `tipousuario` where idtipousuario = ?');
	$query->bindValue(1,$uA->idtipousuario, PDO::PARAM_INT);
	$query->execute();
	$tipoUsr = $query->fetch(PDO::FETCH_OBJ);

    list($width, $height) = getimagesize("images/profiles/thumb100/".$uA->imagen."");
	$baseDimm = 85;
								
	if($width > $height){
		$ratio = $height/$baseDimm;
	}
	
	else{
		$ratio = $width/$baseDimm;
	}
	
	$width /= $ratio;
	
	
	$leftPos = ($width-$baseDimm)/2;	

	?>
<div class="itemDetWrapp transTw userDetailWrapper <? if($oddCnt%2==1){ echo "odd";} echo $uA->baneado == 1 ? " inactive" : ""; $oddCnt++  ?>" >
	<?php 
	if(!empty($uA->imagen))
	{
	?>
		<span class="itmImageMask itmListMask usrImageMask">
			<img class="itmListImg itmImage" style="left:-<?php echo $leftPos; ?>px;" width="<?php echo $width; ?>" height="<?php echo $baseDimm; ?>" src="/images/profile/cr/<?php echo $uA->imagen ?>">
		</span>
	<?php 
	}
	
	else{
		?>
		<span class="itmImageMask usrImageMask transOn itmListMask noImg">
		
			<img src="/images/resources/<?php echo $tipoUsr->idtipousuario == 2 ? "storePNG" : "userPNG" ?>-100.png" alt="No user picture" width="85" height="85" />
		
		</span>
		<?php
	}	
	
	?>
		<div class="itmListDescrWrap">
			<span class="itmListName transOn itmListDescr"><?php echo $uA->nombre; ?></span>
			<span class="itmListType transOn itmListDescr">Tipo: <?php echo $tipoUsr->descripcion; ?> </span>
			<span class="itmListMail transOn itmListDescr"><?php echo $uA->correo; ?></span>
			<span class="itmListID transOn itmListDescr">Usuario # <?php echo $uA->idusuario; ?></span>
		</div>
		<div class="itmListBtnWrapp hidden">
			<span class="openBtn"><i class="fa fa-chevron-right fluidTransTw"></i></span>
			<input type="hidden" class="idusuario" value="<?php echo $uA->idusuario; ?>">

			<?php if($tipoUsr->idtipousuario == 2 ){ 
				
			?>
				<a class="itmListBtn viewListBtn" href="/administracion/usr/<?php echo $uA->idusuario; ?>">Ver Artículos</a>
			
			<?php
			 } 
			 ?>
			 
			 <button class="itmListBtn banButton"><?php echo $uA->baneado == 1 ? "Restaurar" : "Suspender"; ?></button>
		</div>
	</div>
	<?
}
?>
</div>

<script> //clears search terms in user list
$('.removeSrchBtn').click(function()
{
	var srh = $(this).siblings('.listSrchField').val();
	$.ajax({
		type : "POST",
		url : "/administracion/usu_func/reset_srh.php"
	}).done(function(){
		location.reload();
	})
})


//executes user list search when clicked

$('.listSearchBtn').click(function()
{
	var srh = $(this).siblings('.listSrchField').val();
	var typ = $(this).siblings('.usrTypeSelect').val();
	$.ajax({
		type : "POST",
		url : "/administracion/usu_func/set_search.php",
		data : {searchUsr : srh, userType : typ}
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
	
	$('.banButton').on('click', function(){
		$('.openBtn').removeClass('active');
		$(this).parent().parent('.itemDetWrapp').removeClass('active');
	});
	
</script>

<script>
	$('.banButton').click(function(){
		if($(this).html() == 'Suspender')
		{
			$(this).html('Restaurar');
			$(this).parent().parent().addClass('inactive');
		}
		else{
			$(this).html('Suspender');
			$(this).parent().parent().removeClass('inactive');
		}
		var idusuario = $(this).siblings('.idusuario').val();
		$.ajax({
			type : "POST",
			url : "/administracion/usu_func/ban_user.php",
			data : {usr : idusuario}
		})
	})
</script>