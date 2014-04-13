<?php 
require_once('phpfn/mysqlManager.php');
$db = new mysqlManager();
$cupones = $db->selectRecord('cupon', NULL, Array('idusuario' => $session["usuario"], 'idcupon' => $_GET["id"]));
$usados = $db->selectRecord('v_cupon_usr', NULL, Array('idcupon' => $_GET["id"], 'usado' => 1));
$nousados = $db->selectRecord('v_cupon_usr', NULL, Array('idcupon' => $_GET["id"], 'usado' => 0));
?>
<div class="topBar">
	<h2 class="settHead">Administrar cupones reservados</h2>
</div>
<div class="settWrap sentAdvWrapp">
	<a class="go-coupon-btn setting-extra-btn" href="#">Ver Cupón</a>
	<div class="listActionsWrap listSearchWrap couponSearch">
		<form method="post">
			<input class="listSrchField txtField" type="text" name="busqueda" placeholder="codigo, nombre, correo">
			<button class="formActionBtn listSearchBtn" type="submit">Buscar</button>
		</form> 
	</div>
	
	<span>Cupones canjeados: <b><?php echo $usados->rowcount; ?></b></span>
	<br />
	<span>Cupones pendientes: <b><?php echo $nousados->rowcount; ?></b></span>
	
	<div class="advListBlock reservedListWrap">
		<div class="advListHead">
			<span class="advListHeadItem advListItm couponBuyName">Nombre</span>
			<span class="advListHeadItem advListItm couponBuyDate">Fecha</span>
			<span class="advListHeadItem advListItm couponBuyID">ID</span>
			<span class="advListHeadItem advListItm couponStatus">Estado</span>
		</div>
		<?php 
		if(empty($_POST["busqueda"]))
		{
			$busc = '';
		}
		else
		{
			$busc = $_POST["busqueda"];
		}	
		
		$cupUsrs = $db->selectRecord('v_cupon_usr',NULL,Array('busqueda' => "%$busc%", 'idcupon' => $_GET["id"]));
		$oddCnt = 0;
		foreach($cupUsrs->data as $cU)
		{
			?>
			<div class="advDet <? if($oddCnt%2==1){ echo "odd";} $oddCnt++  ?>">
				<!-- <span><?php echo $cU->idusuario ?></span> -->
				<span class="advListItm couponBuyName"><span class="helpText">Titulo: </span><?php echo $cU->nombre ?></span>
				<!-- <span><?php echo $cU->correo ?></span> -->
				<span class="advListItm couponBuyDate"><span class="helpText">Comprado el: </span><?php echo $cU->fecha_creacion ?></span>
				<span class="advListItm couponBuyID"><span class="helpText">Código: </span><?php echo $cU->codigo ?></span>
				<span class="advListItm couponStatus">
					<span class="helpText">Status: </span>
					<input type="hidden" class="idcompra" value="<?php echo $cU->idcupon_compras ?>">
					<input type="hidden" class="esUsado" value="<?php echo $cU->usado ?>">
					<button type="button" class="isUsedButton <?php echo $cU->usado == 1 ? "usado" : ""; ?>"><?php echo $cU->usado == 1 ? "Usado" : "No usado"; ?></button>
				</span>
			</div>
			<?php
		}
		?>
	</div>
</div> 
<script>
	$('.isUsedButton').click(function()
		{
			var usado = $(this).siblings('.esUsado').val();
			var idCompra = $(this).siblings('.idcompra').val();
			if(usado == 1)
			{
				$(this).siblings('.esUsado').val(0);
				$(this).html('No usado');
			}
			else
			{
				$(this).siblings('.esUsado').val(1);
				$(this).html('Usado');
			}
			$.ajax({
				type : 'post',
				url : '/p/cup_func/cupon_usado.php',
				data : {us : usado, id : idCompra}
			})
		}
	)
</script>