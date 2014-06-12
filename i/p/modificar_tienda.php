<?php 
if($usuario->idtipousuario == 3)
{
	header("location: http://tumall.do/administracion/solicitudes ");
}
/*if($usuario->premium)
{
    ?>
    <script src="/ckeditor/ckeditor.js"></script>
    <?php
}
 else {*/
  
//}
$query = $con->prepare('SELECT * FROM `provincia` order by descripcion');
$query->execute();
$provincia = $query->fetchAll(PDO::FETCH_OBJ);

$query = $con->prepare('SELECT * FROM `sector` WHERE `idprovincia` = ? order by descripcion');
$query->bindValue(1, $usuario->idprovincia,PDO::PARAM_INT);
$query->execute();
$sector = $query->fetchAll(PDO::FETCH_OBJ);

$query = $con->prepare('SELECT * FROM `redes_sociales` WHERE `activo` = 1 ORDER BY idredes_sociales ASC');
$query->execute();
$social = $query->fetchAll(PDO::FETCH_OBJ);
?>
<script src='/Scripts/jquery.form.min.js'></script>

<div class="topBar">
	<h2 class="settHead">Información de tu tienda</h2>
</div>
<div class="settWrap editStoreWrap">

	<div class="tabNavWrap transTw">
		<span class="tabItm storeTab info active" data-tab="storeInfoSec">Información</span>
		<span class="tabItm storeTab social" data-tab="storeSocialSec">Redes sociales</span>
		<span class="tabItm storeTab appearance" data-tab="storeAppearanceSec">Apariencia</span>
	</div>
	<form class="storeEditForm tabWrap storeEditSec transTw storeInfoSec" action="/p/store_func/update_store_info.php" method="POST">
			<input type="hidden" value="<?php echo $session["usuario"]; ?>" name="idusuario">
			
			<label class="settingLbl">Nombre de la tienda:</label>
			<br/>
			<input type="text" class="txtField transTw storeName largeField" name="nombreTienda" value="<?php echo $usuario->nombretienda ?>">
			<br />
			
			<label class="settingLbl">Descripción de la tienda:</label>
			<br />
			<textarea class="txtField transTw storeDescr largeField" name="descripcionTienda"><?php echo $usuario->descripcion ?></textarea>
			<br />
			<label class="settingLbl">Moneda por defecto: </label>
			<div class="tipWrap category">
				<p class="tipDescrip popUpMsg transTw">
					Elige la moneda en que realices tus ventas para evitar cambiarla manualmente al agregar artículos.
				</p>
			</div>
			<div class="styledSelect storeCurrencySel">
				<select class="normalSelect" name="moneda">
					<option value="RD" <?php echo $usuario->moneda == 'RD' ? 'selected' : ''; ?>>RD$</option>
					<option value="US" <?php echo $usuario->moneda == 'US' ? 'selected' : ''; ?>>US$</option>
					<option value="EU" <?php echo $usuario->moneda == 'EU' ? 'selected' : ''; ?>>EU$</option>
				</select>
			</div>
			<br />
			<label class="settingLbl">Provincia:</label>
			<br />
			
				<select name="idprovincia">
				<?php 
				foreach($provincia as $pv)
				{
					?>
					<option value="<?php echo $pv->idprovincia ?>" <?php echo ($usuario->idprovincia == $pv->idprovincia) ? "selected" : ""; ?>><?php echo $pv->descripcion ?></option>
					<?php
				}
				?>
				</select>
				<br>
				<?
			/*
			 * <div class="provinciaWrapper categorySel">
			<input type="hidden" value="<?php echo $usuario->idprovincia; ?>" class="idprovincia" name="idprovincia">
			<input type="hidden" value="<?php echo $usuario->idsector; ?>" class="idsector" name="idsector">
			<label class="settingLbl">Provincia:</label>
			<br />
			<div class="provinciaWrapper categorySel">
				<?php 
				foreach($provincia as $pv)
				{
					if($pv->idprovincia <> 1)
					{
					?>
					<div class="provinciaDetail <?php echo ($usuario->idprovincia == $pv->idprovincia) ? "selected" : ""; ?>">
						<input type="hidden" class="provinciaVal" value="<?php echo $pv->idprovincia ?>">
						<span class="listProvincia"><?php echo $pv->descripcion ?></span>
					</div>
					<?
					}
				}
				?>
			</div>
			<div class="sectorWrapper categorySel">
			<?php 
				foreach($sector as $st)
				{
					if($st->idsector <> 1)
					{
					?>
					<div class="sectorDetail <?php echo ($usuario->idsector == $st->idsector) ? "selected" : ""; ?>">
						<input type="hidden" class="sectorVal" value="<?php echo $st->idsector ?>">
						<span class="listSector"><?php echo $st->descripcion ?></span>
					</div>
					<?
					}
				}
				?>
			</div>
			<br />
			 * 
			 */?>
			<label class="settingLbl">Dirección:</label>
			<br />
			<input class="txtField transTw storeAddress largeField" type="text" name="direccion1" value="<?php echo $usuario->direccion1 ?>">
			<br />
			
			<label class="settingLbl">Dirección (cont.):</label>
			<br />
			<input class="txtField transTw storeAddress largeField" type="text" name="direccion2" value="<?php echo $usuario->direccion2 ?>">
			<br />
			
			<div class="fieldWrap">
				<label class="settingLbl">Teléfono:</label>
				<input class="txtField transTw storePhone" type="text" name="telefono1" value="<?php echo $usuario->telefono1 ?>">
			</div>
			
			<div class="fieldWrap">
				<label class="settingLbl">Tel. Adicional:</label>
				<input class="txtField transTw storePhone" type="text" name="telefono2" value="<?php echo $usuario->telefono2 ?>">
			</div>
			
			<div class="ctrlBtnWrap">
				<span class="tabNavBack backBtn formActionBtn back">Atrás</span>
				<button type="submit" class="sendForm formActionBtn transTw">Guardar cambios</button>
			</div>
	</form>
	<form class="storeEditForm tabWrap storeEditSec transTw storeSocialSec hidden" action="/p/store_func/update_store_social.php" method="POST">
		<div>
			
			<?php
			foreach($social as $sc)
			{
				$query = $con->prepare('SELECT * FROM `usuarioredes` WHERE `idredes_sociales` = ? and `idusuario` = ?');
				$query->bindValue(1, $sc->idredes_sociales,PDO::PARAM_INT);
				$query->bindValue(2, $session["usuario"],PDO::PARAM_INT);
				$query->execute(); 
				$usuariosocial = $query->fetch(PDO::FETCH_OBJ);
				?>
				<span class="preURL"><?php echo $sc->preurl ?></span> 
				<input type="text" class="txtField transTw socialField <?php echo $sc->descripcion ?>" name="descripcion[]" value="<?php echo $usuariosocial->nombre_red ?>">
				<br />
				
				<input type="hidden" name="social[]" value="<?php echo $sc->idredes_sociales ?>">
				<?php
			}
			 ?></div>
			 <div class="ctrlBtnWrap">
				<span class="tabNavBack backBtn formActionBtn back">Atrás</span>
				<button type="submit" class="sendForm formActionBtn transTw">Guardar cambios</button>
			</div>
	</form>
	<form class="storeImageForm tabWrap storeEditSec transTw storeAppearanceSec hidden" action="/p/store_func/update_store_images.php" enctype="multipart/form-data" method="POST"> 
		
		
		<div class="pictureUploadWrap">
			<h3>Logo de la tienda</h3>
			<label class="settLbl">El logo se utilizará alrededor de Tu Mall para hacer referencia a tu tienda. Debe tener mínimo <b>150px de largo y 150px de alto</b>.</label>
			<br />
			<input class="fileUpload profileUpld" type="file" name="profilePic">
			<div class="actualImage profPicWrap">
				<?php if(!empty($usuario->imagen))
				{
					?>
					<img src="/images/profile/thumb/<?php echo $usuario->imagen ?>" style="left: -<?php echo $usuario->imagencoordx; ?>px; top: -<?php echo $usuario->imagencoordy; ?>px">
					<?php
				} 
				
				else{
				?>
				
				<img width="150" src="/images/resources/storePNG.png" />
				
				<?php	
				}
				
				?>
			</div>
			<span class="imgTipMsg">(Puedes reposicionar la imagen como desees, arrastrándola con el puntero.)</span>
			
			<input type="hidden" class="imgOrigWidth" name="origProfWidth">
			<input type="hidden" class="coorX" name="coordProfX" value="<?php echo $usuario->imagencoordx ?>">
			<input type="hidden" class="coorY" name="coordProfY" value="<?php echo $usuario->imagencoordy ?>"> 
		</div>
		<div class="pictureUploadWrap">
			<h3>Banner</h3>
			<label class="settLbl">Puedes utilizar el banner como objeto de promoción, información, o decoración en tu tienda. El banner es una imagen estática y debe tener mínimo <b>800px de largo y 150px</b> de alto</label>
			<br />
			<input class="fileUpload bannerUpld" type="file" name="banerPic">
			<div class="actualImage bannerImgWrap">
				<?php if(!empty($usuario->banner1))
				{
					?>
					<img src="/images/banners/thumb/<?php echo $usuario->banner1 ?>" style="left: -<?php echo $usuario->bannercoordx; ?>px; top: -<?php echo $usuario->bannercoordy; ?>px">
					<?php
				}
				
				else{
				?>
				
				<img width="533" src="/images/resources/defaultBanner.png" />
				
				<?php	
				}
				
				?>
			</div>
			
			<span class="imgTipMsg">(Puedes reposicionar la imagen como desees, arrastrándola con el puntero.)</span>
			
			<input type="hidden" class="imgOrigWidth" name="origBannerWidth">
			<input type="hidden" class="coorX" name="coordBannerX" value="<?php echo $usuario->bannercoordx ?>">
			<input type="hidden" class="coorY" name="coordBannerY" value="<?php echo $usuario->bannercoordy ?>"> 
		</div>
		
		<div class="ctrlBtnWrap">
			<span class="tabNavBack backBtn formActionBtn back">Atrás</span>
			<button type="submit" class="sendImageForm sendForm formActionBtn transTw">Guardar cambios</button>
		</div>
	</form>
