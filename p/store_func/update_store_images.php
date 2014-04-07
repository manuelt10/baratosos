<?php 
session_start();
    $session = $_SESSION;
session_write_close();
if($session['autenticado'])
{
	require_once('../../phpfn/cndb.php');
	require_once('../../phpfn/filemanager.php');
	require_once('../../phpfn/stringmanager.php');
	$con = conection();
	$fM = new fileManager();
	$sM = new stringManager();
	$imagenesPermitidas = Array('jpg', 'png', 'jpeg', 'JPG', 'JPEG', 'PNG');
	if(is_uploaded_file($_FILES['profilePic']['tmp_name']))
	{
		list($widthProfile, $heightProfile) = getimagesize($_FILES['profilePic']['tmp_name']);
		$nombreImagenProfile = $sM->generateFullRandomCode();
		$extProfile = pathinfo($_FILES['profilePic']['name'], PATHINFO_EXTENSION);
		if((($widthProfile >= 150) and ($heightProfile >= 150)))
		{
			if(in_array($extProfile, $imagenesPermitidas))
		    {
		    	$query = $con->prepare('SELECT imagen FROM `usuario` 
										WHERE `idusuario` = ?');
				$query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
		        $query->execute();
				$imgBorrar = $query->fetchAll(PDO::FETCH_OBJ);
		    	foreach($imgBorrar as $iB)
				{
					unlink('../../images/profile/' . $iB->imagen);
					unlink('../../images/profile/thumb/' . $iB->imagen);
					unlink('../../images/profile/cr/' . $iB->imagen);
					unlink('../../images/profile/thumb100/' . $iB->imagen);
				}
				
				
				$query = $con->prepare('UPDATE `usuario` 
										SET `imagen` = ?, imagencoordx = ?, imagencoordy = ?
										WHERE `idusuario` = ?');
		        $query->bindValue(1,$nombreImagenProfile . '.' . $extProfile);
				$query->bindValue(2,$_POST["coordProfX"]);
				$query->bindValue(3,$_POST["coordProfY"]);
				$query->bindValue(4,$session["usuario"], PDO::PARAM_INT);
		        $query->execute();
				if($fM->fileUpload($_FILES['profilePic'], '../../images/profile/' , $nombreImagenProfile))
		        {
		        	if(($extProfile === 'jpg') or ($extProfile === 'JPG') or ($extProfile === 'jpeg') or ( $extProfile === 'JPEG'))
		            {
		            	$fM->createThumbnailJPEG('../../images/profile/', $nombreImagenProfile . '.' . $extProfile, '../../images/profile/thumb/', $_POST['origProfWidth'], 100);
						list($width, $height) = getimagesize('../../images/profile/thumb/' . $nombreImagenProfile  . '.' . $extProfile);
						$copy = imagecreatefromjpeg("../../images/profile/thumb/" . $nombreImagenProfile  . '.' . $extProfile);
        				$new = ImageCreateTrueColor(150, 150);
						imagecopyresampled($new, $copy, 0, 0,$_POST["coordProfX"], $_POST["coordProfY"], $width, $height, $width, $height);
						header('Content-Type: image/jpeg');
						imagejpeg($new,"../../images/profile/cr/" .  $nombreImagenProfile  . '.' . $extProfile , 100);
						imagedestroy($new);
						$fM->createThumbnailJPEG('../../images/profile/cr/', $nombreImagenProfile . '.' . $extProfile, '../../images/profile/thumb100/', 100, 100);
						
					}
		        	if(($extProfile === 'png') or ($extProfile === 'PNG'))
		            {
		            	$fM->createThumbnailPNG('../../images/profile/', $nombreImagenProfile . '.' . $extProfile, '../../images/profile/thumb/', $_POST['origProfWidth'], 0);
						list($width, $height) = getimagesize('../../images/profile/thumb/' . $nombreImagenProfile  . '.' . $extProfile);
						$copy = imagecreatefrompng("../../images/profile/thumb/" . $nombreImagenProfile  . '.' . $extProfile);
						
						$new = ImageCreateTrueColor(150, 150);
						
						
						imagecopyresampled($new, $copy, 0, 0,$_POST["coordProfX"], $_POST["coordProfY"],$width, $height, $width, $height);
						
						// Transparent Background
						
						header('Content-Type: image/png');
						imagepng($new,"../../images/profile/cr/" .  $nombreImagenProfile  . '.' . $extProfile , 0);
						imagedestroy($new);
						$fM->createThumbnailPNG('../../images/profile/cr/', $nombreImagenProfile . '.' . $extProfile, '../../images/profile/thumb100/', 100, 0);
						
					}
				}
				
		    	
			}
		}
	}
	if(is_uploaded_file($_FILES['banerPic']['tmp_name']))
	{
		list($widthBanner, $heightBanner) = getimagesize($_FILES['banerPic']['tmp_name']);
		$nombreImagenBanner = $sM->generateFullRandomCode();
		$extBanner = pathinfo($_FILES['banerPic']['name'], PATHINFO_EXTENSION);
		if((($widthBanner >= 800) and ($heightBanner >= 150)))
		{
			if(in_array($extBanner, $imagenesPermitidas))
		    {
		    	$query = $con->prepare('SELECT banner1 FROM `usuario` 
										WHERE `idusuario` = ?');
				$query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
		        $query->execute();
				$imgBorrar = $query->fetchAll(PDO::FETCH_OBJ);
		    	foreach($imgBorrar as $iB)
				{
					unlink('../../images/banners/' . $iB->banner1);
					unlink('../../images/banners/temp/' . $iB->banner1);
					unlink('../../images/banners/thumb/' . $iB->banner1);
				}
				
				$query = $con->prepare('UPDATE `usuario` 
										SET `banner1` = ?, bannercoordx = ?, bannercoordy = ?
										WHERE `idusuario` = ?');
		        $query->bindValue(1,$nombreImagenBanner . '.' . $extBanner);
				$query->bindValue(2,$_POST["coordBannerX"]);
				$query->bindValue(3,$_POST["coordBannerY"]);
				$query->bindValue(4,$session["usuario"], PDO::PARAM_INT);
		        $query->execute();
				$_POST["coordBannerX"] = $_POST["coordBannerX"] * 1.5;
				$_POST["coordBannerY"] = $_POST["coordBannerY"] * 1.5;
				$_POST["origBannerWidth"] = $_POST["origBannerWidth"] * 1.5;
				if($fM->fileUpload($_FILES['banerPic'], '../../images/banners/' , $nombreImagenBanner))
		        {
		        	if(($extBanner === 'jpg') or ($extBanner === 'jpeg') or ($extBanner === 'JPG') or ($extBanner === 'JPEG'))
		            {
		            	$fM->createThumbnailJPEG('../../images/banners/', $nombreImagenBanner . '.' . $extBanner, '../../images/banners/temp/', $_POST["origBannerWidth"], 100);
						$fM->createThumbnailJPEG('../../images/banners/', $nombreImagenBanner . '.' . $extBanner, '../../images/banners/thumb/', 533, 100);
						list($width, $height) = getimagesize('../../images/banners/temp/' . $nombreImagenBanner  . '.' . $extBanner);
						$copy = imagecreatefromjpeg("../../images/banners/temp/" . $nombreImagenBanner  . '.' . $extBanner);
        				$new = ImageCreateTrueColor(800, 150);
						imagecopyresampled($new, $copy, 0, 0, $_POST["coordBannerX"], $_POST["coordBannerY"], $width, $height, $width, $height);
						header('Content-Type: image/jpeg');
						imagejpeg($new,"../../images/banners/cr/" .  $nombreImagenBanner  . '.' . $extBanner , 100);
						imagedestroy($new);
					}
		        	if(($extBanner === 'png' or $extBanner === 'PNG'))
		            {
		            	$fM->createThumbnailPNG('../../images/banners/', $nombreImagenBanner . '.' . $extBanner, '../../images/banners/temp/', $_POST["origBannerWidth"], 0);
						$fM->createThumbnailPNG('../../images/banners/temp/', $nombreImagenBanner . '.' . $extBanner, '../../images/banners/thumb/', 533, 0);
						list($width, $height) = getimagesize('../../images/banners/temp/' . $nombreImagenBanner  . '.' . $extBanner);
						$copy = imagecreatefrompng("../../images/banners/temp/" . $nombreImagenBanner  . '.' . $extBanner);
        				$new = ImageCreateTrueColor(800, 150);
						imagecopyresampled($new, $copy, 0, 0, 0, $_POST["coordBannerY"], $width, $height, $width, $height);
						header('Content-Type: image/jpeg');
						imagepng($new,"../../images/banners/cr/" .  $nombreImagenBanner  . '.' . $extBanner, 0);
						imagedestroy($new);
					}
					
					
					
				}
			}
		}
	}
$query = $con->prepare('SELECT imagen, banner1, imagencoordx, imagencoordy, bannercoordx, bannercoordy FROM `usuario` 
										WHERE `idusuario` = ?');
		$query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
		$query->execute();
		$imgActualizar = $query->fetch(PDO::FETCH_OBJ);
		print_r($_POST);
	if($_FILES['profilePic']['size'] == 0)
	{
		
		
		$extencion = explode('.', $imgActualizar->imagen);
		if(($imgActualizar->imagencoordx <> $_POST["coordProfX"]) or ($imgActualizar->imagencoordy <> $_POST["coordProfY"]))
		{
			unlink("../../images/profile/cr/" .  $imgActualizar->imagen);
			list($width, $height) = getimagesize('../../images/profile/thumb/' . $imgActualizar->imagen);
			$nuevoNombre = $sM->generateFullRandomCode();
			
			if($extencion[1] == 'png')
			{
				$copy = imagecreatefrompng("../../images/profile/thumb/" . $imgActualizar->imagen);
				
	        	$new = imagecreatetruecolor(150, 150);
				$bg = imagecolorallocate ( $new, 255, 255, 255 );
				imagefill ( $new, 0, 0, $bg );
				
				imagecopyresampled($new, $copy, 0, 0,$_POST["coordProfX"], $_POST["coordProfY"],$width, $height, $width, $height);
				header('Content-Type: image/png');
				imagepng($new,"../../images/profile/cr/" .  $nuevoNombre . $extencion[1]);
				imagedestroy($new);
				rename("../../images/profile/thumb/" . $imgActualizar->imagen,"../../images/profile/thumb/" . $nuevoNombre . $extencion[1]);
				rename("../../images/profile/" . $imgActualizar->imagen,"../../images/profile/" . $nuevoNombre . $extencion[1]);
			} else {
				$copy = imagecreatefromjpeg("../../images/profile/thumb/" . $imgActualizar->imagen);
	        	$new = imagecreatetruecolor(150, 150);
				
				imagecopyresampled($new, $copy, 0, 0,$_POST["coordProfX"], $_POST["coordProfY"],$width, $height, $width, $height);
				header('Content-Type: image/jpeg');
				imagejpeg($new,"../../images/profile/cr/" . $nuevoNombre . $extencion[1]);
				imagedestroy($new);
				rename("../../images/profile/thumb/" . $imgActualizar->imagen,"../../images/profile/thumb/" . $nuevoNombre . $extencion[1]);
				rename("../../images/profile/" . $imgActualizar->imagen,"../../images/profile/" . $nuevoNombre . $extencion[1]);
			}
			
			$query = $con->prepare('UPDATE `usuario` 
									SET imagencoordx = ?, imagencoordy = ?, imagen = ?
									WHERE `idusuario` = ?');
			$query->bindValue(1,$_POST["coordProfX"]);
			$query->bindValue(2,$_POST["coordProfY"]);
			$query->bindValue(3,$nuevoNombre . $extencion[1]);
			$query->bindValue(4,$session["usuario"], PDO::PARAM_INT);
		    $query->execute();
		}
	}
	if($_FILES['banerPic']['size'] == 0)
	{
		//$_POST["coordBannerX"] = $_POST["coordBannerX"] * 1.5;
		//$_POST["coordBannerY"] = $_POST["coordBannerY"] * 1.5;
		$extencionBanner = explode('.', $imgActualizar->banner1);
		if(($imgActualizar->bannercoordx <> $_POST["coordBannerX"]) or ($imgActualizar->bannercoordy <> $_POST["coordBannerY"]))
		{
			$nuevoNombre = $sM->generateFullRandomCode();
			unlink("../../images/banners/cr/" .  $imgActualizar->banner1);
			list($width, $height) = getimagesize('../../images/banners/temp/' . $imgActualizar->banner1);
			
			if($extencionBanner[1] == 'png')
			{
				$copy = imagecreatefrompng('../../images/banners/temp/' . $imgActualizar->banner1);
	        	$new = ImageCreateTrueColor(800, 150);
	        	
				$bg = imagecolorallocate ( $new, 255, 255, 255 );
				imagefill ( $new, 0, 0, $bg );
						
				imagecopyresampled($new, $copy, 0, 0, 0, $_POST["coordBannerY"]*1.5,$width*1.5, $height*1.5, $width*1.5, $height*1.5);
				header('Content-Type: image/png');
				imagepng($new,"../../images/banners/cr/" .  $nuevoNombre . $extencionBanner[1]);
				imagedestroy($new);
				
				rename('../../images/banners/temp/' . $imgActualizar->banner1,'../../images/banners/temp/' . $nuevoNombre . $extencionBanner[1]);
				rename('../../images/banners/thumb/' . $imgActualizar->banner1,'../../images/banners/thumb/' . $nuevoNombre . $extencionBanner[1]);
				rename('../../images/banners/' . $imgActualizar->banner1,'../../images/banners/' . $nuevoNombre . $extencionBanner[1]);
			} else if (($extencionBanner[1] == 'jpg') or ($extencionBanner[1] == 'jpeg')){
				
				$copy = imagecreatefromjpeg('../../images/banners/temp/' . $imgActualizar->banner1);
	        	$new = ImageCreateTrueColor(800, 150);
				
				imagecopyresampled($new, $copy, 0, 0,0, $_POST["coordBannerY"]*1.5,$width*1.5, $height*1.5, $width*1.5, $height*1.5);
				header('Content-Type: image/jpeg');
				imagejpeg($new,"../../images/banners/cr/" .  $nuevoNombre . $extencionBanner[1]);
				imagedestroy($new);
				
				rename('../../images/banners/temp/' . $imgActualizar->banner1,'../../images/banners/temp/' . $nuevoNombre . $extencionBanner[1]);
				rename('../../images/banners/thumb/' . $imgActualizar->banner1,'../../images/banners/thumb/' . $nuevoNombre . $extencionBanner[1]);
				rename('../../images/banners/' . $imgActualizar->banner1,'../../images/banners/' . $nuevoNombre . $extencionBanner[1]);
			}
			
			$query = $con->prepare('UPDATE `usuario` 
									SET bannercoordx = ?, bannercoordy = ?, banner1 = ?
									WHERE `idusuario` = ?');
			$query->bindValue(1,$_POST["coordBannerX"]);
			$query->bindValue(2,$_POST["coordBannerY"]);
			$query->bindValue(3,$nuevoNombre . $extencionBanner[1]);
			$query->bindValue(4,$session["usuario"], PDO::PARAM_INT);
		    $query->execute();
		}
	}
	 
	
}
header("location: http://tumall.dotutienda/editar")
?>
