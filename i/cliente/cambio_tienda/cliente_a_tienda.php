<?php 
session_start();
$session = $_SESSION;
session_write_close();
if(empty($_POST["nombreTienda"]) or empty($_POST["telefono1"]))
{
	session_start();
	$_SESSION["error"] = 1;
	session_write_close();
	echo "<script>history.go(-1)</script>";
}
else if (!isset($_POST["terminos"]))
{
	session_start();
	$_SESSION["error"] = 2;
	session_write_close();
	echo "<script>history.go(-1)</script>";
}
else
	{
		require_once('../../phpfn/cndb.php');
		$con = conection();
		$query = $con->prepare('UPDATE `usuario` 
							SET `nombretienda` = ?, `telefono1` = ?, `idtipousuario` = 2
							WHERE `idusuario` = ?');
	    $query->bindValue(1,$_POST["nombreTienda"]);
		$query->bindValue(2,$_POST["telefono1"]);
		$query->bindValue(3,$session["usuario"], PDO::PARAM_INT);
	    $query->execute();
		
		session_start();
		unset($_SESSION["autenticado"]);
		unset($_SESSION["usuario"]);
		header("location: ../../accesar");
		session_write_close();
	}

?>