<?php 
if($usuario->idtipousuario == 3 and !empty($_GET["usrid"]))
{
	session_start();
	$_SESSION["usuarioReal"] = $_SESSION["usuario"];
	$_SESSION["usuario"] = $_GET["usrid"];
	session_write_close();
	$session["usuario"] = $_GET["usrid"];
}
$query = $con->prepare('UPDATE `caracteristica_producto` 
set `delete` = 0, `temp_descripcion` = NULL
where idproducto = ? ');
$query->bindValue(1,$_GET["prod_id"], PDO::PARAM_INT);
$query->execute();

$query = $con->prepare('UPDATE `imagenproducto` 
set `delete` = 0
where idproducto = ? ');
$query->bindValue(1,$_GET["prod_id"], PDO::PARAM_INT);
$query->execute();


$query = $con->prepare('UPDATE `imagencaracteristica` 
set `delete` = 0
WHERE `idcaracteristica_producto` 
											IN (SELECT `idcaracteristica_producto` 
												FROM `caracteristica_producto` 
			 									WHERE `idproducto` = ?) ');
$query->bindValue(1,$_GET["prod_id"], PDO::PARAM_INT);
$query->execute();

$query3 = $con->prepare('SELECT * FROM `img_temporales` WHERE `idproducto` = ?');
		            $query3->bindValue(1,$_GET["prod_id"], PDO::PARAM_INT);
		            $query3->execute();
		            $imgBorrar = $query3->fetchAll(PDO::FETCH_OBJ);
					foreach($imgBorrar as $iB)
					{
						unlink('images/productos/' . $iB->imagen);
						unlink('images/productos/thumb50/' . $iB->imagen);
						unlink('images/productos/thumb150/' . $iB->imagen);
						unlink('images/productos/thumb200/' . $iB->imagen);
						unlink('images/productos/thumb400/' . $iB->imagen);
					}
					$query3 = $con->prepare('DELETE FROM `img_temporales` WHERE `idproducto` = ?');
		            $query3->bindValue(1,$_GET["prod_id"], PDO::PARAM_INT);
		            $query3->execute();

$query3 = $con->prepare('SELECT * FROM `caracteristica_producto` 
		 							WHERE `idproducto` = ? and `temp` = 1');
	            $query3->bindValue(1,$_GET["prod_id"], PDO::PARAM_INT);
	            $query3->execute();
	            $caracBorrar = $query3->fetchAll(PDO::FETCH_OBJ);
				foreach($caracBorrar as $cB)
				{
					$query3 = $con->prepare('SELECT * FROM `imagencaracteristica` 
											WHERE `idcaracteristica_producto` = ?');
		            $query3->bindValue(1,(int)$cB->idcaracteristica_producto, PDO::PARAM_INT);
		            $query3->execute();
		            $imgBorrar = $query3->fetchAll(PDO::FETCH_OBJ);
					foreach($imgBorrar as $iB)
					{
						unlink('images/c_prod/' . $iB->imagen);
						unlink('images/c_prod/thumb50/' . $iB->imagen);
						unlink('images/c_prod/thumb150/' . $iB->imagen);
						unlink('images/c_prod/thumb200/' . $iB->imagen);
						unlink('images/c_prod/thumb400/' . $iB->imagen);
					}
					$query3 = $con->prepare('DELETE FROM `imagencaracteristica` 
											WHERE `idcaracteristica_producto` = ?');
		            $query3->bindValue(1,(int)$cB->idcaracteristica_producto, PDO::PARAM_INT);
		            $query3->execute();
				}
				
				$query3 = $con->prepare('SELECT * FROM `imagencaracteristica` 
											WHERE `idcaracteristica_producto` 
											IN (SELECT `idcaracteristica_producto` 
												FROM `caracteristica_producto` 
			 									WHERE `idproducto` = ?)
											AND `temp` = 1
											');
		            $query3->bindValue(1,$_GET["prod_id"], PDO::PARAM_INT);
		            $query3->execute();
		            $imgBorrar = $query3->fetchAll(PDO::FETCH_OBJ);
					foreach($imgBorrar as $iB)
					{
						unlink('images/c_prod/' . $iB->imagen);
						unlink('images/c_prod/thumb50/' . $iB->imagen);
						unlink('images/c_prod/thumb150/' . $iB->imagen);
						unlink('images/c_prod/thumb200/' . $iB->imagen);
						unlink('images/c_prod/thumb400/' . $iB->imagen);
					}
					
					$query3 = $con->prepare('DELETE FROM `imagencaracteristica` WHERE `idcaracteristica_producto` 
											IN (SELECT `idcaracteristica_producto` 
												FROM `caracteristica_producto` 
			 									WHERE `idproducto` = ?)
											AND `temp` = 1');
		            $query3->bindValue(1,$_GET["prod_id"], PDO::PARAM_INT);
		            $query3->execute();
				
				$query3 = $con->prepare('DELETE FROM `caracteristica_producto` 
										WHERE `idproducto` = ? and `temp` = 1');
	            $query3->bindValue(1,$_GET["prod_id"], PDO::PARAM_INT);
	            $query3->execute();
				

if($usuario->premium)
{
    ?>
    <script src="/ckeditor/ckeditor.js"></script>
    <?php
}
 else {
    ?>
    <script src="/simpleckeditor/ckeditor.js"></script>
    <?php
}
?>

<?php 

$query = $con->prepare('SELECT * FROM `producto` where idproducto = ? ');
$query->bindValue(1,$_GET["prod_id"], PDO::PARAM_INT);
$query->execute();
$producto = $query->fetch(PDO::FETCH_OBJ);
/*if product is from user*/
if($producto->idusuario == $session["usuario"])
{
	$query = $con->prepare('SELECT * FROM `categoria1`');
	$query->execute();
	$categoria = $query->fetchAll(PDO::FETCH_OBJ);
	
	$query = $con->prepare('SELECT * FROM `categoria2` where idcategoria1 = ?');
	$query->bindValue(1,$producto->idcategoria1, PDO::PARAM_INT);
	$query->execute();
	$categoria2 = $query->fetchAll(PDO::FETCH_OBJ);
	
	$query = $con->prepare('SELECT * FROM `categoria3` where idcategoria2 = ?');
	$query->bindValue(1,$producto->idcategoria2, PDO::PARAM_INT);
	$query->execute();
	$categoria3 = $query->fetchAll(PDO::FETCH_OBJ);
	?>
	
	<div class="topBar">
		<h2 class="settHead">Modificar artículos</h2>
	</div>
	<div class="settWrap updateItemsWrap">
		
		<form method="post" action="/p/upd_productos/upd_producto.php" class="itmDetailForm">
			<input type="hidden" value="<?php echo $_GET["prod_id"] ?>" name="idproduct"> 
	        
	        <div class="transType">
                <input type="radio" name="tipoTransaccion" value="1" checked>
                <label>Venta</label>
            </div>
	        
	        <div class="categoryWrap itmFormSec 1"  data-id="1">
    			<legend class="settLegend">Nombre del artículo y categoría</legend>
    			
    			<label class="settingLbl itmsLbl">Nombre del artículo: </label>
    			<?php echo $session["error"] === 1 ? "<span class='error-label'>Ponle nombre al artículo</span>" : ""; ?>
    			<br />
    			<input type="text" name="nombreProducto" value="<?php echo $producto->nombre ?>" class="txtField itmTxtFld nameFld" placeholder="e.j.: Smartphone S IV">
    			
    			
    			<br />
    			<label class="settingLbl itmsLbl">Categoría: </label>
				<div class="tipWrap category">
					<p class="tipDescrip popUpMsg transTw">
						Selecciona la categoría que mejor califique a tu artículo.
					</p>
				</div>			
				<?php 
	                echo $session["error"] === 2 ? "<span class='error-label'>Selecciona una categoria</span>" : "";
	                echo $session["error"] === 3 ? "<span class='error-label'>Selecciona una sub-categoria</span>" : "";
	                echo $session["error"] === 4 ? "<span class='error-label'>Selecciona una sub-categoria</span>" : "";
	            ?>
    			
    			<br />
	           	<select class="categoria1 categorySel catInit" size="16" name="categoria1">
	                <?php 
	                foreach($categoria as $ct)
	                {
	                	?>
	                    <option value="<?php echo $ct->idcategoria1 ?>" <?php echo ($ct->idcategoria1 === $producto->idcategoria1) ? "selected" : ""; ?>><?php echo $ct->descripcion ?></option>
	                	<?php
	                }
	                ?>
	          	</select>
	          	<select class="categoria2 categorySel catSub" size="16" name="categoria2">
	          		<?php 
	                foreach($categoria2 as $ct2)
	                {
	                	?>
	                    <option value="<?php echo $ct2->idcategoria2 ?>" <?php echo ($ct2->idcategoria2 === $producto->idcategoria2) ? "selected" : ""; ?>><?php echo $ct2->descripcion ?></option>
	                	<?php
	                }
	                ?>
	          	</select>
	          	<select class="categoria3 categorySel catSub2" size="16" name="categoria3">
	          		<?php 
	                foreach($categoria3 as $ct3)
	                {
	                	?>
	                    <option value="<?php echo $ct3->idcategoria3 ?>" <?php echo ($ct3->idcategoria3 === $producto->idcategoria3) ? "selected" : ""; ?>><?php echo $ct3->descripcion ?></option>
	                	<?php
	                }
	                ?>
				</select>
	            
			
	      	</div>

            <div class="itmDescr itmFormSec 2" data-id="2">
            	<legend class="settLegend">Descripción del artículo</legend>
            	
            	<div class="fieldGroup origPrice">
		        	<label class="settingLbl itmsLbl">Precio: </label>
					<?php 
		        		echo $session["error"] === 5 ? "<span class='error-label'>Debes ponerle precio a tu artículo</span>" : "";
		        		echo $session["error"] === 6 ? "<span class='error-label'>El precio no tiene un valor valido</span>" : ""; 
		        	?>
		        	<br />
		        	<input type="text" name="precioProducto" value="<?php echo $producto->precio ?>" class="txtField itmTxtFld priceFld transTw" placeholder="e.j.: 1,000.00">
		        	<div class="styledSelect currency">
			        	<select class="normalSelect" name="moneda">
				        	<option value="RD" <?php echo $producto->moneda == 'RD' ? 'selected' : ''; ?>>RD$</option>
							<option value="US" <?php echo $producto->moneda == 'US' ? 'selected' : ''; ?>>US$</option>
							<option value="EU" <?php echo $producto->moneda == 'EU' ? 'selected' : ''; ?>>EU€</option>
			        	</select>
		        	</div>
            	</div>
            	
            	<div class="fieldGroup salePrice">
		        	<label class="settingLbl">En oferta?</label><input type="checkbox" class="salePriceCheck" name="enoferta" value="1" <?php echo $producto->enoferta == 1 ? "checked" : ""; ?>>
		        	<br />
		        	
		        	<input type="text" name="precioOferta" value="<?php echo $producto->preciooferta ?>" class="txtField itmTxtFld salePriceFld transTw" placeholder="Precio en oferta">
		        	
		        	
            	</div>
				<div class="itmDescrWrap">
					<label class="settingLbl itmsLbl">Descripción del artículo: </label>
					<div class="tipWrap category">
						<p class="tipDescrip popUpMsg transTw">
							Editor full HTML para dar mejor estilo a tus descripciones. Necesitas ayuda? <a href="#">Click aquí</a>.
						</p>
					</div>
					<?php echo $session["error"] === 7 ? "<span class='error-label'>Agrega una descripción a tu artículo</span>" : ""; ?>
	                <textarea name="descripcionProducto" class="itmTxtArea" id="itmTxtFld"><?php echo $producto->descripcion ?></textarea>
	            </div>
	            
	            <label class="settingLbl itmsLbl">Palabras Clave: </label>
	            <br />
	            <input type="text" name="palabrasClave" value="<?php echo $producto->palabras_claves ?>" class="txtField itmTxtFld keyFld" placeholder="Separadas por coma">
	            <br />
	            <span class="fieldHelper">Mientras más palabras agregues mayor probabilidad tienes de que tu artículo sea encontrado.</span>
	       	</div>
		   	
	       	 <input type="hidden" name="caract_default" class="caractDefault" value="<?php echo $producto->caracteristica_pred ?>">
	    </form>
	
	
		<div class="addImgWrap itmFormSec 3" data-id="3">
	    	<legend class="settLegend">Imágenes del artículo</legend>
			
			<label class="settLbl">Las dimensiones mínimas para las imágenes de tu artículo deben ser <b>400px x 400px</b>.</label>
			<div class="snglCharacWrap characDetail">
				<form id="imageform" method="post" enctype="multipart/form-data" action='/p/upd_productos/upd_img_predeterminadas.php'>
					<input type="text" class="caract_default txtField itmTxtFld" value="<?php echo $producto->caracteristica_pred ?>">
					<input type="hidden" value="<?php echo $_GET["prod_id"] ?>" name="idproduct">
			        <br />
			        <div class="upldInptMask">
			        	<input type="file" name="file" class="imgupl" >
			        </div>
			        
			        <div class="imgListWrap imgCaract oldImgs">
				    	<?php 
						$query = $con->prepare('SELECT * FROM `imagenproducto` where idproducto = ? ');
					    $query->bindValue(1,$_GET["prod_id"], PDO::PARAM_INT);
					    $query->execute();
						$imagenProducto = $query->fetchAll(PDO::FETCH_OBJ);
						foreach($imagenProducto as $iP)
						{
				        	list($width, $height) = getimagesize("images/productos/thumb150/".$iP->imagen."");
						
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
							<div class="uplddImgWrap">
								<input type="hidden" class="imageProductVal" value="<?php echo $iP->imagen ?>">
								<span class="itmImageMask imageDefaultMask">
									<img class="imageDefault itmImage" <?php if($height > $width){  ?> width="<?php echo $baseDimm; ?>" height="<?php echo $modVal; ?>" style="top: -<?php echo $pos; ?>px;"  <?php }  else{ ?> height="<?php echo $baseDimm; ?>" width="<?php echo $modVal; ?>" style="left: -<?php echo $pos; ?>px;" <?php } ?> src="/images/productos/thumb150/<?php echo $iP->imagen; ?>" >
								</span>
							</div>
							<?php
						}
						?>
				    </div>
				    
				    <div class="imagenes imgListWrap imgCaract defCharImgList newImgs">
						<?php 
				        $query2 = $con->prepare('SELECT `img_temporales`, `idusuario` FROM `img_temporales` WHERE idusuario = ? and idproducto = ? and `delete` = 0');
				        $query2->bindValue(1,$session["usuario"], PDO::PARAM_INT);
						$query2->bindValue(2,$_GET["prod_id"],PDO::PARAM_INT);
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
				            <div class="uplddImgWrap">
				            	<input type="hidden" class="imageProductVal" value="<?php echo $imgs->img_temporales ?>">
				                <span class="itmImageMask imageDefaultMask">
				                	<img class="imageDefault itmImage" <?php if($height > $width){  ?> width="<?php echo $baseDimm; ?>" height="<?php echo $modVal; ?>" style="top: -<?php echo $pos; ?>px;"  <?php }  else{ ?> height="<?php echo $baseDimm; ?>" width="<?php echo $modVal; ?>" style="left: -<?php echo $pos; ?>px;" <?php } ?> src="/images/productos/thumb150/<?php echo $imgs->img_temporales ?>">
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
		    	
				$query3 = $con->prepare('SELECT `idcaracteristica_producto`, `idproducto`, `temp_descripcion`, `descripcion`, `cantidad`, `fecha_creacion` FROM `caracteristica_producto` WHERE `idproducto`  = ? and `delete` = 0');
		        $query3->bindValue(1,$_GET["prod_id"], PDO::PARAM_INT);
		        $query3->execute();
		        $caracteristica = $query3->fetchAll(PDO::FETCH_OBJ);
				$n = 0;
				foreach($caracteristica as $cT)
				{
					?>
					<form class="caracteristica xtraCharacDetail characDetail transTh" action="/p/upd_productos/upd_img_caract.php" method="post">
				    
				    	<input type="text" class="caracteristica txtField itmTxtFld" value="<?php
				    	if(empty($cT->temp_descripcion))
						{
							echo $cT->descripcion;
						}
						else {
							echo $cT->temp_descripcion;
						}
				    	 ?>">
				    	<input type="hidden" name="idproducto" value="<?php echo $_GET["prod_id"] ?>">
				    	<input type="hidden" name="id" class ="idCar" value="<?php echo $cT->idcaracteristica_producto ?>" >
				    	<button class="removeCharacBtn" type="button"></button>
				    	<br />
				    	
						<div class="upldInptMask">
				    		<input type="file" name="file" class="imgcarupl">
				    	</div>
				    	<input type="hidden" class="cont" value="<?php echo $n ?>">
				    	
				    	<div class="imgCaract imgListWrap imgChWrap<?php echo $n ?>" data-id="imgChWrap<?php echo $n ?>">
				    		<?php
								$query3 = $con->prepare('SELECT `idimagencaracteristica`, `imagen`, `idcaracteristica_producto` FROM `imagencaracteristica` WHERE `idcaracteristica_producto` = ? and `delete` = 0');
					            $query3->bindValue(1,$cT->idcaracteristica_producto, PDO::PARAM_INT);
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
									$pos = ($modVal - $baseDimm)/2
								
									?>
									<div class="uplddImgWrap">
										<input type="hidden" class="imageProductVal" value="<?php echo $iC->imagen ?>">
										<span class="itmImageMask">
											<img class="imageCaractProducto itmImage" <?php if($height > $width){  ?> width="<?php echo $baseDimm; ?>" height="<?php echo $modVal; ?>" style="top: -<?php echo $pos; ?>px;"  <?php }  else{ ?> height="<?php echo $baseDimm; ?>" width="<?php echo $modVal; ?>" style="left: -<?php echo $pos; ?>px;" <?php } ?> src="/images/c_prod/thumb150/<?php echo $iC->imagen ?>">
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
		</div>
		<div class="ctrlBtnWrap">
		   <a href="<?php echo $_SERVER["HTTP_REFERER"]; ?>" class="formActionBtn back cancel transTw">Cancelar</a>
		   <span class="formActionBtn finish transTw">Guardar</span>
	    </div>
	</div>
	<?php
} 
?>

<script src="/Scripts/jquery.form.min.js"></script>

<script type="application/javascript"> 

	/* checks if price is numeric */
       
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

	   
	//saves characteristic form fields as user types   

	$('.caract_default').on('keyup',function()
	{
		$('.caractDefault').val($(this).val());
	});
	
	$('.finish').click(function(){
		$('.itmDetailForm').submit();
	});		
	

	$('.characsWrap').on('keyup','.caracteristica', function()
	{
		
		var id = $(this).siblings('.idCar').val();
		$.ajax({
			type: "POST",
			url: "/p/upd_productos/upd_caracteristica.php",
			data: {idcaracteristica : id, temp_descripcion : $(this).val()}
		})
	})
	

	/* removes images from item on click */
	$('.addImgWrap').on('click', '.itmImageMask', function(){
	
		var imageVal = $(this).siblings('.imageProductVal').val();
		
		if($(this).hasClass('imageDefaultMask')){
			var imgUrl = ("/p/upd_productos/rem_prod_img.php");
		}
		
		else{
			var imgUrl = ("/p/upd_productos/rem_image_caract.php");
		}
		
		$.ajax({
			type: "POST",
			url: imgUrl,
			data: {image : imageVal}
		});
		
		$(this).parent('.uplddImgWrap').remove();
	});


	/* adds characterisc wrapp  */
	
 	function remHidden(){
	 	$('.characDetail').removeClass('hidden');
 	}	
	
	$('.addCharacBtn').click(function(){
		var characCnt = $('.characsWrap').children('.characDetail').length;
		
		if(characCnt < 4){
			var caracteristica = $('.addCharWrap').siblings('.caracteristica').val();
			var producto = <?php echo $_GET["prod_id"] ?>;
			$(this).siblings('.caracteristica').val('');
			//if(caracteristica)
			//{
				$.ajax({
					type: "POST",
					url: "/p/upd_productos/add_caract_producto.php",
					data: {prod: producto, descripcion: caracteristica, con : characCnt}
				}).done(function(html)
				{
					$('.characsWrap').append(html);
					
					setTimeout(remHidden, 10);
				})
			//}
		}
		
	})

	/*  removes full characterisc from update items */

	function remmElem(){
		currElem.remove();
	}

	$('.characsWrap').on('click', '.removeCharacBtn', function()
	{
		var idcar = $(this).siblings('.idCar').val();
			currElem = $(this).parent();

			currElem.addClass('hidden deleted');
			currElem.children().addClass('deleted');
		
		$.ajax({
			type: "POST",
			url: "/p/upd_productos/rem_caract.php",
			data: {idcaracteristica : idcar}
		})
		
		setTimeout(remmElem, 300);
		
	})


	/* adds images to the respective item image wrap defCharImgList (default characteristic)/imgChWrap(extras) */
	var targetDiv;
	
	function appendLoadFN(){
		targetDiv.parent().append('<span class="loadBlock"><img src="/images/LoadAnim.gif" /></span>');
	}
	
	function removeLoadFN(){
		$(this).siblings('.loadBlock').last().remove();
	}

	$('.characsWrap').on('change', '.imgcarupl', function(event)	
	{	   
	    
	    var dataID = $(this).parent('.upldInptMask').siblings('.imgCaract').data('id');
	    
	    targetDiv = $('.'+dataID+'');
	    
	    
	    $(this).parent('.upldInptMask').parent('.caracteristica').ajaxForm(
	    {
	    	beforeSubmit: appendLoadFN,
	        target: targetDiv,
	        success: removeLoadFN
	    }).submit()
	});

    $('.imgupl').on('change', function()	
    { 
    
    	targetDiv = $('.defCharImgList');
        
        $("#imageform").ajaxForm(
        {
        	beforeSubmit: appendLoadFN,
	        target: targetDiv,
	        success: removeLoadFN
        }).submit();
    });



 /* loads CKEDITOR */
    CKEDITOR.replace( 'descripcionProducto', {
    toolbar: 'Basic'
});


$('.categorySel').change(function(){
	var valor = $(this).val();
	
	if($(this).hasClass('categoria1')){
		
		$('.categoria2').html();
		$('.categoria3').html();
		$.ajax({
			type: "POST",
			url: "/p/p_f/categoria2.php",
			data: {categoria1 : valor}
		}).done(function(html){
			$('.categoria2').html(html);
			$('.categoria3').html(html);
		});	
		
	}	
	
	else if($(this).hasClass('categoria2')){
		$('.categoria3').html();
		$.ajax({
			type: "POST",
			url: "/p/p_f/categoria3.php",
			data: {categoria2 : valor}
		}).done(function(html){
			$('.categoria3').html(html);
		});	
	}
});

</script>