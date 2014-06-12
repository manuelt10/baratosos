<?php 
session_start();
$session = $_SESSION;
session_write_close();
require_once('../../phpfn/cndb.php');
	$con = conection();
$query = $con->prepare('SELECT * FROM `usuario` where idusuario = ?');
$query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
$query->execute();
$usuario = $query->fetch(PDO::FETCH_OBJ);
if(	($session["autenticado"]) and 
	(md5($_POST["clave"]) == $usuario->contrasena) and 
	($_POST["nuevaclave"] == $_POST["nuevaclave2"]))
{
	
	$query = $con->prepare('UPDATE `usuario` 
							SET `contrasena` = ?, fecha_modificacion = now()
							WHERE `idusuario` = ?');
    $query->bindValue(1,md5($_POST["nuevaclave"]));
	$query->bindValue(2,$session["usuario"], PDO::PARAM_INT);
    $query->execute();
	
}
else {
	session_start();
	$_SESSION["error"] = 1;
	session_write_close();
}
header("location: http://tumall.do/administracion/ajustes");
?>