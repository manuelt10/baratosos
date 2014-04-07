<?php 
session_start();
    $session = $_SESSION;
session_write_close();
if($session['autenticado'])
{
	require_once('../../phpfn/cndb.php');
	$con = conection();
	$query = $con->prepare('UPDATE `certificacion`
							set estado = ?, fecha_modificacion = now()
							where idcertificacion = ?');
	$query->bindValue(1,$_POST["status"]);
	$query->bindValue(2,$_POST["idCertif"], PDO::PARAM_INT);
	$query->execute();
}
?>