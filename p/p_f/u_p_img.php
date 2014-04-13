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
list($width, $height) = getimagesize($_FILES['file']['tmp_name']);

$nombreImagen = $sM->generateFullRandomCode();
$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
$imagenesPermitidas = Array('jpg', 'png', 'jpeg');
if(($width >= 400) and ($height >= 400))
{
    if(in_array($ext, $imagenesPermitidas))
    {
        if($fM->fileUpload($_FILES['file'], '../../images/productos/' , $nombreImagen))
        {
            #inserta imagen temporal
            $query = $con->prepare('INSERT INTO `img_temporales`(`img_temporales`, `idusuario`, `idproducto`) VALUES (?,?,?)');
            $query->bindValue(1,$nombreImagen . '.' . $ext);
            $query->bindValue(2,$session["usuario"], PDO::PARAM_INT);
			$query->bindValue(3,$_POST["idproduct"], PDO::PARAM_INT);
            $query->execute();

            #thumb50 de ancho
            if(($ext === 'jpg') or ($ext === 'jpeg'))
            {
                $fM->createThumbnailJPEG('../../images/productos/', $nombreImagen . '.' . $ext, '../../images/productos/thumb50/', 50, 100);
            }
            if(($ext === 'png'))
            {
                $fM->createThumbnailPNG('../../images/productos/', $nombreImagen . '.' . $ext, '../../images/productos/thumb50/', 50, 0);
            }

            #thumb 150px de ancho
            if(($ext === 'jpg') or ($ext === 'jpeg'))
            {
                $fM->createThumbnailJPEG('../../images/productos/', $nombreImagen . '.' . $ext, '../../images/productos/thumb150/', 150, 100);
            }
            if(($ext === 'png'))
            {
                $fM->createThumbnailPNG('../../images/productos/', $nombreImagen . '.' . $ext, '../../images/productos/thumb150/', 150, 0);
            }
            #thumb 200px de ancho
            if(($ext === 'jpg') or ($ext === 'jpeg'))
            {
                $fM->createThumbnailJPEG('../../images/productos/', $nombreImagen . '.' . $ext, '../../images/productos/thumb200/', 200, 100);
            }
            if(($ext === 'png'))
            {
                $fM->createThumbnailPNG('../../images/productos/', $nombreImagen . '.' . $ext, '../../images/productos/thumb200/', 200, 0);
            }
            
            #thumb 400px de ancho
            if(($ext === 'jpg') or ($ext === 'jpeg'))
            {
                $fM->createThumbnailJPEG('../../images/productos/', $nombreImagen . '.' . $ext, '../../images/productos/thumb400/', 400, 100);
            }
            if(($ext === 'png'))
            {
                $fM->createThumbnailPNG('../../images/productos/', $nombreImagen . '.' . $ext, '../../images/productos/thumb400/', 400, 0);
            }


            #obtiene todas las imagenes temporales del usuario
            $query2 = $con->prepare('SELECT `img_temporales`, `idusuario` FROM `img_temporales` WHERE idusuario = ?');
            $query2->bindValue(1,$session["usuario"], PDO::PARAM_INT);
            $query2->execute();
            $imagenes = $query2->fetchAll(PDO::FETCH_OBJ);



            foreach($imagenes as $imgs)
            {
            
            list($widthO, $heightO) = getimagesize("../../images/productos/thumb150/".$imgs->img_temporales."");
									
			$baseDimm = 75;
			
			if($heightO > $widthO){
				$ratio = $widthO/$baseDimm;
				$modVal = $heightO;
			}
			
			else{
				$ratio = $heightO/$baseDimm;
				$modVal = $widthO;
			}
			
			$modVal /= $ratio; 
			$pos = ($modVal - $baseDimm)/2;
            
                ?>
                <div class="uplddImgWrap">
                	<input type="hidden" class="imageProductVal" value="<?php echo $imgs->img_temporales ?>">
                	<span class="itmImageMask imageDefaultMask">
                    	<img class="imageProduct imageDefault itmImage" <?php if($heightO > $widthO){  ?> width="<?php echo $baseDimm; ?>" height="<?php echo $modVal; ?>" style="top: -<?php echo $pos; ?>px;"  <?php }  else{ ?> height="<?php echo $baseDimm; ?>" width="<?php echo $modVal; ?>" style="left: -<?php echo $pos; ?>px;" <?php } ?> src="/images/productos/thumb150/<?php echo $imgs->img_temporales ?>">
                	</span>
                </div>
                
                <?php
            }

        }
    }
}
else
{
            echo "<span class='validation-label error-label'>Las dimensiones de las imagenes son incorrectas</span><br>";
            $query2 = $con->prepare('SELECT `img_temporales`, `idusuario` FROM `img_temporales` WHERE idusuario = ?');
            $query2->bindValue(1,$session["usuario"], PDO::PARAM_INT);
            $query2->execute();
            $imagenes = $query2->fetchAll(PDO::FETCH_OBJ);



            foreach($imagenes as $imgs)
            {
            
            list($widthO, $heightO) = getimagesize("../../images/productos/thumb150/".$imgs->img_temporales."");
									
			$baseDimm = 75;
			
			if($heightO > $widthO){
				$ratio = $widthO/$baseDimm;
				$modVal = $heightO;
			}
			
			else{
				$ratio = $heightO/$baseDimm;
				$modVal = $widthO;
			}
			
			$modVal /= $ratio; 
			$pos = ($modVal - $baseDimm)/2;
			
            ?>
            <div class="uplddImgWrap">
            	<input type="hidden" class="imageProductVal" value="<?php echo $imgs->img_temporales ?>">
            	<span class="itmImageMask imageDefaultMask">
                	<img class="imageProduct imageDefault itmImage" <?php if($heightO > $widthO){  ?> width="<?php echo $baseDimm; ?>" height="<?php echo $modVal; ?>" style="top: -<?php echo $pos; ?>px;"  <?php }  else{ ?> height="<?php echo $baseDimm; ?>" width="<?php echo $modVal; ?>" style="left: -<?php echo $pos; ?>px;" <?php } ?> src="/images/productos/thumb150/<?php echo $imgs->img_temporales ?>">
            	</span>
            </div>
            
            <?php
            }
}

?>
