<?php 
session_start();
$session = $_SESSION;
session_write_close();
if($session["autenticado"] and !empty($_POST["certificadoTienda"]))
{
	require_once('../../phpfn/cndb.php');
	$con = conection();
	$query = $con->prepare('
	INSERT INTO `certificacion`( `idusuario`, `texto`, `estado`, `fecha_modificacion`) 
	VALUES (?,?,?,NOW());');
	$query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
	$query->bindValue(2,$_POST["certificadoTienda"]);
	$query->bindValue(3,'E');
	$query->execute();
	
}
header('location: http://tumall.dopublicidad/')
?>