<?php 
session_start();
$session = $_SESSION;
session_write_close();
if($session["autenticado"])
{ 
	require_once('../../phpfn/cndb.php');
	$con = conection();
	$query = $con->prepare('UPDATE `temp_caracteristicas` 
							SET `caracteristica` = ?
							WHERE `idtemp_caracteristicas` = ?');
    $query->bindValue(1,$_POST["temp_descripcion"]);
	$query->bindValue(2,$_POST["idcaracteristica"], PDO::PARAM_INT);
    $query->execute();
}
?>