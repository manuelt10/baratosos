<?php 
if(empty($_POST["titulo"]) or empty($_POST["idusuario"]) or empty($_POST["precionormal"]) or empty($_POST["preciooferta"]) or empty($_POST["dia_publicacion"]) or empty($_POST["duracion_dias"]))
{
	session_start();
	$_SESSION["error"] = 1;
	session_write_close();
	header('Location: ' . $_SERVER["HTTP_REFERER"]);
}
else
{
	require_once('../../phpfn/mysqlManager.php');
	$db = new mysqlManager();
	$where = Array('dia_publicacion' => $_POST["dia_publicacion"]);
	$record = $db->selectRecord('cupon', NULL, $where);
	if ($record->rowCount > 0)
	{
		session_start();
		$_SESSION["error"] = 2;
		session_write_close();
		header('Location: ' . $_SERVER["HTTP_REFERER"]);
	}
	else
	{
		$record = Array(
			'idusuario' => $_POST["idusuario"],
			'idproducto' => $_POST["idproducto"],
			'titulo' => $_POST["titulo"],
			'precio_normal' => $_POST["precionormal"],
			'precio_oferta' => $_POST["preciooferta"],
			'cantidad' => $_POST["cantidad"],
			'cantidad_compra' => $_POST["cantidad_compra"],
			'duracion_dias' => $_POST["duracion_dias"],
			'duracion_horas' => $_POST["duracion_horas"],
			'caracteristicas' => $_POST["caracteristicas"],
			'descripcion' => $_POST["descripcion"],
			'contacto' => $_POST["contacto"],
			'dia_publicacion' => date('Y-m-d H:i:s', strtotime($_POST["dia_publicacion"]))
		);
		$var = $db->insertRecord('cupon',$record);
		
		foreach($_POST["position"] as $p)
		{
			$record = Array(
				'idcupon' => $var->lastid,
				'posicion' => $p
			);
			$db->insertRecord('cupon_posicion',$record);
		}
		
		$db->updateRecord('cupon_galeria',Array('idcupon' => $var->lastid),Array('idcupon' => NULL));
		
		header('Location: http://tumall.doadministracion/cupones');
	}
}

?>