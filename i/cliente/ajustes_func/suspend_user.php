<?php 
session_start();
$session = $_SESSION;
session_write_close();
if($session["autenticado"])
{
	require_once('../../phpfn/cndb.php');
	$con = conection();
	$query = $con->prepare('UPDATE `usuario` 
							SET `suspendido` = 1
							WHERE `idusuario` = ?');
	$query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
    $query->execute();
	
	header("location: http://tumall.do/phpfn/salir");
}
?>