<?php  
session_start();
$session = $_SESSION;
session_write_close();
if($session["autenticado"])
{
	require_once('../../phpfn/cndb.php');
	$con = conection();
	$query = $con->prepare('update anunciopub 
							set terminado = 1
							where idanunciopub = :id');
			$query->bindValue(':id', $_POST["idanunciopub"], PDO::PARAM_INT);
			$query->execute();
}
header('location: ' . $_SERVER["HTTP_REFERER"]);
?>