</div>


<script> //draggable image repositionator (pending trademark)
	var draggy  = $('.profPicWrap img'),
		clickCoordX,
		clickCoordY,
		lastImgPosX,
		lastImgPosY,
		newImgPosX,
		newImgPosY,
		imgWidthDiff,
		imgHeightDiff;

	$('.actualImage').on('mousedown', 'img', function(event){
		draggy = $(this);
		draggy.addClass('draggable');
		imgWidthDiff = (draggy.parent().width() - draggy.width());
		imgHeightDiff = (draggy.parent().height() - draggy.height());
		lastImgPosX = draggy.offset().left - draggy.parent().offset().left;
		lastImgPosY = draggy.offset().top - draggy.parent().offset().top;
		clickCoordX = event.pageX;
		clickCoordY = event.pageY;
		
	})
	
	$('.actualImage').on('dragstart', draggy, function(event){
		event.preventDefault();
	})
	
	$('html').mouseup(function(){ 
		if(draggy.hasClass('draggable')){
			
			draggy.parent().siblings('.coorX').val((-1)*draggy.position().left);
			draggy.parent().siblings('.coorY').val((-1)*draggy.position().top);
			
		}
		
		draggy.removeClass('draggable');
		
	
	})	
	
	$('html').mousemove(function(event){
		
		if(draggy.hasClass('draggable')){
			
			newImgPosX = (lastImgPosX + (event.pageX - clickCoordX));
			newImgPosY = (lastImgPosY + (event.pageY - clickCoordY));

			if(newImgPosX > 0 && newImgPosY > 0){
				$('.draggable').css({
					left: 0,
					top: 0
				});
				
			}
			
			else if(newImgPosX < imgWidthDiff && newImgPosY > 0){
				$('.draggable').css({
					left: imgWidthDiff,
					top: 0
				});
				
			}
			
			else if(newImgPosX > 0 && newImgPosY < imgHeightDiff){
				$('.draggable').css({
					left: 0,
					top: imgHeightDiff
				});
				
			}
			
			else if(newImgPosX < imgWidthDiff && newImgPosY < imgHeightDiff){
				$('.draggable').css({
					left: imgWidthDiff,
					top: imgHeightDiff
				});
				
			}
			
			else if(newImgPosX > 0){
				$('.draggable').css({
					left: 0,
					top: newImgPosY
				});
				
			}
			
			else if(newImgPosX < imgWidthDiff){
				$('.draggable').css({
					left: imgWidthDiff,
					top: newImgPosY
				});
				
			}
			
			else if(newImgPosY > 0){
				$('.draggable').css({
					left: newImgPosX,
					top: 0
				});
				
			}
			
			else if(newImgPosY < imgHeightDiff){
				$('.draggable').css({
					left: newImgPosX,
					top: imgHeightDiff
				});
				
			}			
			
			else{
				$('.draggable').css({
					left: newImgPosX,
					top: newImgPosY
				});
			}		
		
		}
		
	});
	
