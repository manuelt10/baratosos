<?php 
session_start();
$session = $_SESSION;
session_write_close();
if($session["autenticado"])
{
	require_once('cndb.php');
	$con = conection();
	$query = $con->prepare('CALL prod_fav(:idusuario, :idproducto)');
	$query->bindValue(':idusuario',$_POST["idusuario"], PDO::PARAM_INT);
	$query->bindValue(':idproducto',$_POST["idproducto"], PDO::PARAM_INT);
	$query->execute();
	$conteo = $query->fetch(PDO::FETCH_OBJ);
	echo $conteo->cantidadFav;
}
?>
