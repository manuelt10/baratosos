<?php 
session_start();
    $session = $_SESSION;
session_write_close();
if($session['autenticado'])
{
	if($_POST["us"] == 1)
	{
		$usado = 0;
	}
	else
	{
		$usado = 1;
	}
	require_once('../../phpfn/mysqlManager.php');
	$db = new mysqlManager();
	$db->updateRecord('cupon_compras',Array("usado" => $usado),Array("idcupon_compras" => $_POST["id"]));
	
}
 

?> 