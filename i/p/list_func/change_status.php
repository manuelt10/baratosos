<?php 
session_start();
$session = $_SESSION;
session_write_close();
if($session["autenticado"])
{
	require_once('../../phpfn/cndb.php');
	$con = conection();
	$query = $con->prepare('CALL prod_chang_stat(?)');
    $query->bindValue(1,$_POST["idproducto"], PDO::PARAM_INT);
    $query->execute();
}
?>