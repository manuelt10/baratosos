<?php 
if($usuario->idtipousuario == 3)
{
	header("location: http://tumall.doadministracion/solicitudes ");
}
$query = $con->prepare('SELECT * FROM `categoria1` order by descripcion ');
$query->execute();
$categoria = $query->fetchAll(PDO::FETCH_OBJ);
/*
if(empty($session["error"]))
{
		$query = $con->prepare('
		SELECT ` idimg_temp_caractprod`, `imagen`, `idusuario`, `idtemp_caracteristicas` 
		FROM `img_temp_caractprod` 
		WHERE idtemp_caracteristicas in 
		( select idtemp_caracteristicas from temp_caracteristicas where idusuario = ?)');
	    $query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
	    $query->execute();
		$img_caract = $query->fetchAll(PDO::FETCH_OBJ);
		foreach($img_caract as $iC)
		{
			unlink('../../images/c_prod/' . $iC->imagen);
			unlink('../../images/c_prod/thumb50/' . $iC->imagen);
			unlink('../../images/c_prod/thumb150/' . $iC->imagen);
			unlink('../../images/c_prod/thumb200/' . $iC->imagen);
			unlink('../../images/c_prod/thumb400/' . $iC->imagen);
		}
		$query = $con->prepare('
		DELETE
		FROM `img_temp_caractprod` 
		WHERE idtemp_caracteristicas in 
		( select idtemp_caracteristicas from temp_caracteristicas where idusuario = ?)');
	    $query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
	    $query->execute();
		
		$query = $con->prepare('
		DELETE
		FROM `temp_caracteristicas` 
		WHERE idusuario = ?');
	    $query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
	    $query->execute();
	    }*/
