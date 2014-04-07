<?php 
session_start();
    $session = $_SESSION;
session_write_close();
if($session['autenticado'])
{
	require_once('../../phpfn/mysqlManager.php');
	$db = new mysqlManager();
	$var = $db->updateRecord('cupon',Array('elim_admin' => 1),Array('idcupon' => $_POST["idcupon"]));
	
}


?>