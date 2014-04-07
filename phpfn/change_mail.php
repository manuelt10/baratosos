<?php 
session_start();
$session = $_SESSION;
session_write_close();
require_once('cndb.php');
$con = conection();

$query = $con->prepare('SELECT * FROM `usuario` where md5(correonuevo) = ?');
$query->bindValue(1,$_GET["nm"]);
$query->execute();
$existeCorreo = $query->fetch(PDO::FETCH_OBJ);
if(count($existeCorreo->idusuario) == 0)
{
	$query = $con->prepare('SELECT * FROM `usuario` where md5(idusuario) = ? and md5(correonuevo) = ?');
	$query->bindValue(1,$_GET["id"]);
	$query->bindValue(2,$_GET["nm"]);
	$query->execute();
	$usuario = $query->fetch(PDO::FETCH_OBJ);
	if(count($usuario))
	{
		$query = $con->prepare('UPDATE `usuario`
								SET correo = correonuevo, correonuevo = NULL
								WHERE md5(idusuario) = ? AND md5(correonuevo) = ?');
		$query->bindValue(1,$_GET["id"]);
		$query->bindValue(2,$_GET["nm"]);
		$query->execute();
		session_start();
		unset($_SESSION["autenticado"]);
		unset($_SESSION["usuario"]);
		session_write_close();
	}
}

header('location: ../home')
?>