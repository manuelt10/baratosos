<?php 
session_start();
    $session = $_SESSION;
session_write_close();
if($session['autenticado'])
{
	require_once('../../phpfn/mysqlManager.php');
	$db = new mysqlManager();
	$estatus = $db->selectRecord('cupon',Array('estatus'),Array('idcupon' => (int)$_POST["idcupon"]));
	if($estatus->data[0]->estatus == 1)
	{
		$var = $db->updateRecord('cupon',Array('estatus' => 0),Array('idcupon' => 0 + $_POST["idcupon"]));
	}
	else
	{
		$var = $db->updateRecord('cupon',Array('estatus' => 1),Array('idcupon' => 0 + $_POST["idcupon"]));
	}
}


?>