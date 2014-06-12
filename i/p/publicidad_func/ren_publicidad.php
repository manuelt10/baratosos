<?php 
session_start();
    $session = $_SESSION;
session_write_close();
if($session['autenticado'])
{
	require_once('../../phpfn/cndb.php');
	$con = conection();
		$query = $con->prepare("UPDATE `ofertas` 
								SET `estado` = 'E',
								tiempo_aprobacion = ?,
								fecha_renovacion = now()
								WHERE `idofertas` = ?");
		$query->bindValue(1,$_POST["tiempoRenovacion"], PDO::PARAM_INT);
		$query->bindValue(2,$_POST["id"], PDO::PARAM_INT);
		$query->execute();
}
header("location: " . $_SERVER["HTTP_REFERER"]);
?>