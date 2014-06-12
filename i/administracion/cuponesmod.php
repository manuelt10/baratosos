<?php 
$query = $con->prepare("SELECT * FROM `cupon_galeria` where idcupon is null");
		 $query->execute();
$imgCupones = $query->fetchAll(PDO::FETCH_OBJ);
foreach($imgCupones as $iC)
{
	unlink('images/cupon/' . $iC->imagen );
	unlink('images/cupon/thumb150/' . $iC->imagen );
	unlink('images/cupon/thumb400/' . $iC->imagen );
}
$query = $con->prepare("DELETE FROM `cupon_galeria` where idcupon is null");
		 $query->execute();
require_once('phpfn/mysqlManager.php');
$db = new mysqlManager();
$cupon = $db->selectRecord('cupon', NULL, Array('idcupon' => $_GET["id"]));
$db->updateRecord('cupon_galeria',Array('eliminar' => '0'), Array('idcupon' => $_GET["id"]));
?>

<script src="/simpleckeditor/ckeditor.js"></script>
<script src="/Scripts/jquery.form.img.js"></script>

<div class="topBar">
	<h2 class="settHead">Modificar cupón</h2>
</div>
<div class="settWrap updateItemsWrap">
	<div class="couponForms itmFormSec" >
    	<legend class="settLegend">Detalles del cupón</legend>
		<form id="cuponForm" method="post" action="/administracion/cup_func/mod_cupon.php">
			<input type="hidden" name="idcupon" value="<?php echo $_GET["id"] ?>">
			<label class="settingLbl">Título:</label>
			<br />
			<input type="text" name="titulo" class="txtField itmTxtFld largerFld" value="<?php echo $cupon->data[0]->titulo ?>" placeholder="Nombre del artículo o servicio">
			<br />
			<br />
			<div class="fieldGroup larger">
				<label class="settingLbl">Código de tienda:</label>
				<br />
				<input type="text" class="cuponUser txtField itmTxtFld" name="idusuario" value="<?php echo $cupon->data[0]->idusuario ?>">
				
				<div class="previewUser itemPreview">
					<?php 
					$usr = $db->selectRecord('usuario', NULL, Array('idusuario' => $cupon->data[0]->idusuario));
					if($usr->data[0]->idtipousuario == 2)
					{
					
						?>
						<span class="itmImageMask itmListMask usrImageMask">
							<?php 
							if(!empty($usr->data[0]->imagen))
							{
							?>
								<img class="userImage itmListImg itmImage" src="/images/profile/cr/<?php echo $usr->data[0]->imagen ?>">
							<?php
							}
							else
							{
							?>
								<img class="userImage itmListImg itmImage" src="/images/resources/storePNG-100.png">
							<?php
							}
							?>
						</span>
						<div class="itmListDescrWrap">
							<span class="storeName"><?php echo $usr->data[0]->nombretienda ?></span><br>
							<span class="eMail"><?php echo $usr->data[0]->correo ?></span><br>
						</div>
	
					<?	
					}
					?>
				</div>
				
			</div>
			
			
			<div class="fieldGroup larger">
				<label class="settingLbl">ID Artículo (Opcional):</label>
				<br />
				<input type="text" class="cuponProduct txtField itmTxtFld" name="idproducto" value="<?php echo $cupon->data[0]->idproducto ?>">
				<div class="previewProduct itemPreview">
					<?php 
					$prod = $db->selectRecord('producto', NULL, Array('idproducto' => $cupon->data[0]->idproducto));
					if(!empty($prod->data[0]))
					{
					?>
						
						<span class="pictureWrapper">
							<?php 
							if(empty($prod->data[0]->imagen))
							{
							?>
								<img src="/images/NoImage.png" alt="No image" title="No image">
							<?php 
							}
							else
							{
							?>
								<img src="/images/productos/thumb200/<?php echo $prod->data[0]->imagen ?>" title="<?php echo $prod->data[0]->nombre; ?>" alt="<?php echo $prod->data[0]->nombre; ?>" >
							<?php 
							}
							?>
						</span>
						<div class="itmListDescrWrap">
							<span><?php echo $prod->data[0]->nombre ?></span>
							<span><?php echo $prod->data[0]->price ?></span>
						</div>
					<?php
					}
					?>
				</div>
			</div>
			
			<br />
			<div class="fieldGroup larger">
				<label class="settingLbl">Precio normal:</label>
				<br />
				<input type="text" name="precionormal" class="txtField itmTxtFld" value="<?php echo $cupon->data[0]->precio_normal ?>" placeholder="Precio original del artículo o servicio">
			</div>
			<div class="fieldGroup larger">	
				<label class="settingLbl">Precio oferta:</label>
				<br />
				<input type="text" name="preciooferta" class="txtField itmTxtFld" value="<?php echo $cupon->data[0]->precio_oferta ?>" placeholder="Precio en oferta del artículo o servicio">
			</div>
			<br />
			<div class="fieldGroup larger">
				<label class="settingLbl">Cant. de cupones:</label>
				<br />
				<input type="text" name="cantidad" class="txtField itmTxtFld" value="<?php echo $cupon->data[0]->cantidad ?>" placeholder="Cant. de cupones a vender">
			</div>	
			<div class="fieldGroup larger">	
				<label class="settingLbl">Límite por usuario:</label>
				<br />
				<input type="text" name="cantidad_compra" class="txtField itmTxtFld" value="<?php echo $cupon->data[0]->cantidad_compra ?>" placeholder="Cant. máxima de cupones por usuario">
			</div>
			<br />
			<div class="fieldGroup larger">
				<label class="settingLbl">Publicar el:</label>
				<br />
				<input type="text" name="dia_publicacion" class="txtField itmTxtFld dateFld" placeholder="dd-mm-aaaa" value="<?php $fecha_pub = date('d-m-Y', strtotime($cupon->data[0]->dia_publicacion )); echo $fecha_pub; ?>">
			</div>
			
			<div class="fieldGroup">
				<label class="settingLbl">Duración:</label>
				<br />
				<label class="settingLbl">Días:</label>
				<input type="text" name="duracion_dias" class="txtField shortField itmTxtFld" value="<?php echo $cupon->data[0]->duracion_dias ?>" placeholder="días" />
				<label class="settingLbl">Horas:</label>
				<input type="text" name="duracion_horas" class="txtField shortField itmTxtFld" value="<?php echo $cupon->data[0]->duracion_horas ?>" placeholder="horas" />
			</div>
			<br />
			
			<div class="couponDescrWrap">
				<label class="settingLbl">Descripción. Describe a detalle de qué se trata el artículo o servicio::</label><textarea name="descripcion"><?php echo $cupon->data[0]->descripcion ?></textarea><br>
				<label class="settingLbl">Condiciones y características para el canje:</label><textarea name="caracteristicas"><?php echo $cupon->data[0]->caracteristicas ?></textarea><br>
				<label class="settingLbl">Ubicación, contacto, y horarios::</label><textarea name="contacto"><?php echo $cupon->data[0]->contacto ?></textarea><br>
			</div>
			<button type="submit" class="couponFinishBtn">Enviar</button>
			<?php 
			$pos = $db->selectRecord('cupon_posicion',NULL,Array('idcupon' => $_GET["id"]));
			foreach($pos->data as $row)
			{
				?>
				<input type='hidden' name='position[]' class='mapPosition' value="<?php echo $row->posicion ?>">
				<?php
			}
			?>
			
		</form>
	</div>
	
	<div class="couponForms itmFormSec" >
		<legend class="settLegend">Imagenes del cupón</legend>
		<form id="cuponGalleryForm" method="post" enctype="multipart/form-data" action="/administracion/cup_func/add_image.php">
			<input type="hidden" name="id" value="<?php echo $_GET["id"] ?>">
			<label class="settingLbl">Añadir imagenes:</label>
			<input type="file" name="image" class="imgupl">
		</form>
		<div class="imagenesCupones">
			<?php 
			$imagenCupon = $db->selectRecord('cupon_galeria',NULL,Array('idcupon' => $_GET["id"]));
			$imgCnt = 0;
			foreach($imagenCupon->data as $row)
			{
				?>
				<div class="couponImagesWrap">
					<input type="radio" name="idCuponImage" class="idCuponImage" id="couponPrincImg<?php echo $imgCnt; ?>" value="<?php echo $row->idcupon_galeria ?>" <?php echo $row->principal == 1 ? "checked" : ""; ?>>
					<label class="settLbl" for="couponPrincImg<?php echo $imgCnt; ?>">Principal</label>
					<br />
					<img class="cuponImage" src="/images/cupon/thumb150/<?php echo $row->imagen ?>">
				</div>
				<?php
				$imgCnt++;
			}
			 
			?>
		</div>
	</div>
	
	
	
	<div class="couponForms itmFormSec">
		<legend class="settLegend">Localización del cupón</legend>
		<label class="settLbl">Da click en las diferentes localizaciones de la tienda promotora.</label>
		<br />
		<button type="button" class="clearMap formActionBtn transTw cancel" onclick="deleteMarkers()">Limpiar mapa</button>
		<div id="map-canvas" class="editCouponMap"></div>
	</div>
	
	
	<div class="ctrlBtnWrap">
	   <a href="<?php echo $_SERVER["HTTP_REFERER"]; ?>" class="formActionBtn back cancel transTw">Cancelar</a>
	   <span class="formActionBtn finish transTw">Guardar</span>
    </div>
	
