
<div class="topBar">
	<h2 class="settHead">Destacar tiendas</h2>
</div>

<div class="settWrap applyAdvertsWrap">

	<div>
		<a class="tabItm" href="/administracion/publicidad">Artículos destacados</a>
		<a class="tabItm active" href="/administracion/tiendas">Tiendas destacadas</a>
		<a class="tabItm" href="/administracion/anuncios">Anuncios</a>
	</div>
	
	<form id="addPubForm" class="displayAdvertForm" method="post" action="/administracion/pub_func/add_store_pub.php">
		
		<label class="settingLbl">Código de tienda: </label>
		<input class="txtField" name="id" type="text" class="storeShow">
		<label class="settingLbl">Duración (días):</label>
		<div class="styledSelect">	
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
		<button class="sendBtn formActionBtn sendForm">Agregar</button>
		<div class="previewStore">
			
		</div>
	</form>
	
	<div>
		<?php 
		$query = $con->prepare("select tp.idtiendapub, tp.idusuario, tp.duracion, tp.terminado, tp.fecha_creacion, u.nombretienda, u.correo, u.imagen 
								from tiendapub tp
								join usuario u
								on u.idusuario = tp.idusuario
								order by tp.fecha_creacion desc
								");
		$query->execute();
		$usuarioTienda = $query->fetchAll(PDO::FETCH_OBJ);
		foreach($usuarioTienda as $uT)
		{		
			?>
			<div class="itemDetWrapp transTw userDetailWrapper <? if($oddCnt%2==1){ echo "odd";} $oddCnt++  ?>" >
				
				<?php 
				if(!empty($uT->imagen))
				{
				?>
					<span class="itmImageMask itmListMask usrImageMask">
						<img class="itmListImg itmImage" width="85" height="85" src="/images/profile/cr/<?php echo $uT->imagen ?>">
					</span>
				<?php 
				}
				else{
					?>
					<span class="itmImageMask usrImageMask transOn itmListMask noImg">
						<img src="/images/resources/storePNG-100.png" alt="No user picture" width="85" height="85" />				
					</span>
					<?php
				}	
				?>
				
				<div class="itmListDescrWrap">
					<span class="itmListName transOn itmListDescr"><?php echo $uT->nombretienda ?></span>
					<span class="itmListMail transOn itmListDescr"><?php echo $uT->correo ?></span>
		
				<?php 
				$fecha_final = date("Y-m-d H:i:s",strtotime('+'. $uT->duracion .' days', strtotime($uT->fecha_creacion)));
				$fecha1 = new DateTime($fecha_final);
				$fecha2 = new DateTime("now");
				$dias = $fecha1->diff($fecha2);
				$dia = $dias->format('%a');
				$horas = $dias->format('%H');
				if(($dia == 0 ) or ($uT->terminado == 1))
				{
					?>
					<span class="itmListStatus transOn itmListDescr">Terminado</span>
				</div>	
				<div class="itmListBtnWrapp hidden">
					<span class="openBtn"><i class="fa fa-chevron-right fluidTransTw"></i></span>
					<?php
				}
				else {
					
					?>
					<span class="itmListRemaining transOn itmListDescr"><?php echo $dia. 'd ' . $horas . 'h'; ?></span>
				</div>	
				<div class="itmListBtnWrapp hidden">
					<span class="openBtn"><i class="fa fa-chevron-right fluidTransTw"></i></span>
					<button class="finishOffer itmListBtn banButton">Terminar</button>
					<form class="endPromoteForm" method="post" action="/administracion/pub_func/finish_store_pub.php">
						<input type="hidden" name="idtiendapub" value="<?php echo $uT->idtiendapub ?>">
						
					</form>
					
					
					<?php
				} 
								
				?>
					<button class="removeOffer itmListBtn itmRemoveBtn">Quitar</button>
					<form method="post" action="/administracion/pub_func/remove_store_pub.php">
						<input type="hidden" name="idtiendapub" value="<?php echo $uT->idtiendapub ?>">
					</form>
				</div>
			</div>
			<?php
		}
		?>
		
	</div>
</div>

<script>
	$('.itmListBtn').on('click', function(){
		$(this).next().submit();
	});
</script>

<script>
	$('.storeShow').keyup(function(){
		var storeId = $(this).val();
		if(storeId)
		{
			$.ajax({
				type : "POST",
				url  : "/administracion/pub_func/preview_store.php",
				data : {id : storeId}
			}).done(function(html){
				$('.previewStore').html(html);
			});
		}
	})
</script>


<script> //show/hides item's edit buttons when on mobile
	$('.openBtn').click(function(event){
		
		if($(this).hasClass('active')){
			$(this).removeClass('active');
			$(this).parent().parent('.itemDetWrapp').removeClass('active');
		}
		
		else{
			$('.openBtn').removeClass('active');
			$('.itemDetWrapp').removeClass('active');
			$(this).parent().parent('.itemDetWrapp').addClass('active');
			$(this).addClass('active');
		}
		
	});
	
	$('.banButton').on('click', function(){
		$('.openBtn').removeClass('active');
		$(this).parent().parent('.itemDetWrapp').removeClass('active');
	});
	
</script>
