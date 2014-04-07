<?php 
	#require_once('../../phpfn/cndb.php');
	session_start();
	$session = $_SESSION;
	session_write_close();
	if($session["autenticado"])
	{
		$con = conection();
		$query = $con->prepare('
		SELECT ` idimg_temp_caractprod`, `imagen`, `idusuario`, `idtemp_caracteristicas` 
		FROM `img_temp_caractprod` 
		WHERE idtemp_caracteristicas = ?');
	    $query->bindValue(1,$_POST["idCar"], PDO::PARAM_INT);
	    $query->execute();
		$img_caract = $query->fetchAll(PDO::FETCH_OBJ);
		foreach($img_caract as $iC)
		{
			unlink('../../images/c_prod/' . $iC->imagen);
			unlink('../../images/c_prod/thumb50/' . $iC->imagen);
			unlink('../../images/c_prod/thumb150/' . $iC->imagen);
			unlink('../../images/c_prod/thumb200/' . $iC->imagen);
			unlink('../../images/c_prod/thumb400/' . $iC->imagen);
		}
		$query = $con->prepare('
		DELETE
		FROM `img_temp_caractprod` 
		WHERE idtemp_caracteristicas = ?');
	    $query->bindValue(1,$_POST["idCar"], PDO::PARAM_INT);
	    $query->execute();
		
		$query = $con->prepare('
		DELETE
		FROM `temp_caracteristicas` 
		WHERE idtemp_caracteristicas = ?');
	    $query->bindValue(1,$_POST["idCar"], PDO::PARAM_INT);
	    $query->execute();
		
		$query = $con->prepare('
		SELECT * FROM `img_temporales` WHERE `idusuario` = ?');
	    $query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
	    $query->execute();
		$img_temp = $query->fetchAll(PDO::FETCH_OBJ);
		foreach($img_temp as $iT)
		{
			unlink('../../images/productos/' . $iT->imagen);
			unlink('../../images/productos/thumb50/' . $iT->imagen);
			unlink('../../images/productos/thumb150/' . $iT->imagen);
			unlink('../../images/productos/thumb200/' . $iT->imagen);
			unlink('../../images/productos/thumb400/' . $iT->imagen);
		}
		
	}
?>