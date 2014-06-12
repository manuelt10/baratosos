<?php 
session_start();
$session = $_SESSION;
session_write_close();
if($session["autenticado"])
{
	require_once('../../phpfn/cndb.php');
	$con = conection();
	$query = $con->prepare('UPDATE `imagenproducto` 
		SET `delete` = 1
		WHERE `imagen` = ?');
    $query->bindValue(1,$_POST["image"]);
    $query->execute();
	
	$query = $con->prepare('UPDATE `img_temporales` 
		SET `delete` = 1
		WHERE `img_temporales` = ?');
    $query->bindValue(1,$_POST["image"]);
    $query->execute();
}
?>