?>
<?php 
if($usuario->premium)
{
    ?>
    <script src="ckeditor/ckeditor.js"></script>
    <?php
}
 else {
    ?>
    <script src="simpleckeditor/ckeditor.js"></script>
    <?php
}
?>
    <script src="Scripts/jquery.form.img.js"></script>
	
	
	
	<div class="topBar">
		<!-- <a href="productos/lista" class="formActionBtn">Cancelar</a> -->
		<h2 class="settHead">Publicar artículos</h2>
	</div>
	<div class="settWrap addItemsWrap">
    <form method="post" action="p/p_f/i_p1.php" class="itmDetailForm" >
            
            <div class="transType">
                <input type="radio" name="tipoTransaccion" value="1" checked>
                <label class="settingLbl itmsLbl">Venta</label>
            </div>
            
            <div class="categoryWrap itmFormSec fluidTransTw 1"  data-id="1">
	    		<legend class="settLegend"><span class="lgndNo">1</span> Nombra tu artículo y elige una categoría</legend>
	    		
                <label class="settingLbl itmsLbl">Nombre del artículo: </label>
                <?php echo $session["error"] === 4 ? "<span class='error-label'>Ponle un nombre a tu artículo</span>" : ""; ?> 
                <br />
                <input type="text" name="nombreProducto" class="txtField itmTxtFld transTw nameFld" placeholder="Pon un nombre que describa a tu artículo">
                
                <br />
                <label class="settingLbl itmsLbl">Categoría: </label>
                <div class="tipWrap category">
					<p class="tipDescrip popUpMsg transTw">
						Selecciona la categoría que mejor califique a tu artículo.
					</p>
				</div>
				<?php 
		            echo $session["error"] === 1 ? "<span class='error-label'>Selecciona una categoría</span>" : "";
		            echo $session["error"] === 2 ? "<span class='error-label'>Selecciona una sub-categoría</span>" : "";
		            echo $session["error"] === 3 ? "<span class='error-label'>Selecciona una sub-categoría</span>" : "";
	            ?>
                <br />
	            <select class="categoria1 categorySel catInit" size="16" name="categoria1">
                    <?php 
                    foreach($categoria as $ct)
                    {
                            ?>
                            <option value="<?php echo $ct->idcategoria1 ?>"><?php echo $ct->descripcion ?></option>
                            <?php
                    }
                    ?>
	            </select>
	            <select class="categoria2 categorySel catSub" size="16" name="categoria2"></select>
	            <select class="categoria3 categorySel catSub2" size="16" name="categoria3"></select>				
				
                <div class="ctrlBtnWrap">
                	<span class="formActionBtn next trans first">Descripción
                		<span class="error-popup popUpMsg transOn"></span>
                	
                	</span>
                </div>
            </div>

            <div class="itmDescr itmFormSec fluidTransTw 2 hidden" data-id="2">
            	<legend class="settLegend"><span class="lgndNo">2</span> Detalles de tu artículo</legend>

                <div class="fieldGroup origPrice">
	                <label class="settingLbl itmsLbl">Precio: </label>
	                <?php	echo $session["error"] === 6 ? "<span class='error-label'>Éste campo no tiene un valor válido </span>" : "";
	                		echo $session["error"] === 7 ? "<span class='error-label'>Debes ponerle precio a tu artículo</span>" : ""; 
		                	    
	                ?>
	                <br />
	                <input type="text" name="precioProducto" class="txtField itmTxtFld transTw priceFld" placeholder="e.j.: 1,000.00"/>
					<div class="styledSelect currency">
			        	<select class="normalSelect" name="moneda">
				        	<option value="RD" <?php echo $usuario->moneda == 'RD' ? 'selected' : ''; ?>>RD$</option>
							<option value="US" <?php echo $usuario->moneda == 'US' ? 'selected' : ''; ?>>US$</option>
							<option value="EU" <?php echo $usuario->moneda == 'EU' ? 'selected' : ''; ?>>EU€</option>
			        	</select>
		        	</div>
                </div>
	        	
	        	<div class="fieldGroup salePrice">
	                <label class="settingLbl">En oferta? </label><input class="salePriceCheck" type="checkbox" name="enOferta" value="1">
	                <br />
	                <input type="text" name="precioOferta" class="txtField itmTxtFld transTw salePriceFld" placeholder="Precio de oferta"/>
	        	</div>
                <div class="itmDescrWrap">
                    <label class="settingLbl itmsLbl">Descripción del artículo: </label>
	                <div class="tipWrap category">
						<p class="tipDescrip popUpMsg transTw">
							Editor full HTML para dar mejor estilo a tus descripciones. Necesitas ayuda? <a href="#">Click aquí</a>.
						</p>
					</div>
                    <?php echo $session["error"] === 5 ? "<span class='error-label'>Escribe una descripción de tu artículo</span>" : ""; ?>
                    <br />
                    <textarea name="descripcionProducto" class="itmTxtArea" id="itmTxtFld"></textarea>
                </div>
                <label class="settingLbl itmsLbl">Palabras clave: </label>
                <br />
                <input type="text" name="palabrasClave" class="txtField itmTxtFld transTw keyFld" placeholder="e.j.: smartphone, celular, negro, aluminio"/>
                
                <?php 
                	$errorCodes = Array(5, 6, 7, 8);
					
					echo in_array($session["error"], $errorCodes) ? 
					"
					<script>
	                	$('.itmFormSec.1').addClass('hidden top');
						$('.itmFormSec.2').removeClass('hidden');
	                </script>" : "";
                 ?>
 
                <div class="ctrlBtnWrap">
	                <span class="formActionBtn back transTw">Categorías</span>
	                <span class="formActionBtn next transTw second">Imágenes
	                	<span class="error-popup popUpMsg  transOn"></span>
	                </span>
                </div>
            </div>
            
			<input type="hidden" name="caract_default" class="caractDefault" value="predeterminada">
            <!-- <button type="submit" class="saveBtn itmSave">Guardar artículo</button> -->
    </form>
	    <div class="addImgWrap itmFormSec fluidTransTw 3 hidden" data-id="3">
	    	<legend class="settLegend"><span class="lgndNo">3</span> Agrega imagenes de tu artículo</legend>
		    <label class="settLbl">Las dimensiones mínimas para las imágenes de tu artículo deben ser <b>400px x 400px</b>.</label>
		    <div class="snglCharacWrap characDetail">
		    	
			    <form id="imageform" method="post" enctype="multipart/form-data" action='p/p_f/u_p_img.php'>
			    	
			    	
			    	<input type="text" class="caract_default txtField itmTxtFld transTw"  placeholder="Nombre característica">
			    	<br />
				    <div class="upldInptMask">
				        <input type="file" name="file" class="imgupl" >
			    	</div>
			    	
			    	<div class="imagenes imgListWrap defCharImgList imgCaract">
		            <?php 
		            $query2 = $con->prepare('SELECT `img_temporales`, `idusuario` FROM `img_temporales` WHERE idusuario = ?');
		            $query2->bindValue(1,$session["usuario"], PDO::PARAM_INT);
		            $query2->execute();
		            $imagenes = $query2->fetchAll(PDO::FETCH_OBJ);
		
		            foreach($imagenes as $imgs)
		            {
		            
						list($width, $height) = getimagesize("images/productos/thumb150/".$imgs->img_temporales."");
						
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
						$pos = ($modVal - $baseDimm)/2
		                ?>
		                <div  class="uplddImgWrap">
		                	<input type="hidden" class="imageProductVal" value="<?php echo $imgs->img_temporales ?>">
		                    <span class="itmImageMask imageDefaultMask">
		                    	<img class="imageProduct imageDefault itmImage" <?php if($height > $width){  ?> width="<?php echo $baseDimm; ?>" height="<?php echo $modVal; ?>" style="top: -<?php echo $pos; ?>px;"  <?php }  else{ ?> height="<?php echo $baseDimm; ?>" width="<?php echo $modVal; ?>" style="left: -<?php echo $pos; ?>px;" <?php } ?> src="images/productos/thumb150/<?php echo $imgs->img_temporales ?>">
		                    </span>
		                </div>
		                <?php
		            }
		            ?>
		           
			        </div>
			    	
			    </form>
		        
			</div>
		    <div class="characsWrap">

					   
			    <div class="addCharWrap">
			    	<button type="button" class="addCharacBtn formActionBtn transTw">Añadir característica</button>
				    <div class="tipWrap category">
						<p class="tipDescrip popUpMsg bigTxt transTw">
							Para vender varias versiones del mismo artículo, puedes agregar otra "característica". Una característica puede ser un color, capacidad, tamaño, etc. Y permite agregar imágenes específicas para la misma.
						</p>
					</div>
			    </div>

				<?php 
		    	
	    		$query3 = $con->prepare('SELECT * FROM `temp_caracteristicas` WHERE `idusuario` = ?');
	            $query3->bindValue(1,$session["usuario"], PDO::PARAM_INT);
	            $query3->execute();
	            $caracteristica_temp = $query3->fetchAll(PDO::FETCH_OBJ);
				$n = 0;
				foreach($caracteristica_temp as $cT)
				{
					?>
					<form class="caracteristica xtraCharacDetail characDetail transTw" action="p/p_f/upl_caract.php">
				    	<input type="text" class="txtField itmTxtFld transTw charactTxt" name="caracteristica[]" placeholder="Nombre característica" value="<?php echo $cT->caracteristica ?>">
				    	<input type="hidden" name="id" class ="idCar" value="<?php echo $cT->idtemp_caracteristicas ?>" >
				    	<button class="removeCharacBtn" type="button"></button>
				    	<br />
				    	
				    	<div class="upldInptMask">
				    		<input type="file" name="file" class="imgcarupl" multiple>
				    	</div>
				    	<script>
				    		
				    	</script>
				    	<input type="hidden" class="cont" value="<?php echo $n ?>">
				    	
				    	<div class="imgCaract imgListWrap imgChWrap<?php echo $n ?>" data-id="imgChWrap<?php echo $n ?>">
				    		<?php
								$query3 = $con->prepare('SELECT * FROM `img_temp_caractprod` WHERE `idtemp_caracteristicas` = ?');
					            $query3->bindValue(1,$cT->idtemp_caracteristicas, PDO::PARAM_INT);
					            $query3->execute();
					            $img_caract = $query3->fetchAll(PDO::FETCH_OBJ);
								foreach($img_caract as $iC)
								{
									list($width, $height) = getimagesize("images/c_prod/thumb150/".$iC->imagen."");
									
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
										<input type="hidden" class="imageProductVal" value="<?php echo $iC->imagen ?>">
										<span class="itmImageMask">
											<img class="imageCaractProducto itmImage" <?php if($height > $width){  ?> width="<?php echo $baseDimm; ?>" height="<?php echo $modVal; ?>" style="top: -<?php echo $pos; ?>px;"  <?php }  else{ ?> height="<?php echo $baseDimm; ?>" width="<?php echo $modVal; ?>" style="left: -<?php echo $pos; ?>px;" <?php } ?> src="images/c_prod/thumb150/<?php echo $iC->imagen ?>">
										</span>
									</div>
									<?php
								}
							?>
				    	</div>
 
				    	
				    	
				   	</form>
					
					<?php
					$n++;
				}
		    	?>
			    
		    </div> 
		    
		    
		    <div class="ctrlBtnWrap">
			   <span class="formActionBtn back transTw">Descripción</span>
			   <span class="formActionBtn finish transTw">Finalizar</span>
			   
		    </div>
	    </div>
	</div>
    
