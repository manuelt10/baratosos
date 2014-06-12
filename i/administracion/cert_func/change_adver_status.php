<?php 
session_start();
    $session = $_SESSION;
session_write_close();
if($session['autenticado'])
{
	require_once('../../phpfn/cndb.php');
	$con = conection();
	if($_POST["status"] == 'A')
	{
		$query = $con->prepare('UPDATE `ofertas`
							set estado = ?, fecha_modificacion = now(), fecha_aprobacion = now(), tiempo_duracion = ?
							where idofertas = ?');
	$query->bindValue(1,$_POST["status"]);
	$query->bindValue(2,$_POST["duracion"], PDO::PARAM_INT);
	$query->bindValue(3,$_POST["idOfer"], PDO::PARAM_INT);
	$query->execute();
	
	$query = $con->prepare('INSERT INTO `anunciopub`( `image`, `link`, `duracion`, `idusuario`) 
		        			select imagen, link, duracion, :usuario from ofertas where idofertas = :id');
	$query->bindValue(':idusuario',$session["usuario"], PDO::PARAM_INT);
	$query->bindValue(':id',$_POST["idOfer"], PDO::PARAM_INT);
	$query->execute();
	}
	else
		{
	$query = $con->prepare('UPDATE `ofertas`
							set estado = ?, fecha_modificacion = now()
							where idofertas = ?');
	$query->bindValue(1,$_POST["status"]);
	$query->bindValue(2,$_POST["idOfer"], PDO::PARAM_INT);
	$query->execute();
		}
	
}
?>