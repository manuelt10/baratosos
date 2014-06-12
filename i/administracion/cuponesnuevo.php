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
?>

<script src="/simpleckeditor/ckeditor.js"></script>
<script src="/Scripts/jquery.form.img.js"></script>
<div class="topBar">
	<h2 class="settHead">Agregar cupón</h2>
</div>
<div class="settWrap updateItemsWrap">
	
	<div class="couponForms itmFormSec">
		<legend class="settLegend">Detalles del cupón</legend>
		<form id="cuponForm" method="post" action="/administracion/cup_func/add_cupon.php">
			
			<label class="settingLbl">Título:</label>
			<br />
			<input type="text" class="txtField itmTxtFld largerFld" name="titulo" class="txtField itmTxtFld largerFld" placeholder="Nombre del artículo o servicio">
			<br />
			<br />
			
			
			<?php 
				if($usuario->idtipousuario == 2)
				{
					?>
					
					<input class="cuponUser txtField itmTxtFld" type="hidden" name="idusuario" readonly value="<?php echo $usuario->idusuario; ?>" />
					
					<?	
				}
				
				else{
			?>
			
			<div class="fieldGroup larger">
				<label class="settingLbl">Código de tienda:</label>
				<br />
				<input type="text" class="cuponUser txtField itmTxtFld" name="idusuario" />
				<div class="previewUser itemPreview"></div>
			</div>
			
			<?php
			
			}
			
			?>
			
			<div class="fieldGroup larger">
				<label class="settingLbl">ID del artículo (Opcional):</label>
				<br />
				<input type="text" class="cuponProduct txtField itmTxtFld" name="idproducto">
				<div class="previewProduct itemPreview"></div>
			</div>
			<br />
			
			<div class="fieldGroup larger">
				<label class="settingLbl">Precio normal:</label>
				<br />
				<input type="text" class="txtField itmTxtFld" name="precionormal" placeholder="Precio original del artículo o servicio">
			</div>
			
			<div class="fieldGroup larger">
				<label class="settingLbl">Precio oferta:</label>
				<br />
				<input type="text" class="txtField itmTxtFld" name="preciooferta" placeholder="Precio en oferta del artículo o servicio">
			</div>
			
			<div class="fieldGroup larger">
				<label class="settingLbl">Cant. de cupones:</label>
				<br />
				<input type="text" class="txtField itmTxtFld" name="cantidad" placeholder="Cant. de cupones a vender">
			</div>
			
			<div class="fieldGroup larger">
				<label class="settingLbl">Límite por usuario:</label>
				<br />
				<input type="text" class="txtField itmTxtFld" name="cantidad_compra" placeholder="Cant. máxima de cupones por usuario">
			</div>
			
			<div class="fieldGroup larger">
				<label class="settingLbl">Publicar el:</label>
				<br />
				<input type="text" class="txtField itmTxtFld dateFld" name="dia_publicacion" placeholder="dd-mm-aaaa"> 
			</div>
			
			<div class="fieldGroup">
				<label class="settingLbl">Duración:</label>
				<br />
				<label class="settingLbl">Días:</label>
				<input type="text" class="txtField itmTxtFld shortField" name="duracion_dias">
				<label class="settingLbl">Horas:</label>
				<input type="text" class="txtField itmTxtFld shortField" name="duracion_horas">
			</div>
			<br />
			
			<div class="couponDescrWrap">
				<label class="settingLbl">Descripción. Describe a detalle de qué se trata el artículo o servicio:</label>
				<br />
				<textarea name="descripcion"></textarea>
				<br />
				<label class="settingLbl">Condiciones y características para el canje:</label>
				<br />
				<textarea name="caracteristicas"></textarea>
				<br />
				<label class="settingLbl">Ubicación, contacto, y horarios:</label>
				<br />
				<textarea name="contacto"></textarea>
			</div>
			<button type="submit" class="couponFinishBtn">Enviar</button>
		</form>
	</div>
	
	<div class="couponForms itmFormSec">
		<legend class="settLegend">Imágenes del cupón</legend>
		<form id="cuponGalleryForm" method="post" enctype="multipart/form-data" action="/administracion/cup_func/add_image.php">
			<label class="settingLbl">Añadir imagenes</label>
			<br />
			
			<input type="file" name="image" class="imgupl">
		</form>
		<div class="imagenesCupones">
			
		</div>
	</div>
	
	
	<div class="couponForms itmFormSec">
		<legend class="settLegend">Localización del cupón</legend>
		<label class="settLbl">Da click en las diferentes localizaciones de la tienda promotora.</label>
		<br />
		<button type="button" class="clearMap formActionBtn transTw cancel" onclick="deleteMarkers()">Limpiar mapa</button>
	
		<div id="map-canvas"  class="editCouponMap"></div>
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
		$('.imgupl').on('change', function()	
		{ 
		   	targetDiv = $('.defCharImgList');
		   
	   		var options = {
		        target: '.imagenesCupones'
		    }; 
		   
		    $("#cuponGalleryForm").ajaxForm(options).submit();
		});
	</script>
	
	
	<script type="text/javascript">
		$('.cuponProduct').keyup(function(){
			var id = $(this).val();
			var idUser = $('.cuponUser').val();
			$.ajax({
				type : "POST",
				url : "/administracion/cup_func/product_preview",
				data : {idproducto : id, idusuario : idUser}
			}).done(function(html)
			{
				$('.previewProduct').html(html);
			})
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
    		$.ajax({
    			type : 'POST',
    			url : '/administracion/cup_func/prin_image.php',
    			data : {idGaleria : $(this).val()}
    		})
    	})
    </script> 
    <script>
    	$('.imagenesCupones').on('click','.cuponImage',function(){
    		$(this).parent('div').remove();
    		$.ajax({
    			type : 'POST',
    			url : '/administracion/cup_func/rem_image_full.php',
    			data : {idGaleria : $(this).siblings('.idCuponImage').val()}
    		})
    	})
    </script>
    
    
    
    
    