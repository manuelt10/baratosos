<?php 
class fileManager
{
	/*
	 * createThumbnailJPEG: Crea un thumbnail para JPEG 
	 */
	public function createThumbnailJPEG($path, $imagename, $thumbpath, $thumbWidth,  $quality)
	{
		 $img = imagecreatefromjpeg( "{$path}{$imagename}" );
		 $width = imagesx( $img );
      	 $height = imagesy( $img );
		 $new_width = $thumbWidth;
		 if($width <= $thumbWidth)
		 {
		 	$new_width = $width;
		 }
		 $new_height = floor( $height * ( $new_width / $width ) );
		 $srcx = 0;
		 $srcy = 0;
      	 
		 $tmp_img = imagecreatetruecolor( $new_width, $new_height );
		 imagecopyresampled( $tmp_img, $img, 0, 0, $srcx, $srcy, $new_width, $new_height, $width, $height);
		 imagejpeg( $tmp_img, "{$thumbpath}{$imagename}",100);
		 
		 $img = imagecreatefromjpeg($path . $imagename); 
		 imagejpeg($img, $path . $imagename, $quality); //75 quality setting 
		 imagedestroy($img);
	}
	
	/*
	 * createThumbnailPNG: Crea thumbnails para PNG 
     * Calidad en PNG es de 0 a 9, siendo 0 la mejor calidad
	 */
	public function createThumbnailPNG($path, $imagename, $thumbpath,$thumbWidth, $quality)
	{
		 $img = imagecreatefrompng( "{$path}{$imagename}" );
		 $width = imagesx( $img );
      	 $height = imagesy( $img );
		 $new_width = $thumbWidth;
		 if($width <= $thumbWidth)
		 {
		 	$new_width = $width;
		 }
		 $new_height = floor( $height * ( $new_width / $width ) );
		 $srcx = 0;
		 $srcy = 0;

		 $tmp_img = imagecreatetruecolor( $new_width, $new_height );
		
						  $bg = imagecolorallocate ( $tmp_img, 255, 255, 255 );
						imagefill ( $tmp_img, 0, 0, $bg );
		 
		 imagecopyresampled( $tmp_img, $img, 0, 0, $srcx, $srcy, $new_width, $new_height, $width, $height);
		 
		 
		 
		 imagepng( $tmp_img, "{$thumbpath}{$imagename}", 0);
		 
		 $img = imagecreatefrompng($path . $imagename); 
		 imagepng($img, $path . $imagename, $quality); //75 quality setting 
		 imagedestroy($img);
	}
	
	/*
	 * reArrayFiles: Reordena el $_FILES que vienen de input multiple
	 */
	public function reArrayFiles(&$file_post) 
	{
	    $file_ary = array();
	    $file_count = count($file_post['name']);
	    $file_keys = array_keys($file_post);
	
	    for ($i=0; $i<$file_count; $i++) {
	        foreach ($file_keys as $key) {
	            $file_ary[$i][$key] = $file_post[$key][$i];
	        }
	    }
	
	    return $file_ary;
	}	
	
	/*
	 * fileUpload: mueve el archivo a la carpeta especificada.
	 */
	public function fileUpload($file,$url,$orderid)
	{
		$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
		if ($file["error"] > 0)
    		{
    			return 0;
    		}
  		else
    		{
                    if(move_uploaded_file($file["tmp_name"],$url . $orderid . '.' . $ext ))
                    {
                        return 1;
                    }
                    else
                    {
                        return 0;
                    }
		}
  	}
		
	
}


?>