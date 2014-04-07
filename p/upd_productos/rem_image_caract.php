<?php 
session_start();
$session = $_SESSION;
session_write_close();
if($session["autenticado"])
{
	require_once('../../phpfn/cndb.php');
	$con = conection();
	$query = $con->prepare('UPDATE `imagencaracteristica` 
		SET `delete` = 1
		WHERE `imagen` = ?');
    $query->bindValue(1,$_POST["image"]);
    $query->execute();
}
?>