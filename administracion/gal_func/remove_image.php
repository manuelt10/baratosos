<?php 
session_start();
    $session = $_SESSION;
session_write_close();
if($session['autenticado'])
{
	require_once('../../phpfn/mysqlManager.php');
	$db = new mysqlManager();
	$imgCup = $db->selectRecord('galeria',NULL,Array('idgaleria' => $_POST["idGaleria"]));
	
	unlink('../../images/galeria/' . $imgCup->data[0]->imagen);
	unlink('../../images/galeria/thumb150' . $imgCup->data[0]->imagen);
	$db->deleteRecord('galeria',Array('idgaleria' => $_POST["idGaleria"]));
		
}
?>