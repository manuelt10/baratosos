<?php 
session_start();
    $session = $_SESSION;
session_write_close();
if($session['autenticado'])
{
	require_once('../../phpfn/mysqlManager.php');
	$db = new mysqlManager();
	$var = $db->updateRecord('cupon',Array('aprobado' => $_POST["stat"]),Array('idcupon' => $_POST["idcupon"]));
	
}


?>