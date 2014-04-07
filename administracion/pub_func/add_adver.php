<?php 
session_start();
    $session = $_SESSION;
session_write_close();
if($session['autenticado'] and !empty($_POST["link"]) and !empty($_POST["dias"]) and $_FILES['file']['size'] > 0)
{
	require_once('../../phpfn/cndb.php');
	require_once('../../phpfn/filemanager.php');
	require_once('../../phpfn/stringmanager.php');
	$con = conection();
	$fM = new fileManager();
	$sM = new stringManager();
	$imagenesPermitidas = Array('jpg', 'png', 'jpeg');
	if(is_uploaded_file($_FILES['file']['tmp_name']))
	{
		list($width, $height) = getimagesize($_FILES['file']['tmp_name']);
		$nombreImagen = $sM->generateFullRandomCode();
		$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
		if($_POST["posicion"] == 'banner-right')
		{
			if((($width == 200) and ($height >= 150) or ($height <= 300)))
			{
				if(in_array($ext,$imagenesPermitidas))
				{
					if($fM->fileUpload($_FILES['file'], '../../images/publicidad/' , $nombreImagen))
			        {
			        	/*INSERT INTO `anunciopub`( `image`, `link`, `duracion`, `idusuario`) VALUES (?,?,?,?)*/
			        	$query = $con->prepare('INSERT INTO `anunciopub`( `image`, `link`, `duracion`, `posicion`, `idusuario`) 
			        							VALUES (:image,:link,:duracion,:posicion,:idusuario)');
						$query->bindValue(':image',$nombreImagen . '.' . $ext);
						$query->bindValue(':link', $_POST["link"]);
						$query->bindValue(':duracion',$_POST["dias"], PDO::PARAM_INT); 
						$query->bindValue(':posicion',$_POST["posicion"]); 
						$query->bindValue(':idusuario',$session["usuario"], PDO::PARAM_INT);
						$query->execute();
					}
				}
			}
		}
		else if($_POST["posicion"] == 'banner-top')
		{
			if(($width == 728) and ($height == 90))
			{
				if(in_array($ext,$imagenesPermitidas))
				{
					if($fM->fileUpload($_FILES['file'], '../../images/publicidad/' , $nombreImagen))
			        {
			        	/*INSERT INTO `anunciopub`( `image`, `link`, `duracion`, `idusuario`) VALUES (?,?,?,?)*/
			        	$query = $con->prepare('INSERT INTO `anunciopub`( `image`, `link`, `duracion`, `posicion`, `idusuario`) 
			        							VALUES (:image,:link,:duracion,:posicion,:idusuario)');
						$query->bindValue(':image',$nombreImagen . '.' . $ext);
						$query->bindValue(':link', $_POST["link"]);
						$query->bindValue(':duracion',$_POST["dias"], PDO::PARAM_INT); 
						$query->bindValue(':posicion',$_POST["posicion"]); 
						$query->bindValue(':idusuario',$session["usuario"], PDO::PARAM_INT);
						$query->execute();
					}
				}
			}
		}
	}
}
header("location: " . $_SERVER["HTTP_REFERER"]);
?>