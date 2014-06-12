<?php 
	require_once('../../phpfn/cndb.php');
	session_start();
	$session = $_SESSION;
	session_write_close();
	if($session["autenticado"])
	{
		$con = conection();
		$query = $con->prepare('delete from `img_temporales` where  `img_temporales` = ?');
	            $query->bindValue(1,$_POST["imageValue"]);
	            $query->execute();
		unlink('../../images/productos/' . $_POST["imageValue"]);
		unlink('../../images/productos/thumb50/' . $_POST["imageValue"]);
		unlink('../../images/productos/thumb150/' . $_POST["imageValue"]);
		unlink('../../images/productos/thumb200/' . $_POST["imageValue"]);
		unlink('../../images/productos/thumb400/' . $_POST["imageValue"]);
	}
?>