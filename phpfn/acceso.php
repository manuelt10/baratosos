<?php 
require_once('cndb.php');
$con = conection();
$query = $con->prepare('SELECT * FROM `usuario` where correo = ?');
$query->bindValue(1,$_POST["correo"]);
$query->execute();
$usuario = $query->fetch(PDO::FETCH_OBJ);
if((empty($_POST["correo"])) or (empty($_POST["contrasena"])))
{
	session_start();
	$_SESSION["error"] = 1;
	session_write_close();
	echo "<script type='application/javascript'>history.go(-1)</script>";
}
else if(empty($usuario))
{
	session_start();
	$_SESSION["error"] = 2;
	session_write_close();
	echo "<script type='application/javascript'>history.go(-1)</script>";
}
else if(md5($_POST["contrasena"]) <> $usuario->contrasena)
{
	session_start();
	$_SESSION["error"] = 2;
	session_write_close();
	echo "<script type='application/javascript'>history.go(-1)</script>";
}
else if($usuario->activo == 0)
{
	session_start();
	$_SESSION["error"] = 3;
	session_write_close();
	echo "<script type='application/javascript'>history.go(-1)</script>";
}
else if($usuario->suspendido == 1)
{
	session_start();
	$_SESSION["error"] = 4;
	session_write_close();
	echo "<script type='application/javascript'>history.go(-1)</script>";
}
else if($usuario->baneado == 1)
{
	session_start();
	$_SESSION["error"] = 5;
	session_write_close();
	echo "<script type='application/javascript'>history.go(-1)</script>";
}
else {
	session_start();
	$_SESSION["autenticado"] = true;
	$_SESSION["usuario"] = $usuario->idusuario;
	$session = $_SESSION;
	session_write_close();
	
		$query = $con->prepare('UPDATE `usuario` 
								set fecha_ultimo_acceso = now() 
								where idusuario = ?');
		$query->bindValue(1, $usuario->idusuario, PDO::PARAM_INT);
		$query->execute();

		$query = $con->prepare('
		SELECT ` idimg_temp_caractprod`, `imagen`, `idusuario`, `idtemp_caracteristicas` 
		FROM `img_temp_caractprod` 
		WHERE `idusuario` = ?');
	    $query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
	    $query->execute();
		$img_caract = $query->fetchAll(PDO::FETCH_OBJ);
		foreach($img_caract as $iC)
		{
			unlink('../images/c_prod/' . $iC->imagen);
			unlink('../images/c_prod/thumb50/' . $iC->imagen);
			unlink('../images/c_prod/thumb150/' . $iC->imagen);
			unlink('../images/c_prod/thumb200/' . $iC->imagen);
			unlink('../images/c_prod/thumb400/' . $iC->imagen);
		}
		$query = $con->prepare('
		DELETE
		FROM `img_temp_caractprod` 
		WHERE `idusuario` = ?');
	    $query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
	    $query->execute();
		
		$query = $con->prepare('
		DELETE
		FROM `temp_caracteristicas` 
		WHERE `idusuario` = ?');
	    $query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
	    $query->execute();
		
		$query = $con->prepare('
		SELECT * FROM `img_temporales` WHERE `idusuario` = ?');
	    $query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
	    $query->execute();
		$img_temp = $query->fetchAll(PDO::FETCH_OBJ);
		foreach($img_temp as $iT)
		{
			unlink('../images/productos/' . $iT->imagen);
			unlink('../images/productos/thumb50/' . $iT->imagen);
			unlink('../images/productos/thumb150/' . $iT->imagen);
			unlink('../images/productos/thumb200/' . $iT->imagen);
			unlink('../images/productos/thumb400/' . $iT->imagen);
		}

		$query = $con->prepare('
		DELETE FROM `img_temporales` WHERE `idusuario` = ?');
	    $query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
	    $query->execute();
		
	switch($usuario->idtipousuario)
	{
		case 1: 
			header("location: http://tumall.do/cliente/fav");
			break;
		case 2: 
			header("location: http://tumall.do/productos/lista");
			break;
		case 3: 
			header("location: http://tumall.do/administracion/usuarios");
			break;
		default:
			header("location: ../home");
			break;
	}
}

?>