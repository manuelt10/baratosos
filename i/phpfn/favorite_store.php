<?php 
session_start();
$session = $_SESSION;
session_write_close();
if($session["autenticado"])
{
	require_once('cndb.php');
	$con = conection();
	$query = $con->prepare('CALL usr_fav(:idusuario, :idusuario_favorited)');
	$query->bindValue(':idusuario',$_POST["idusuario"], PDO::PARAM_INT);
	$query->bindValue(':idusuario_favorited',$_POST["idusuario_favorited"], PDO::PARAM_INT);
	$query->execute();
	$conteo = $query->fetch(PDO::FETCH_OBJ);
	echo $conteo->cantidadFav;
}
?>
