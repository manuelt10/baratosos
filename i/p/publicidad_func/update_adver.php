<?php 

session_start();
    $session = $_SESSION;
session_write_close();
if($session['autenticado'] and !empty($_POST["titulo"]) and !empty($_POST["descripcion"])/* and !empty($_POST["monto"])*/)
{
	require_once('../../phpfn/cndb.php');
	require_once('../../phpfn/filemanager.php');
	require_once('../../phpfn/stringmanager.php');
	$con = conection();
	$fM = new fileManager();
	$sM = new stringManager();
	$imagenesPermitidas = Array('jpg', 'png', 'jpeg', 'gif', 'giff');
	if(is_uploaded_file($_FILES['file']['tmp_name']))
	{
		list($width, $height) = getimagesize($_FILES['file']['tmp_name']);
		$nombreImagen = $sM->generateFullRandomCode();
		$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
		if((($width >= 200) and ($height >= 300)))
		{
			if(in_array($ext,$imagenesPermitidas))
			{
				if($fM->fileUpload($_FILES['file'], '../../images/publicidad/' , $nombreImagen))
		        {
			        $query = $con->prepare('INSERT INTO `ofertas`(`idusuario`, `tipo`, `titulo`, `descripcion`, `imagen`, 
																`tiempo_duracion`, `estado`, `link`) 
												VALUES (?,?,?,?,?,?,?,?)');
					$query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
					$query->bindValue(2,"Anuncio");
					$query->bindValue(3,$_POST["titulo"]); 
					$query->bindValue(4,$_POST["descripcion"]);
					$query->bindValue(5,$nombreImagen . '.' . $ext);
					$query->bindValue(6,$_POST["tiempoDuracion"], PDO::PARAM_INT);
					$query->bindValue(7,"E");
					$query->bindValue(8,$_POST["link"]);
			        $query->execute();
					header("location: http://tumall.do/publicidad");
				}
				else {
					session_start();
					$_SESSION["error"] = 4;
					header("location: " . $_SERVER["HTTP_REFERER"]);
					session_write_close();
				}
				
			}
			else
				{
					session_start();
					$_SESSION["error"] = 3;
					header("location: " . $_SERVER["HTTP_REFERER"]);
					session_write_close();
				}
		}
		else
			{
				session_start();
				$_SESSION["error"] = 2;
				header("location: " . $_SERVER["HTTP_REFERER"]);
				session_write_close();
			}
	}
	else
		{
			$query = $con->prepare('INSERT INTO `ofertas`(`idusuario`, `tipo`, `titulo`, `descripcion`, `imagen`, 
																 `tiempo_duracion`, `estado`, `link`) 
												VALUES (?,?,?,?,?,?,?,?)');
			$query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
			$query->bindValue(2,"Anuncio");
			$query->bindValue(3,$_POST["titulo"]); 
			$query->bindValue(4,$_POST["descripcion"]);
			$query->bindValue(5,$nombreImagen . '.' . $ext);
			$query->bindValue(6,$_POST["tiempoDuracion"], PDO::PARAM_INT);
			$query->bindValue(7,"E");
			$query->bindValue(8,$_POST["link"]);
			$query->execute();
			header("location: http://tumall.do/publicidad");
		}
}
else {
	session_start();
	$_SESSION["error"] = 1;
	header("location: " . $_SERVER["HTTP_REFERER"]);
	session_write_close();
}

?>