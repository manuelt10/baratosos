<?php 
session_start();
    $session = $_SESSION;
session_write_close();
if($session['autenticado'])
{
	require_once('../../phpfn/mysqlManager.php');
	$db = new mysqlManager();
	$db->updateRecord('galeria',Array("url" => $_POST["url"]),Array("idgaleria" => $_POST["idGaleria"]));
	
}
 

?> 