<?php  
session_start();
$session = $_SESSION;
session_write_close();
if($session["autenticado"])
{
	require_once('../../phpfn/cndb.php');
	$con = conection();
	$query = $con->prepare('update productopub 
							set terminado = 1
							where idproductopub = :id');
			$query->bindValue(':id', $_POST["idproductopub"], PDO::PARAM_INT);
			$query->execute();
}
header('location: ' . $_SERVER["HTTP_REFERER"]);
?>