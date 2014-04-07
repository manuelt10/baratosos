<?php 
session_start();
$session = $_SESSION;
session_write_close();
if($session["autenticado"])
{
	require_once('../../phpfn/cndb.php');
	$con = conection();
			
	$query = $con->prepare('select count(*) as conteo from tiendapub');
			$query->execute();
			$cantidadTiendaPub = $query->fetch(PDO::FETCH_OBJ);
	
	if($cantidadTiendaPub->conteo <= 12)
	{
			if(!empty($_POST["id"]))
			{
				$query = $con->prepare('insert into tiendapub(idusuario, duracion)
										values(:idusuario, :duracion)');
				$query->bindValue(':idusuario',$_POST["id"], PDO::PARAM_INT);
				$query->bindValue(':duracion',$_POST["dias"], PDO::PARAM_INT);
				$query->execute();
				
				
			}
	}
}
header('location: ' . $_SERVER["HTTP_REFERER"]);
?>