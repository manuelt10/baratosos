<?php 
/**/
session_start();
$session = $_SESSION;
session_write_close();
if($session["autenticado"])
{
	require_once('../../phpfn/cndb.php');
	$con = conection();
	$query = $con->prepare('SELECT nombretienda, correo FROM `usuario`
							where idtipousuario = 2
							and idusuario = :id');
	$query->bindValue(':id',$_POST["id"], PDO::PARAM_INT);
	$query->execute();
	$tienda = $query->fetch(PDO::FETCH_OBJ);
}

?>
<span><?php echo $tienda->nombretienda ?></span>
<span><?php echo $tienda->correo ?></span>
