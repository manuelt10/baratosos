<?php 
session_start();
$session = $_SESSION;
session_write_close();
if($session["autenticado"] and !empty($_POST["nombreTienda"]))
{
	require_once('../../phpfn/cndb.php');
	$con = conection();
	$query = $con->prepare('UPDATE `usuario` 
							SET `nombretienda` = ?, 
							`descripcion` = ? , 
							`idprovincia` = ?, 
							`idsector` = ?, 
							`direccion1` = ?, 
							`direccion2` = ?, 
							`telefono1` = ?, 
							`telefono2` = ?,
							`moneda` = ?
							WHERE `idusuario` = ?');
    $query->bindValue(1,$_POST["nombreTienda"]);
	$query->bindValue(2,$_POST["descripcionTienda"]);
	$query->bindValue(3,$_POST["idprovincia"], PDO::PARAM_INT);
	$query->bindValue(4,$_POST["idsector"], PDO::PARAM_INT);
	$query->bindValue(5,$_POST["direccion1"]);
	$query->bindValue(6,$_POST["direccion2"]);
	$query->bindValue(7,$_POST["telefono1"]);
	$query->bindValue(8,$_POST["telefono2"]);
	$query->bindValue(9,$_POST["moneda"]);
	$query->bindValue(10,$_POST["idusuario"], PDO::PARAM_INT);
    $query->execute();
}
?>