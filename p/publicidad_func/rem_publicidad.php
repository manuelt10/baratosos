<?php 
session_start();
    $session = $_SESSION;
session_write_close();
if($session['autenticado'])
{
	require_once('../../phpfn/cndb.php');
	$con = conection();
	if($_POST["type"] == 1)
	{
		$query = $con->prepare('UPDATE `certificacion` 
								SET `visible` = 0
								WHERE `idcertificacion` = ?');
		$query->bindValue(1,$_POST["id"], PDO::PARAM_INT);
		$query->execute();
	}
	else if ($_POST["type"] <> 1)
	{
		$query = $con->prepare('UPDATE `ofertas` 
								SET `visible` = 0
								WHERE `idofertas` = ?');
		$query->bindValue(1,$_POST["id"], PDO::PARAM_INT);
		$query->execute();
	}
}
?>