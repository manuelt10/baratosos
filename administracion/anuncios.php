
<div class="topBar">
	<h2 class="settHead">Colocar anuncios</h2>
</div>

<div class="settWrap applyAdvertsWrap">

	<div>
		<a class="tabItm" href="/administracion/publicidad">Artículos destacados</a>
		<a class="tabItm" href="/administracion/tiendas">Tiendas destacadas</a>
		<a class="tabItm active" href="/administracion/anuncios">Anuncios</a>
	</div>
	<form class="addPubForm displayAdvertForm" method="post" action="/administracion/pub_func/add_adver.php" enctype="multipart/form-data">
		<label class="settingLbl">Selecciona una imagen: </label>
		<input type="file" name="file" class="fileUpload">
		<br />
		<br />
		<label class="settingLbl">URL: </label>
		<input class="txtField" type="text" name="link">
		<label class="settingLbl">Duración (días):</label>
		<div class="styledSelect daySelect">	
			<select class="normalSelect" name="dias">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="14">14</option>
				<option value="21">21</option>
				<option value="30">30</option>
			</select>
		</div>	
		<label class="settingLbl">Posición:</label>
		<div class="styledSelect positionSelect">	
			<select class="normalSelect" name="posicion">
				<option value="banner-right">Derecha</option>
				<option value="banner-top">Superior</option>
			</select>
		</div>	
		<button class="sendBtn formActionBtn sendForm">Agregar</button>
		<div class="newImage"></div>
	</form>
	
	<div class="bannerRightWraper adminBannerWrap">
		<p class="settLbl">La imagen del banner de la derecha debe ser comprendida entre <b>200x150 y 200x300</b></p>
		<?php 
		$query = $con->prepare("SELECT * FROM `anunciopub` where posicion like 'banner-right'");
		$query->execute();
		$anuncios = $query->fetchAll(PDO::FETCH_OBJ);
		foreach($anuncios as $a)
		{
			?>
			<div class="itemDetWrapp transTw userDetailWrapper pubWrapperDetail <? if($oddCnt%2==1){ echo "odd";} $oddCnt++  ?>" >
				<img class="right-ad-thumb" src="/images/publicidad/<?php echo $a->image ?>">
				
				<div class="itmListDescrWrap">
					<span class="itmListName transOn itmListDescr">Link: <?php echo $a->link; ?></span>
					<?php 
					$fecha_final = date("Y-m-d H:i:s",strtotime('+'. $a->duracion .' days', strtotime($a->fecha_creacion)));
					$fecha1 = new DateTime($fecha_final);
					$fecha2 = new DateTime("now");
					$dias = $fecha2->diff($fecha1);
					$dia = $dias->format('%R%a');
					$horas = $dias->format('%R%H');
					
				if(($dia <= 0 and $horas <= 0) or ($a->terminado == 1))
				{
				?>
				</div>
				<div class="itmListBtnWrapp">			
					<span>Terminado</span>
				<?php
				}
				else {
				?>
					<span class="itmListTime transOn itmListDescr">Restante: <?php echo $dia. 'd ' . $horas . 'h'; ?></span>
				</div>
				<div class="itmListBtnWrapp">
					<button class="finishOffer itmListBtn banButton" type="submit">Terminar anuncio</button>
					<form method="post" action="/administracion/pub_func/finish_anuncio.php">
						<input type="hidden" name="idanunciopub" value="<?php echo $a->idanunciopub ?>">
							
					</form>
				<?php
				}
				?>
						<input type="hidden" class="idanunciopub" value="<?php echo $a->idanunciopub ?>">
						<button class="removeOffer itmListBtn itmRemoveBtn" type="button">Quitar anuncio</button>
				</div>		
			</div>
			<?php
		}
		
		?>
	</div>
	<div class="bannerTopWraper adminBannerWrap">
		<p class="settLbl">La imagen del banner superior debe ser exactamente <b>728x90</b></p>
		
		<?php 
		$query = $con->prepare("SELECT * FROM `anunciopub` where posicion like 'banner-top'");
		$query->execute();
		$anuncios = $query->fetchAll(PDO::FETCH_OBJ);
		foreach($anuncios as $a)
		{
			?>
			<div class="itemDetWrapp transTw userDetailWrapper pubWrapperDetail <? if($oddCnt%2==1){ echo "odd";} $oddCnt++  ?>" >
				<img class="top-ad-thumb" src="/images/publicidad/<?php echo $a->image ?>">
				<br />
				<div class="itmListDescrWrap">
					<span class="itmListName transOn itmListDescr">Link: <?php echo $a->link; ?></span>
					<?php 
					$fecha_final = date("Y-m-d H:i:s",strtotime('+'. $a->duracion .' days', strtotime($a->fecha_creacion)));
					$fecha1 = new DateTime($fecha_final);
					$fecha2 = new DateTime("now");
					$dias = $fecha2->diff($fecha1);
					$dia = $dias->format('%R%a');
					$horas = $dias->format('%R%H');
				if(($dia <= 0 and $horas <= 0) or ($a->terminado == 1))
				{
				?>
				</div>
				<div class="itmListBtnWrapp">				
					<span>Terminado</span>
				<?php
				}
				else {
				?>
					<span class="itmListTime transOn itmListDescr">Restante: <?php echo $dia. 'd ' . $horas . 'h'; ?></span>
				</div>
				<div class="itmListBtnWrapp">	
					<button class="finishOffer itmListBtn banButton" type="submit">Terminar anuncio</button>
					<form method="post" action="/administracion/pub_func/finish_anuncio.php">
						<input type="hidden" name="idanunciopub" value="<?php echo $a->idanunciopub ?>">
					</form>
				<?php
				}
				?>
						<input type="hidden" class="idanunciopub" value="<?php echo $a->idanunciopub ?>">
						<button class="removeOffer itmListBtn itmRemoveBtn type="button">Quitar anuncio</button>
				</div>	
			</div>
			<?php
		}
		
		?>
	</div>
</div>

<script>
	$('.finishOffer').on('click', function(){
		$(this).next().submit();
	});
</script>

<script>
	$('.removeOffer').click(function(){
		$(this).parent('.pubWrapperDetail').remove();
		var id = $(this).siblings('.idanunciopub').val();
		$.ajax({
			type : "POST",
			url : "/administracion/pub_func/remove_anun.php",
			data : {idanunciopub : id}
		})
	})
</script>
<script>
	$(function(){
    	$('.addPubForm').on("change",".fileUpload",showPreviewImage_click);
	})

	function showPreviewImage_click(e) {
	    var $input = $(this);
	    var inputFiles = this.files;
	    if(inputFiles == undefined || inputFiles.length == 0) return;
	    var inputFile = inputFiles[0];
	
	    var reader = new FileReader();
	    reader.onload = function(event) {
	    	$input.siblings('.newImage').html('<img src="' + event.target.result + '">')
	        //$input.next().attr("src", event.target.result);
	    };
	    reader.readAsDataURL(inputFile);
	}
</script>