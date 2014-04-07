<?php 
session_start();
    $session = $_SESSION;
session_write_close();
if($session['autenticado'])
{
	require_once('../../phpfn/mysqlManager.php');
	$db = new mysqlManager();
	$db->updateRecord('cupon_galeria',Array('eliminar' => 1), Array('idcupon_galeria' => $_POST["idGaleria"]));
}



?>