</script>

<script>
	//shows corresponding form on click/tap
	$('.tabItm').click(function(){
		
		var tab = $(this).data('tab'),
			formHeight = $('.'+tab).height();
			
		$('.settWrap').css('min-height', formHeight+75);
		
		$('.tabItm').removeClass('active');
		$(this).addClass('active');
		
		
		$('.tabWrap').addClass('hidden').removeClass('shown');
		$('.'+tab).removeClass('hidden').addClass('shown');

		
		//mobile specific events
		//separated from prev func to avoid confusion
		$(this).parent().addClass('hidden');
		$('.tabWrap').removeClass('shown');
		$('.'+tab).addClass('shown');
	});
	
	$('.backBtn').click(function(){
		$('.tabWrap').removeClass('shown');
		$('.tabNavWrap').removeClass('hidden');
	});
</script>

<script> //displays thumb for selected imgs and sets for upload 
    $(".fileUpload").change(showPreviewImage_click);

	function showPreviewImage_click(e) {
	    var $input = $(this);
	    var inputFiles = this.files;
	    if(inputFiles == undefined || inputFiles.length == 0) return;
	    var inputFile = inputFiles[0];
	
	    var reader = new FileReader();
	    reader.onload = function(event) {
	    	/* $input.siblings('.actualImage img').remove(); */
	    	
	    	
	    	$input.siblings('.actualImage').html('<img id="newImage" src="' + event.target.result + '">');
	    	var newImg = $input.siblings('.actualImage').children('img');
	    	
	    	
			var img = new Image();
	    	img.onload = function(){
	    		
		    	if($input.hasClass('profileUpld')){
				    if(this.width > this.height){
				    	
				    	newImg.css({
					    	height: '100%'
				    	})
			    	}
			    	else{
			    		
				    	newImg.css({
					    	width: '100%'
				    	})
			    	}
		    	}
		    	
		    	else{
			    	if((this.width/this.height) >= (5.33)){
				    	newImg.css({
					    	height: '100%'
				    	})
			    	}
			    	else{
				    	newImg.css({
					    	width: '100%'
				    	})
			    	}
		    	}
		    	
		    	$input.siblings('.imgOrigWidth').val(newImg.width());
		    	$input.siblings('.coorX').val(0);
		    	$input.siblings('.coorY').val(0);
	    	
	    	}

	    	
	    	img.src = event.target.result;
	    	
	    		    	
	    	
	    };
	    
		
			    
	    reader.onerror = function(event) {
	        console.log("ERROR: " + event.target.error.code);
	    };
	    reader.readAsDataURL(inputFile);
	}
