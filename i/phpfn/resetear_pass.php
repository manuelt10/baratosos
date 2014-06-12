<?php 
if($_POST["pass1"] == $_POST["pass2"])
{
	require_once('cndb.php');
	$con = conection();
	$query = $con->prepare('SELECT * FROM `usuario` where md5(idusuario) = ? and md5(correo) = ? and md5(fecha_creacion) = ?');
	$query->bindValue(1,$_GET["id"]);
	$query->bindValue(2,$_GET["e"]);
	$query->bindValue(3,$_GET["cd"]);
	$query->execute();
	$usuario = $query->fetch(PDO::FETCH_OBJ);
	if(count($usuario->idusuario))
	{
		$query = $con->prepare('update `usuario` set contrasena = ?, fecha_modificacion = now() where idusuario = ?');
	$query->bindValue(1,md5($_POST["pass1"]));
	$query->bindValue(2,$usuario->idusuario, PDO::PARAM_INT);
	$query->execute();
	}
	header('location: ../accesar');
}
else {
	header('location: '. $_SERVER["HTTP_REFERER"]);
}


?>
