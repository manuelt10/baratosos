<?php 
session_start();
$session = $_SESSION;
session_write_close();
if($session["autenticado"])
{
	require_once('../../phpfn/cndb.php');
	$con = conection();
	$query = $con->prepare('select * from producto where idproducto = :idproducto
							and activo = 1
							and borrado = 0');
			$query->bindValue(':idproducto',$_POST["id"], PDO::PARAM_INT);
			$query->execute();
			$producto = $query->fetch(PDO::FETCH_OBJ);
			
	$query = $con->prepare('select count(*) as conteo from productopub');
			$query->execute();
			$cantidadProdPub = $query->fetch(PDO::FETCH_OBJ);
	
	if($cantidadProdPub->conteo <= 12)
	{
		if(count($producto->idproducto))
		{
			if(!empty($_POST["id"]))
			{
				$query = $con->prepare('insert into productopub(idproducto, duracion)
										select idproducto, :duracion
										from producto 
										where idproducto = :idproducto
										and activo = 1
										and borrado = 0');
				$query->bindValue(':idproducto',$_POST["id"], PDO::PARAM_INT);
				$query->bindValue(':duracion',$_POST["dias"], PDO::PARAM_INT);
				$query->execute();
				
			$query = $con->prepare('DELETE FROM `ofertas_producto`
									WHERE idofertas_producto = :id_off_prod');
				$query->bindValue(':id_off_prod',$_POST["id_off_prod"], PDO::PARAM_INT);
				$query->execute();
			#id_off_prod
			
			}
		}
	}
}
header('location: ' . $_SERVER["HTTP_REFERER"]);
?>