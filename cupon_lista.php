<?php 
require_once('phpfn/mysqlManager.php');
$db = new mysqlManager();
$cupones = $db->selectRecord('cupon', NULL, Array('idusuario' => $session["usuario"], 'idcupon' => $_GET["id"] ));
?>
<div>
	<a href="#">Ver Cupon</a>
	<form method="post">
		<input type="text" name="busqueda">
		<button type="submit">Buscar</button>
	</form> 
	<div>
		<?php 
		if(empty($_POST["busqueda"]))
		{
			$busc = '';
		}
		else
		{
			$busc = $_POST["busqueda"];
		}	
		
		$cupUsrs = $db->selectRecord('v_cupon_usr',NULL,Array('busqueda' => "%$busc%"));
		foreach($cupUsrs->data as $cU)
		{
			?>
			<div>
				<span><?php echo $cU->idusuario ?></span>
				<span><?php echo $cU->nombre ?></span>
				<span><?php echo $cU->correo ?></span>
				<span><?php echo $cU->fecha_creacion ?></span>
				<span><?php echo $cU->codigo ?></span>
				<span></span>
			</div>
			<?php
		}
		?>
	</div>
</div> 