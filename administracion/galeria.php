<?php 
require_once('phpfn/mysqlManager.php');
$db = new mysqlManager();
?>
<div>
	
	<script src="Scripts/jquery.form.img.js"></script>
	
	<span>
		Las imagenes deben ser exactamente 700 pixeles de ancho y 300 pixeles de alto (700x300).
	</span>
	<form method="post" id="GalleryForm" action="administracion/gal_func/add_image.php" enctype="multipart/form-data">
		<label>Agregar imagen</label>
		<input type="file" name="image" class="imgupl">
	</form>
	<div class="imagenesGaleria">
		<?php 
		#$row = $db->insertRecord('galeria',$records);
		$imagen = $db->selectRecord('galeria');
		foreach($imagen->data as $row)
		{
			?>
			<div class="imageGallery">
				<img class="galleryImage" src="images/galeria/thumb150/<?php echo $row->imagen ?>">
				<label>Visible:</label> <input type="checkbox"  class="idgaleria" value="<?php echo $row->idgaleria ?>" <?php echo $row->activo == 1 ? "checked" : ""; ?>>
				<input type="text" class="url" value="<?php echo $row->url ?>">
				<input type="number" name="orden" class="orden" min="0" max="<?php echo $imagen->rowcount; ?>" value="<?php echo $row->orden ?>">
				<button type="button" class="removeGal">Borrar</button>
				
			</div>
			<?php
			$imgCnt++;
		}
				?>
	</div>
</div>
<script>
	$('.orden').click(function()
	{
		var id = $(this).siblings('.idgaleria').val();
		var ord = $(this).val();
		$.ajax({
			type : 'POST',
			url : 'administracion/gal_func/mod_ord.php',
			data : {idGaleria : id, orden : ord}
		});
	})
</script>
<script>
	$('.orden').keyup(function()
	{
		var id = $(this).siblings('.idgaleria').val();
		var ord = $(this).val();
		$.ajax({
			type : 'POST',
			url : 'administracion/gal_func/mod_ord.php',
			data : {idGaleria : id, orden : ord}
		});
	})
</script>
<script>
	$('.imagenesGaleria').on('click','.removeGal',function(){
		var id = $(this).siblings('.idgaleria').val();
		$(this).parents('.imageGallery').remove();
		$.ajax({
			type : 'POST',
			url : 'administracion/gal_func/remove_image.php',
			data : {idGaleria : id}
		});
	})
</script>
 
<script>
	$('.imagenesGaleria').on('keyup','.url',function(){
		var id = $(this).siblings('.idgaleria').val();
		var ur = $(this).val();
		$.ajax({
			type : 'POST',
			url : 'administracion/gal_func/mod_url.php',
			data : {idGaleria : id, url : ur}
		});
	})
</script>

<script>
$(document).ready(function() { 
	$('.imgupl').on('change', function()	
	{ 
	   	targetDiv = $('.defCharImgList');
	   
   		var options = {
	        target: '.imagenesGaleria'
	    }; 
	   
	    $("#GalleryForm").ajaxForm(options).submit();
	});
})
</script>

<script>
	$('.imagenesGaleria').on('click','.idgaleria',function(){
		var id = $(this).val();
		var ischecked = $(this).is(':checked') ;
		if(ischecked)
		{
			var i = 1;
		}
		else
		{
			var i = 0;
		}
		$.ajax({
			type : 'POST',
			url : 'administracion/gal_func/change_cup_stat.php',
			data : {idGaleria : id, isch : i}
		});
		
	})
</script>

