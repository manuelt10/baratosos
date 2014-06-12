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
		if($width = 700 and $height = 300)
		{
			require_once('../../phpfn/filemanager.php');
			require_once('../../phpfn/stringmanager.php');
			require_once('../../phpfn/mysqlManager.php');
			$db = new mysqlManager();
			$fM = new fileManager();
			$sM = new stringManager();
			$nombreImagen = $sM->generateFullRandomCode();
			if($fM->fileUpload($_FILES['image'], '../../images/galeria/' , $nombreImagen))
	        {
	        	if(($ext === 'jpg') or ($ext === 'jpeg') or ($ext === 'JPG') or ($ext === 'JPEG'))
	            {
	                $fM->createThumbnailJPEG('../../images/galeria/', $nombreImagen . '.' . $ext, '../../images/galeria/thumb150/', 150, 100);
	            }
	            if(($ext === 'png') or ($ext === 'PNG'))
	            {
	                $fM->createThumbnailPNG('../../images/galeria/', $nombreImagen . '.' . $ext, '../../images/galeria/thumb150/', 150, 0);
	            }
				$records = Array(
					"imagen" => $nombreImagen . '.' . $ext,
					"orden" => 0
					
				);
				$row = $db->insertRecord('galeria',$records);
				
				$imagen = $db->selectRecord('galeria');
				
				foreach($imagen->data as $row)
				{
					?>
					<div class="imageGallery">
						<img class="galleryImage" src="/images/galeria/thumb150/<?php echo $row->imagen ?>">
						<label>Visible:</label> <input type="checkbox"  class="idgaleria" value="<?php echo $row->idgaleria ?>" <?php echo $row->activo == 1 ? "checked" : ""; ?>><br />
						<button type="button" class="removeGal">Borrar</button>
						
					</div>
					<?php
					$imgCnt++;
				}
				
				
				
				
				
				
				
				
			}
		}
			
		
		
	}
	
}
			

?>