<?php 
require_once('phpfn/mysqlManager.php');
$db = new mysqlManager();
$cupon_usr = $db->selectRecord('cupon_compras',NULL,Array('idusuario' => $session["usuario"]));
?>

<div class="topBar">
	<h2 class="settHead">Administrar cupones reservados</h2>
</div>
<div class="settWrap sentAdvWrapp">
	<a class="go-coupon-btn setting-extra-btn" href="#">Lista de cupones</a>
	<div class="listActionsWrap listSearchWrap couponSearch">
		<form method="post">
			<input class="listSrchField txtField" type="text" name="busqueda" placeholder="codigo, nombre, correo">
			<button class="formActionBtn listSearchBtn" type="submit">Buscar</button>
		</form> 
	</div>
	<div class="advListBlock reservedListWrap">
		<div class="advListHead">
			<span class="advListHeadItem advListItm couponBuyName">Nombre</span>
			<span class="advListHeadItem advListItm couponBuyID">Código</span>
			<span class="advListHeadItem advListItm couponBuyDate">Hasta</span>
			<span class="advListHeadItem advListItm couponStatus">Estado</span>
		</div>
		<?php 
		$oddCnt = 0;
		foreach($cupon_usr->data as $c){
			$cupon_actual_usr = $db->selectRecord('cupon',NULL,Array('idcupon' => $c->idcupon));
			?>
			<div class="advDet <? if($oddCnt%2==1){ echo "odd";} $oddCnt++  ?>">
				<a href="#" class="advListItm couponBuyName"><span class="helpText">Titulo: </span><?php echo $cupon_actual_usr->data[0]->titulo; ?></a>
				<span class="advListItm couponBuyID"><span class="helpText">Código: </span><?php echo $c->codigo ?></span>
				<span class="advListItm couponBuyDate"><span class="helpText">Hasta el: </span><?php echo $c->fecha_creacion ?></span> 
				<span class="advListItm couponStatus"><span class="helpText">Status: </span>[<?php echo $c->usado == 1 ? "Usado" : "No usado" ?>] <a href="/reports/rep_cupon.php?id=<?php echo $c->idcupon_compras  ?>">Imprimir</a></span>
			</div>
			<?php
		}
		?>
		 
	</div>   
</div>