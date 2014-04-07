<?php 
session_start();
    $session = $_SESSION;
session_write_close();
if($session['autenticado'])
{
	$ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
	$imagenesPermitidas = Array('jpg', 'png', 'jpeg','JPG', 'PNG', 'JPEG');
	if(in_array($ext, $imagenesPermitidas))
	{
		list($width, $height) = getimagesize($_FILES['image']['tmp_name']);
		if($width >= 400 and $height >= 400)
		{
			require_once('../../phpfn/filemanager.php');
			require_once('../../phpfn/stringmanager.php');
			require_once('../../phpfn/mysqlManager.php');
			$db = new mysqlManager();
			$fM = new fileManager();
			$sM = new stringManager();
			$nombreImagen = $sM->generateFullRandomCode();
			if($fM->fileUpload($_FILES['image'], '../../images/cupon/' , $nombreImagen))
	        {
	        	if(($ext === 'jpg') or ($ext === 'jpeg') or ($ext === 'JPG') or ($ext === 'JPEG'))
	            {
	                $fM->createThumbnailJPEG('../../images/cupon/', $nombreImagen . '.' . $ext, '../../images/cupon/thumb400/', 400, 100);
					$fM->createThumbnailJPEG('../../images/cupon/thumb400/', $nombreImagen . '.' . $ext, '../../images/cupon/thumb150/', 150, 100);
	            }
	            if(($ext === 'png') or ($ext === 'PNG'))
	            {
	                $fM->createThumbnailPNG('../../images/cupon/', $nombreImagen . '.' . $ext, '../../images/cupon/thumb400/', 400, 0);
					$fM->createThumbnailPNG('../../images/cupon/thumb400/', $nombreImagen . '.' . $ext, '../../images/cupon/thumb150/', 150, 0);
	            }
				$records = Array(
					"imagen" => $nombreImagen . '.' . $ext
				);
				$row = $db->insertRecord('cupon_galeria',$records);
				
				if(!empty($_POST["id"]))
				{
				$where = Array("idcupon" => $_POST["id"]);
				$imagenesOriginales = $db->selectRecord('cupon_galeria',NULL,$where);
					$imgCnt = 0;
					foreach($imagenesOriginales->data as $row)
					{
						?>
						<div class="couponImagesWrap">
							<input type="radio" name="idCuponImage" class="idCuponImage" id="couponPrincImg<?php echo $imgCnt; ?>" value="<?php echo $row->idcupon_galeria ?>" <?php echo $row->principal == 1 ? "checked" : ""; ?>>
							<label class="settLbl" for="couponPrincImg<?php echo $imgCnt; ?>">Principal</label>
							<br />
							<img class="cuponImage" src="images/cupon/thumb150/<?php echo $row->imagen ?>">
						</div>
						<?php
						$imgCnt++;
					}
				}
				$where = Array("idcupon" => NULL);
				$imagenCupon = $db->selectRecord('cupon_galeria',NULL,$where);
					$imgCnt = 100;
					foreach($imagenCupon->data as $row)
					{
						?>
						<div class="couponImagesWrap">
							<input type="radio" name="idCuponImage" class="idCuponImage" id="couponPrincImg<?php echo $imgCnt; ?>" value="<?php echo $row->idcupon_galeria ?>" <?php echo $row->principal == 1 ? "checked" : ""; ?>>
							<label class="settLbl" for="couponPrincImg<?php echo $imgCnt; ?>">Principal</label>
							<br />
							<img class="cuponImage" src="images/cupon/thumb150/<?php echo $row->imagen ?>">
						</div>
						<?php
						$imgCnt++;
					}
				
				 
			}
    	}
			
		
		
	}
	
}

?>