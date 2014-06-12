<?php 
session_start();
$session = $_SESSION;
session_write_close();
if($session["autenticado"])
{
	require_once('../../phpfn/cndb.php');
	$con = conection();
	$query = $con->prepare('UPDATE `caracteristica_producto` 
		SET `delete` = 1
		WHERE 	idcaracteristica_producto = ?');
    $query->bindValue(1,$_POST["idcaracteristica"]);
    $query->execute();
}

?>