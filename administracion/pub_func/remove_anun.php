<?php  
session_start();
$session = $_SESSION;
session_write_close();
if($session["autenticado"])
{
	require_once('../../phpfn/cndb.php');
	$con = conection();
	$query = $con->prepare('select * from anunciopub 
							where idanunciopub = :id');
			$query->bindValue(':id', $_POST["idanunciopub"], PDO::PARAM_INT);
			$query->execute();
	$anuncios = $query->fetch(PDO::FETCH_OBJ);
	
	unlink('../../images/publicidad/' . $anuncios->image);
	
	$query = $con->prepare('delete from anunciopub 
							where idanunciopub = :id');
			$query->bindValue(':id', $_POST["idanunciopub"], PDO::PARAM_INT);
			$query->execute();
}
header('location: ' . $_SERVER["HTTP_REFERER"]);
?>