<?php 
session_start();
$session = $_SESSION;
session_write_close();
if($session["autenticado"])
{
	require_once('../../phpfn/cndb.php');
	$con = conection();
	
	$query = $con->prepare("select contrasena from usuario where idusuario = ?");
	$query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
	$query->execute();
	
	$usr = $query->fetch(PDO::FETCH_OBJ);
	
	if($usr->contrasena == md5($_POST["password"]))
	{
		$query = $con->prepare('UPDATE `usuario` 
							SET `suspendido` = 1
							WHERE `idusuario` = ?');
		$query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
	    $query->execute();
		
		header("location: http://tumall.dophpfn/salir");
	}
	else {
		header("location: " . $_SERVER["HTTP_REFERER"]);
		
	}
	
	
}
?>