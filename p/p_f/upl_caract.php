<?php 
session_start();
    $session = $_SESSION;
session_write_close();
require_once('../../phpfn/cndb.php');
require_once('../../phpfn/filemanager.php');
require_once('../../phpfn/stringmanager.php');
$con = conection();
$fM = new fileManager();
$sM = new stringManager();
$imagenesPermitidas = Array('jpg', 'png', 'jpeg');
list($width, $height) = getimagesize($_FILES['file']['tmp_name']);
$nombreImagen = $sM->generateFullRandomCode();
$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
$imagenesPermitidas = Array('jpg', 'png', 'jpeg');
if(($width >= 400) and ($height >= 400))
{
	if(in_array($ext, $imagenesPermitidas))
    {
    	$query = $con->prepare('INSERT INTO `img_temp_caractprod`(`imagen`, `idusuario`,`idtemp_caracteristicas` ) VALUES (?,?,?)');
        $query->bindValue(1,$nombreImagen . '.' . $ext);
        $query->bindValue(2,$session["usuario"], PDO::PARAM_INT);
		$query->bindValue(3,$_POST["id"], PDO::PARAM_INT);
        $query->execute();
		if($fM->fileUpload($_FILES['file'], '../../images/c_prod/' , $nombreImagen))
        {
		if(($ext === 'jpg') or ($ext === 'jpeg'))
            {
                $fM->createThumbnailJPEG('../../images/c_prod/', $nombreImagen . '.' . $ext, '../../images/c_prod/thumb50/', 50, 100);
            }
            if(($ext === 'png'))
            {
                $fM->createThumbnailPNG('../../images/c_prod/', $nombreImagen . '.' . $ext, '../../images/c_prod/thumb50/', 50, 0);
            }

            #thumb 150px de ancho
            if(($ext === 'jpg') or ($ext === 'jpeg'))
            {
                $fM->createThumbnailJPEG('../../images/c_prod/', $nombreImagen . '.' . $ext, '../../images/c_prod/thumb150/', 150, 100);
            }
            if(($ext === 'png'))
            {
                $fM->createThumbnailPNG('../../images/c_prod/', $nombreImagen . '.' . $ext, '../../images/c_prod/thumb150/', 150, 0);
            }
            #thumb 200px de ancho
            if(($ext === 'jpg') or ($ext === 'jpeg'))
            {
                $fM->createThumbnailJPEG('../../images/c_prod/', $nombreImagen . '.' . $ext, '../../images/c_prod/thumb200/', 200, 100);
            }
            if(($ext === 'png'))
            {
                $fM->createThumbnailPNG('../../images/c_prod/', $nombreImagen . '.' . $ext, '../../images/c_prod/thumb200/', 200, 0);
            }
            
            #thumb 400px de ancho
            if(($ext === 'jpg') or ($ext === 'jpeg'))
            {
                $fM->createThumbnailJPEG('../../images/c_prod/', $nombreImagen . '.' . $ext, '../../images/c_prod/thumb400/', 400, 100);
            }
            if(($ext === 'png'))
            {
                $fM->createThumbnailPNG('../../images/c_prod/', $nombreImagen . '.' . $ext, '../../images/c_prod/thumb400/', 400, 0);
            }
			
			if(empty($_POST["id"]))
			{
				$query2 = $con->prepare('SELECT `imagen` FROM `img_temp_caractprod` where `idusuario` = ? and idtemp_caracteristicas is null');
	            $query2->bindValue(1,$session["usuario"], PDO::PARAM_INT);
	            $query2->execute();
	            $imagenes = $query2->fetchAll(PDO::FETCH_OBJ);
			}
			else
				{
					$query2 = $con->prepare('SELECT `imagen` FROM `img_temp_caractprod` where `idusuario` = ? and idtemp_caracteristicas = ?');
		            $query2->bindValue(1,$session["usuario"], PDO::PARAM_INT);
					$query2->bindValue(2,$_POST["id"], PDO::PARAM_INT);
		            $query2->execute();
		            $imagenes = $query2->fetchAll(PDO::FETCH_OBJ);
				}
			

	            foreach($imagenes as $imgs)
	            {
	            
	            list($width, $height) = getimagesize("../../images/c_prod/thumb150/".$imgs->imagen."");
									
				$baseDimm = 75;
				
				if($height > $width){
					$ratio = $width/$baseDimm;
					$modVal = $height;
				}
				
				else{
					$ratio = $height/$baseDimm;
					$modVal = $width;
				}
				
				$modVal /= $ratio; 
				$pos = ($modVal - $baseDimm)/2;
	            
	                ?>
	                    <div class="uplddImgWrap">
							<input type="hidden" class="imageProductVal" value="<?php echo $imgs->imagen ?>">
							<span class="itmImageMask">
								<img class="imageCaractProducto itmImage" <?php if($height > $width){  ?> width="<?php echo $baseDimm; ?>" height="<?php echo $modVal; ?>" style="top: -<?php echo $pos; ?>px;"  <?php }  else{ ?> height="<?php echo $baseDimm; ?>" width="<?php echo $modVal; ?>" style="left: -<?php echo $pos; ?>px;" <?php } ?> src="images/c_prod/thumb150/<?php echo $imgs->imagen ?>">
							</span>
						</div>
	                <?php
	            }
            }
	}
	else
	{
			echo "<span class='validation-label error-label'>No es un archivo valido</span><br>";
            if(empty($_POST["id"]))
			{
				$query2 = $con->prepare('SELECT `imagen` FROM `img_temp_caractprod` where `idusuario` = ? and idtemp_caracteristicas is null');
	            $query2->bindValue(1,$session["usuario"], PDO::PARAM_INT);
	            $query2->execute();
	            $imagenes = $query2->fetchAll(PDO::FETCH_OBJ);
			}
			else
				{
					$query2 = $con->prepare('SELECT `imagen` FROM `img_temp_caractprod` where `idusuario` = ? and idtemp_caracteristicas = ?');
		            $query2->bindValue(1,$session["usuario"], PDO::PARAM_INT);
					$query2->bindValue(2,$_POST["id"], PDO::PARAM_INT);
		            $query2->execute();
		            $imagenes = $query2->fetchAll(PDO::FETCH_OBJ);
				}



            foreach($imagenes as $imgs)
            {
            
            list($width, $height) = getimagesize("../../images/c_prod/thumb150/".$imgs->imagen."");
									
			$baseDimm = 75;
			
			if($height > $width){
				$ratio = $width/$baseDimm;
				$modVal = $height;
			}
			
			else{
				$ratio = $height/$baseDimm;
				$modVal = $width;
			}
			
			$modVal /= $ratio; 
			$pos = ($modVal - $baseDimm)/2;
            
            
            ?>
                <div class="uplddImgWrap">
					<input type="hidden" class="imageProductVal" name="idImageCaractProductoVal" value="<?php echo $imgs->imagen ?>">
					<span class="itmImageMask">
						<img class="imageCaractProducto itmImage" <?php if($height > $width){  ?> width="<?php echo $baseDimm; ?>" height="<?php echo $modVal; ?>" style="top: -<?php echo $pos; ?>px;"  <?php }  else{ ?> height="<?php echo $baseDimm; ?>" width="<?php echo $modVal; ?>" style="left: -<?php echo $pos; ?>px;" <?php } ?> src="images/c_prod/thumb150/<?php echo $imgs->imagen ?>">
					</span>
				</div>
            <?php
            }
	}

}
else
	{
			echo "<span class='validation-label error-label'>Las dimensiones de las imagenes son incorrectas</span><br>";
            if(empty($_POST["id"]))
			{
				$query2 = $con->prepare('SELECT `imagen` FROM `img_temp_caractprod` where `idusuario` = ? and idtemp_caracteristicas is null');
	            $query2->bindValue(1,$session["usuario"], PDO::PARAM_INT);
	            $query2->execute();
	            $imagenes = $query2->fetchAll(PDO::FETCH_OBJ);
			}
			else
				{
					$query2 = $con->prepare('SELECT `imagen` FROM `img_temp_caractprod` where `idusuario` = ? and idtemp_caracteristicas = ?');
		            $query2->bindValue(1,$session["usuario"], PDO::PARAM_INT);
					$query2->bindValue(2,$_POST["id"], PDO::PARAM_INT);
		            $query2->execute();
		            $imagenes = $query2->fetchAll(PDO::FETCH_OBJ);
				}



            foreach($imagenes as $imgs)
            {
            
            list($width, $height) = getimagesize("../../images/c_prod/thumb150/".$imgs->imagen."");
									
			$baseDimm = 75;
			
			if($height > $width){
				$ratio = $width/$baseDimm;
				$modVal = $height;
			}
			
			else{
				$ratio = $height/$baseDimm;
				$modVal = $width;
			}
			
			$modVal /= $ratio; 
			$pos = ($modVal - $baseDimm)/2;
            
            
                ?>
                    <div class="uplddImgWrap">
						<input type="hidden" class="imageProductVal" name="idImageCaractProductoVal" value="<?php echo $imgs->imagen ?>">
						<span class="itmImageMask">
							<img class="imageCaractProducto itmImage" <?php if($height > $width){  ?> width="<?php echo $baseDimm; ?>" height="<?php echo $modVal; ?>" style="top: -<?php echo $pos; ?>px;"  <?php }  else{ ?> height="<?php echo $baseDimm; ?>" width="<?php echo $modVal; ?>" style="left: -<?php echo $pos; ?>px;" <?php } ?> src="images/c_prod/thumb150/<?php echo $imgs->imagen ?>">
						</span>
					</div>
                <?php
            }
	}
/*
print_r($_POST);
echo "<br>";
print_r($_FILES);
*/
?>