</div>
	
	<script src="http://tumall.do/Scripts/datepicker/jquery-ui-1.10.4.custom.min.js"></script>

	<script>
		$('.dateFld').datepicker({altFormat: "dd-mm-yy"});

		$.datepicker.setDefaults({
			dateFormat: 'dd-mm-yy'
		});
		
	</script>

	<script>
		$('.finish').click(function(){
			$('.couponFinishBtn').trigger('click');
		})
	</script>

	<script>
	$(document).ready(function() { 
		$('.imgupl').on('change', function()	
		{ 
		   	targetDiv = $('.defCharImgList');
		   
	   		var options = {
		        target: '.imagenesCupones'
		    }; 
		   
		    $("#cuponGalleryForm").ajaxForm(options).submit();
		});
	})
	</script>
	

	<script type="text/javascript">
		$('.cuponProduct').keyup(function(){
			
			var id = $(this).val();
			var idUser = $('.cuponUser').val();
			
			if(!id == ""){
					$.ajax({
						type : "POST",
						url : "/administracion/cup_func/product_preview",
						data : {idproducto : id, idusuario : idUser}
					}).done(function(html)
					{
						$('.previewProduct').html(html);
					})
			}
		})
	</script>
	
	<script type="text/javascript">
		$('.cuponUser').keyup(function(){
			var id = $(this).val();
			$.ajax({
				type : "POST",
				url : "/administracion/cup_func/user_preview",
				data : {idusuario : id}
			}).done(function(html)
			{
				$('.previewUser').html(html);
			})
		})
	</script>
	
	<script type="text/javascript"> 
	    CKEDITOR.replace( 'descripcion', {
	    toolbar: 'Basic'
	    });
	    CKEDITOR.replace( 'caracteristicas', {
	    toolbar: 'Basic'
	    });
	    CKEDITOR.replace( 'contacto', {
	    toolbar: 'Basic'
	    });
	</script>
	
	<script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCFFubWMV4M8wn1zmkZqCPS2OrSponWpbg&sensor=true"> 
    </script>
     <script type="text/javascript">
     var locations = [    	<?php foreach($pos->data as $p)
			{
				$var = $var . '['. substr(substr($p->posicion,0,-1),1) .'],';
			} 
			echo substr($var, 0, -1); ?> 
			];
			
      var map;
      var markers = [];
		function placeMarker(position, map) {
			
		    var  marker = new google.maps.Marker({
		    position: position,
		    map: map,
		    icon : '/images/icono_tumall.png'
		    }); 
		    $('#cuponForm').append("<input type='hidden' name='position[]' class='mapPosition' value='"+ position +"'>");
		    
		    markers.push(marker);
		}
		
		function initialize() {
	        var mapOptions = {
	          center: new google.maps.LatLng(18.466, -69.95),
	          zoom: 10
	        };
	        map = new google.maps.Map(document.getElementById("map-canvas"),
	            mapOptions);
	        
	        for(i = 0; i < locations.length; i++) { 
	    		/*var latlng = new google.maps.LatLng(parseFloat(locations[i][0]), parseFloat(locations[i][1]));
	    		placeMarker(latlng,map);*/
	    		
	    		marker = new google.maps.Marker({
			        position: new google.maps.LatLng(locations[i][0], locations[i][1]),
			        map: map,
			        icon : '/images/icono_tumall.png'
			    });
		     }    
	        
	        google.maps.event.addListener(map, 'click', function(e) {
			    placeMarker(e.latLng, map);
			});
	      }
	      
	      function setAllMap(map) {
		  for (var i = 0; i < markers.length; i++) {
		    markers[i].setMap(map);
			  }
			}
			
		  function clearMarkers() {
			  setAllMap(null);
			}
		  function deleteMarkers() {
			  clearMarkers();
			  markers = [];
			  $('.mapPosition').remove();
			}
			
			google.maps.event.addDomListener(window, 'load', initialize);
    </script>
    <script>
    	$('.imagenesCupones').on('click','.idCuponImage',function(){
    		var id = $(this).val();
    		$.ajax({
    			type : 'POST',
    			url : '/administracion/cup_func/prin_image.php',
    			data : {idGaleria : id, cupon : <?php echo $_GET["id"] ?>}
    		});
    		
    	})
    </script>
    <script>
    	$('.imagenesCupones').on('click','.cuponImage',function(){
    		$(this).parent('div').remove();
    		$.ajax({
    			type : 'POST',
    			url : '/administracion/cup_func/rem_image.php',
    			data : {idGaleria : $(this).siblings('.idCuponImage').val()}
    		})
    	})
    </script>
