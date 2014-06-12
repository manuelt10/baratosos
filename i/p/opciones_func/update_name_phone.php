<?php 
session_start();
$session = $_SESSION;
session_write_close();
if($session["autenticado"] and !empty($_POST["nombre"]))
{
	require_once('../../phpfn/cndb.php');
	$con = conection();
	$query = $con->prepare('UPDATE `usuario` 
							SET `nombre` = ?, 
							`telefonopersonal` = ?
							WHERE `idusuario` = ?');
    $query->bindValue(1,$_POST["nombre"]);
	$query->bindValue(2,$_POST["telefonopersonal"]);
	$query->bindValue(3,$session["usuario"], PDO::PARAM_INT);
    $query->execute();
	
}
?>