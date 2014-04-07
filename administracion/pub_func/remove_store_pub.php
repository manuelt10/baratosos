<?php  
session_start();
$session = $_SESSION;
session_write_close();
if($session["autenticado"])
{
	require_once('../../phpfn/cndb.php');
	$con = conection();
	$query = $con->prepare('delete from tiendapub 
							where idtiendapub = :id');
			$query->bindValue(':id', $_POST["idtiendapub"], PDO::PARAM_INT);
			$query->execute();
}
header('location: ' . $_SERVER["HTTP_REFERER"]);
?>