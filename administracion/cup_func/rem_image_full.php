<?php 
session_start();
    $session = $_SESSION;
session_write_close();
if($session['autenticado'])
{
	require_once('../../phpfn/mysqlManager.php');
	$db = new mysqlManager();
	$imgCup = $db->selectRecord('cupon_galeria',NULL,Array('idcupon_galeria' => $_POST["idGaleria"]));
	
	unlink('../../images/cupon/' . $imgCup->data[0]->imagen);
	unlink('../../images/cupon/thumb150/' . $imgCup->data[0]->imagen);
	unlink('../../images/cupon/thumb400/' . $imgCup->data[0]->imagen);
	$db->deleteRecord('cupon_galeria',Array('idcupon_galeria' => $_POST["idGaleria"]));
		
}
?>