<script type="application/javascript"> /* checks if price is numeric */
       
       $('.priceFld, .salePriceFld').keypress(function(evt){
	       
	      var charCode = (evt.which) ? evt.which : event.keyCode;
          if (charCode != 46 && charCode > 31
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       });
       
       //checks if sale price is enabled
       
       if(!$('.salePriceCheck').prop('checked')){
	       $('.salePriceFld').attr('readonly', true).addClass('disabled');
       }
       
       $('.salePriceCheck').on('click', function(){
       
	       if(!$(this).prop('checked')){
		       $('.salePriceFld').attr('readonly', true).addClass('disabled');
	       }
	       
	       else{
		       $('.salePriceFld').attr('readonly', false).removeClass('disabled');
	       }
       
       })
       

       
</script>

<script> /*Update temporal characteristic description*/
	$('.characsWrap').on('keyup', '.charactTxt', function(typed)
	{
		
		
		var idTemp = $(this).siblings('.idCar').val();
		
		var desc = $(this).val();
		/*
console.log(idTemp);
		console.log(desc);
*/
		$.ajax({
			type : "POST",
			url : "p/p_f/upd_caract_temp.php",
			data: {idcaracteristica : idTemp, temp_descripcion : desc}
		})
	})
</script>

<script>/* moves betweent pages in the item form */

$('.formActionBtn').click(function(event){
	
	var secID 	= $(this).parent('.ctrlBtnWrap').parent('.itmFormSec').data('id'),
		clkdPar = $(this).parent('.ctrlBtnWrap').parent('.itmFormSec'),
		formHeight;

	function formNavNxtFN(){
			secID+=1;
			$(clkdPar).addClass('hidden top');
			$('.itmFormSec.'+secID).removeClass('hidden');
			if($(window).width() <= 600){
				formHeight = $('.itmFormSec.'+secID).height();
				$('.settWrap').css('min-height', formHeight+80);
			}
			
	}
	
	if($(this).hasClass('back')){
			secID-=1;
			$(clkdPar).addClass('hidden');
			$('.itmFormSec.'+secID).removeClass('hidden top');
			if($(window).width() <= 600){			
				formHeight = $('.itmFormSec.'+secID).height();
				$('.settWrap').css('min-height', formHeight+80);
			}	
	}


	else if($(this).hasClass('first')){
		
		$('.txtField').removeClass('error');
		
		var noCatSelected = 0,
			errCat;
		
		
		$('.categoryWrap').find('.categorySel').each(function(e){
			
			var catChild = $(this).children().length;
			
			e++;
			
			if(catChild > 0){
				if($(this).find(':selected').text() == ""){
					noCatSelected++;
					errCat = e;
				}
			}
			
		});
		
		if($('.nameFld').val().trim() == ""){
			$(this).children('.error-popup').text('Ponle nombre a tu artículo!').addClass('visible');
			
			$('.nameFld').addClass('error').focus();
			
		}
		
		else if(noCatSelected > 0){
			$(this).children('.error-popup').text('Elige una categoría o subcategoría.').addClass('visible');
			
			$('.categoria'+errCat).addClass('error');
			
		}
		
		else{
			formNavNxtFN();
		}
	}
	
	else if($(this).hasClass('second')){
		
		var priceVal = $('.priceFld').val().trim();
		
		if(priceVal == "" || parseInt(priceVal) == 0){
			$(this).children('.error-popup').text('Ponle precio al artículo.').addClass('visible');
			
			$('.priceFld').addClass('error').focus();
		}
		
		else{
			formNavNxtFN();
		}
		
	}
	
	event.stopPropagation();
	
});

$('html').click(function(event){
	$('.error-popup').removeClass('visible');
	$('.error').removeClass('error');
});
	
</script>
    
<script>
	$('.caract_default').on('keyup',function()
	{
		$('.caractDefault').val($(this).val());
	})
	
	
	/* submits form on finish button click */
	$('.finish').click(function(){
		$('.itmDetailForm').submit();
	});
	
</script>

<script type="text/javascript"> /* removes images from item on click */
     $('.addImgWrap').on('click','.itmImageMask',function(){
		
		var img = $(this).siblings('.imageProductVal').val();
		
		if($(this).hasClass('imageDefaultMask')){
			var funcURL = ("p/p_f/borrar_img_producto_t.php");
		}
		
		else{
			var funcURL = ("p/p_f/borrar_img_caracteristica_t.php");
		}
		
		$.ajax({
			type: "POST",
			url: funcURL,
			data: {imageValue : img}
		});
		
		$(this).parent('.uplddImgWrap').remove();
		
      })
</script>

<script type="text/javascript"> /* adds images to the respective item image wrap defCharImgList/imgChWrap */

var targetDiv;

	function appendLoadFN(){
		targetDiv.parent().append('<span class="loadBlock"><img src="images/LoadAnim.gif" /></span>');
	}
	
	function removeLoadFN(){
		$(this).siblings('.loadBlock').last().remove();
	}

	$('.characsWrap').on('change', '.imgcarupl', function()	
	{
	    
	    var cnt = $(this).parent().siblings('.imgListWrap').children('.uplddImgWrap').length;
	    
	    var dataID = $(this).parent('.upldInptMask').siblings('.imgCaract').data('id');
	    
	    targetDiv = $('.'+dataID+'');
	    
		var options = {
		    beforeSubmit: appendLoadFN,
	        target: targetDiv,
	        success: removeLoadFN
	    };
	    if(cnt < 3)
	    {
	    	$(this).parent('.upldInptMask').parent('.caracteristica').ajaxForm(options).submit();
		}
		else
		{
			return false;
			alert('Sólo 3 imágenes por grupo!');
		}
	});
	   
	$('.imgupl').on('change', function()	
	{ 
	   	targetDiv = $('.defCharImgList');
	   
   		var options = {
		    beforeSubmit: appendLoadFN,
	        target: targetDiv,
	        success: removeLoadFN
	    };
	   
	    $("#imageform").ajaxForm(options).submit();
	});
	
</script>    
    
    
<script type="text/javascript"> /* adds new item charac wrapp */

 	function remHidden(){
	 	$('.characDetail').removeClass('hidden');
 	}

    $('.addCharacBtn').click(function()
    {
    	var charactCnt = $('.characsWrap').children('.characDetail').length;
    	
    	if(charactCnt < 4){
	    	var caract = $('.addCharWrap').siblings('.caracteristica').val(); /* what is this for? */
	    	
	        	$.ajax({
	        		type: "POST",
	        		url: "p/p_f/a_car_tmp.php",
	        		data : {caracteristica : caract, cont : charactCnt} 
	        	}).done(function(html)
	        	{
	        		$('.characsWrap').append(html);
	        		
	        		setTimeout(remHidden, 10);
	        	})
    	}
    	
    }); 
   
</script>

	
<script type="text/javascript"> /* removes characWrap */

	function remmElem(){
		currElem.remove();
	}
	
     $('.characsWrap').on('click', '.removeCharacBtn', function(){
		var id = $(this).siblings('.idCar').val()
			currElem = $(this).parent();

			currElem.addClass('hidden deleted');
			currElem.children().addClass('deleted');
			
		$.ajax({
			type: "POST",
			url: "p/p_f/borrar_caracteristica_t.php",
			data: {idCar : id}
		})
		
		setTimeout(remmElem, 300);
		
      })
</script>

<script type="text/javascript"> 
    CKEDITOR.replace( 'descripcionProducto', {
    toolbar: 'Basic'
    });
</script>

<script type="text/javascript">
	$('.categoria1').change(function(){
		var valor = $(this).val();
		$('.categoria2').html();
		$.ajax({
			type: "POST",
			url: "p/p_f/categoria2.php",
			data: {categoria1 : valor}
		}).done(function(html){
			$('.categoria2').html(html);
		});
		
	});
</script>
<script type="text/javascript">
	$('.categoria2').change(function(){
		var valor = $(this).val();
		$('.categoria3').html();
		$.ajax({
			type: "POST",
			url: "p/p_f/categoria3.php",
			data: {categoria2 : valor}
		}).done(function(html){
			$('.categoria3').html(html);
		});
		
	});
</script>




