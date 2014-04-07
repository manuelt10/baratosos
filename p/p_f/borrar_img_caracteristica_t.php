<?php 
	require_once('../../phpfn/cndb.php');
	session_start();
	$session = $_SESSION;
	session_write_close();
	if($session["autenticado"])
	{
		$con = conection();
		$query = $con->prepare('delete from `img_temp_caractprod` where  `imagen` = ?');
	            $query->bindValue(1,$_POST["imageValue"]);
	            $query->execute();
		unlink('../../images/c_prod/' . $_POST["imageValue"]);
		unlink('../../images/c_prod/thumb50/' . $_POST["imageValue"]);
		unlink('../../images/c_prod/thumb150/' . $_POST["imageValue"]);
		unlink('../../images/c_prod/thumb200/' . $_POST["imageValue"]);
		unlink('../../images/c_prod/thumb400/' . $_POST["imageValue"]);
	}
?>