</script>

<script> //submits store changes and displays message if successfull
$('.storeEditForm').submit(function(event) { 
   
   function addHidden(){
	   $('.successMsg').addClass('hidden');
   }
	  
   function remHidden(){
	   $('.successMsg').removeClass('hidden');
	   
	   setTimeout(addHidden, 5000);
   }

   function saveSuccess(){
	   $('.successMsg').remove();
	   $('.settWrap').append('<span class="successMsg popUpMsg hidden transTw">Los cambios han sido guardados</span>');
	   
	  setTimeout(remHidden, 1);
   }
   
   var options = {
   		success: saveSuccess
   		};
   
    $(this).ajaxSubmit(options); 
  
    return false; 
});
</script>


<script> //displays cities/province list on click
	$('.listProvincia').click(function()
	{
		var id = $(this).siblings('.provinciaVal').val();
		$('.idprovincia').val(id);
		$('.idsector').val(1);
		$.ajax({
			type: "POST",
			url: "/p/store_func/show_sector.php",
			data: {idprovincia : id}
		}).done(function(html)
		{
			$('.sectorWrapper').html(html);
		});
	})
	
	$('.sectorWrapper').on('click', '.listSector', function()
	{
		var id = $(this).siblings('.sectorVal').val();
		$('.idsector').val(id);
	})
</script>