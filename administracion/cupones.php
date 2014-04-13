<div class="topBar">
	<h2 class="settHead">Administrar cupones</h2>
</div>
<div class="settWrap manageCuponsWrap">
	
	<div class="listActionsWrap listSearchWrap">
		<form method="post" action="/administracion/cuponesnuevo">
			<button class="sendForm formActionBtn addCuponBtn">Añadir nuevo</button>
		</form>
	</div>
		<?php 
		require_once('phpfn/mysqlManager.php');
		require_once('phpfn/stringmanager.php');
		$db = new mysqlManager();
		$sM = new stringManager();
		//RECORD APROBADOS
		$records = $db->selectRecord('cupones_aprobados_ac',NULL,Array('elim_admin' => 0),Array('estatus' => 'desc','dia_publicacion' => 'desc'));
		?>
		<div class="aprovedWrap">
		<?php
		foreach($records->data as $c)
		{
			?>
			<div class="itemDetWrapp fluidTransTw <? if($oddCnt%2==1){ echo "odd ";} echo $c->estatus == 1 ? "" : "inactive"; echo $c->aprobado == 0 ? "pendiente" : ""; $oddCnt++  ?>">
				<a class="itmImageMask itmListMask"  href="/cupon/<?php echo $link . '-' . $c->idcupon;  ?>">
					<?php 
					$img = $db->selectRecord('cupon_galeria',NULL,Array('idcupon' => $c->idcupon),Array('principal' => 'desc'));
					
					list($width, $height) = getimagesize("images/cupon/thumb150/".$img->data[0]->imagen."");
					$baseDimm = 85;
												
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
					
					
					if(!empty($img->data))
					{
					?>
					<img class="itmListImg itmImage" <?php if($height > $width){  ?> width="<?php echo $baseDimm; ?>" height="<?php echo $modVal; ?>" style="top: -<?php echo $pos; ?>px;"  <?php }  else{ ?> height="<?php echo $baseDimm; ?>" width="<?php echo $modVal; ?>" style="left: -<?php echo $pos; ?>px;" <?php } ?> src="/images/cupon/thumb150/<?php echo $img->data[0]->imagen; ?>">
					<?php 
					}
					?>
				</a>
				
				<div class="itmListDescrWrap">
					<h3><a class="itmListName transOn itmListDescr" href="/cupon/<?php echo $link . '-' . $c->idcupon;  ?>"><?php echo $c->titulo ?></a></h3>
					<span class="itmListID itmListDescr">ID: <?php echo $c->idcupon ?></span>
					<span class="itmListDate itmListDescr inlined">El <?php 
					$fecha_pub = date('d-m-Y', strtotime($c->dia_publicacion ));
					echo $fecha_pub;
					
					
					?>
					</span>
					<span class="itmListDuration itmListDescr inlined">, por <?php echo $c->duracion_dias . 'D y ' . $c->duracion_horas . 'H' ?></span>
					<p class="itmListRemaining itmListDescr">Restante: 
					<?php 
					$fecha_final = date("Y-m-d H:i:s",strtotime($c->dia_publicacion . ' + '. $c->duracion_dias .' days'));
					$fecha_final = date("Y-m-d H:i:s",strtotime($fecha_final . ' + '. $c->duracion_horas .' hours'));
					
					$fecha1 = new DateTime($fecha_final); 
					$fecha2 = new DateTime("now");  
					$dias = $fecha2->diff($fecha1); 
					$dia = $dias->format('%R%a'); 
					$horas = $dias->format('%R%H'); 
					$minutos = $dias->format('%R%I'); 
					$segundos = $dias->format('%R%S'); 
					if($dias->invert == 1)
					{
						?>
						<span class="day">00</span> :
						<span class="hour">00</span> :
						<span class="minute">00</span> :
						<span class="second">00</span>
						
						<?php
					}
					else {
						?>
						<span class="day"><?php echo substr($dia,1) ?></span> :
						<span class="hour"><?php echo substr($horas,1) ?></span> :
						<span class="minute"><?php echo substr($minutos,1) ?></span> :
						<span class="second"><?php echo substr($segundos,1) ?></span>
						<?php
					}
					?> 
	
					</p>
				</div>
				
				<div class="itmListBtnWrapp">
					<span class="openBtn"><i class="fa fa-chevron-right fluidTransTw"></i></span>
					<?php 
					$link = $sM->remove_accents($c->titulo);
					$link = str_replace("-", " ", $link);
					$link = preg_replace('/\s\s+/', ' ', $link);
					$link = str_replace(" ", "-", $link);
					$link = strtolower($link);
					$link = preg_replace('/[^A-Za-z0-9\-]/', '', $link);
					
					?>
					<!-- <a class="itmListBtn itmStatBtn inactive" >Ver</a> -->
					<a class="itmListBtn editBtn" href="/administracion/cuponesmod/<?php echo $c->idcupon ?>">Modificar</a>
					<?php 
					if($c->aprobado <> 0)
					{
						if($c->estatus == 1)
						{
						?>
							<span class="itmListBtn itmStatBtn active">Inactivar</span>
						<?php 
						}
						else {
							?>
							<span class="itmListBtn itmStatBtn inactive">Restaurar</span>
							<?php
						}
						?>
						<?php
					}
					else
					{ 
						?>
						<button class="itmListBtn itmStatBtn aproveBtn">Aprobar</button>
						<button class="itmListBtn itmRemoveBtn reproveBtn">Rechazar</button>
						
						<?php
					}
					
					?>
					
					<input type="hidden" name="idcupon" value="<?php echo $c->idcupon ?>" class="idcup">
				</div>
			</div>
			<?php
		}
		?>
		</div>
		<div class="reprovedWrap">
		<div class="listActionsWrap listSearchWrap">
			<h3 class="settLegend listSeparator">Cupones rechazados</h3>
		</div>
		<?php 
		//RECORDS NO APROBADOS
		$records_noap = $db->selectRecord('cupones_aprobados_inac',NULL,Array('elim_admin' => 0),Array('estatus' => 'desc','dia_publicacion' => 'desc'));
		foreach($records_noap->data as $c)
		{
			?>
			<div class="itemDetWrapp fluidTransTw <? if($oddCnt%2==1){ echo "odd";} echo $c->estatus == 1 ? "" : " inactive"; $oddCnt++  ?>">
				<a class="itmImageMask itmListMask"  href="/cupon/<?php echo $link . '-' . $c->idcupon;  ?>">
					<?php 
					$img = $db->selectRecord('cupon_galeria',NULL,Array('idcupon' => $c->idcupon),Array('principal' => 'desc'));
					
					list($width, $height) = getimagesize("images/cupon/thumb150/".$img->data[0]->imagen."");
					$baseDimm = 85;
												
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
					
					
					if(!empty($img->data))
					{
					?>
					<img class="itmListImg itmImage" <?php if($height > $width){  ?> width="<?php echo $baseDimm; ?>" height="<?php echo $modVal; ?>" style="top: -<?php echo $pos; ?>px;"  <?php }  else{ ?> height="<?php echo $baseDimm; ?>" width="<?php echo $modVal; ?>" style="left: -<?php echo $pos; ?>px;" <?php } ?> src="/images/cupon/thumb150/<?php echo $img->data[0]->imagen; ?>">
					<?php 
					}
					?>
				</a>
				
				<div class="itmListDescrWrap">
					<h3><a class="itmListName transOn itmListDescr" href="/cupon/<?php echo $link . '-' . $c->idcupon;  ?>"><?php echo $c->titulo ?></a></h3>
					<span class="itmListID itmListDescr">ID: <?php echo $c->idcupon ?></span>
					<span class="itmListDate itmListDescr inlined">El <?php 
					$fecha_pub = date('d-m-Y', strtotime($c->dia_publicacion ));
					echo $fecha_pub;
					
					
					?>
					</span>
					<span class="itmListDuration itmListDescr inlined">, por <?php echo $c->duracion_dias . 'D y ' . $c->duracion_horas . 'H' ?></span>
					<p class="itmListRemaining itmListDescr">Restante: 
					<?php 
					$fecha_final = date("Y-m-d H:i:s",strtotime($c->dia_publicacion . ' + '. $c->duracion_dias .' days'));
					$fecha_final = date("Y-m-d H:i:s",strtotime($fecha_final . ' + '. $c->duracion_horas .' hours'));
					
					$fecha1 = new DateTime($fecha_final); 
					$fecha2 = new DateTime("now");  
					$dias = $fecha2->diff($fecha1); 
					$dia = $dias->format('%R%a'); 
					$horas = $dias->format('%R%H'); 
					$minutos = $dias->format('%R%I'); 
					$segundos = $dias->format('%R%S'); 
					if($dias->invert == 1)
					{
						?>
						<span class="day">00</span> :
						<span class="hour">00</span> :
						<span class="minute">00</span> :
						<span class="second">00</span>
						
						<?php
					}
					else {
						?>
						<span class="day"><?php echo substr($dia,1) ?></span> :
						<span class="hour"><?php echo substr($horas,1) ?></span> :
						<span class="minute"><?php echo substr($minutos,1) ?></span> :
						<span class="second"><?php echo substr($segundos,1) ?></span>
						<?php
					}
					?> 
	
					</p>
				</div>
				
				<div class="itmListBtnWrapp">
					<span class="openBtn"><i class="fa fa-chevron-right fluidTransTw"></i></span>
					<?php 
					$link = $sM->remove_accents($c->titulo);
					$link = str_replace("-", " ", $link);
					$link = preg_replace('/\s\s+/', ' ', $link);
					$link = str_replace(" ", "-", $link);
					$link = strtolower($link);
					$link = preg_replace('/[^A-Za-z0-9\-]/', '', $link);
					
					?>
					<!-- <a class="itmListBtn itmStatBtn inactive" >Ver</a> -->
					<a class="itmListBtn editBtn" href="/administracion/cuponesmod/<?php echo $c->idcupon ?>">Modificar</a>
					<?php 
					if($c->aprobado <> 0)
					{
						?>
						<button class="itmListBtn itmStatBtn aproveBtn">Aprobar</button>
						<span class="itmListBtn itmRemoveBtn">Remover</span>
						<?php
					}
					
					?>
					
					<input type="hidden" name="idcupon" value="<?php echo $c->idcupon ?>" class="idcup">
				</div>
			</div>
			<?php
		}
		?>
		</div>
</div>

<script>
	$('.itmRemoveBtn').click(function(){
		$(this).parents('.itemDetWrapp').remove();
		$.ajax({
			type : 'POST',
			url : '/administracion/cup_func/rem_cupon.php',
			data :{idcupon : $(this).siblings('.idcup').val()}
		});
	})
</script>
<script>
	
	$('.aproveBtn').click(function()
	{
		
		var couponItem = $(this).parent().parent();
	
		$.ajax({
			type : 'POST',
			url : '/administracion/cup_func/aprobado_cup.php',
			data :{idcupon : $(this).siblings('.idcup').val() , stat : 1}
		});
		
		$('.aprovedWrap').append(couponItem);
		couponItem.addClass('inactive');
		couponItem.children('.itmListBtnWrapp').append('<span class="itmListBtn itmStatBtn inactive">Restaurar</span>');
		couponItem.find('.aproveBtn').remove();
		couponItem.find('.itmRemoveBtn').remove();
		
	});
</script>
<script>
	$('.reproveBtn').click(function()
	{
		var id = $(this).siblings('.idcup').val(),
			couponItem = $(this).parent().parent();
		$.ajax({
			type : 'POST',
			url : '/administracion/cup_func/aprobado_cup.php',
			data :{idcupon : id , stat : 2}
		});
		
		$('.reprovedWrap').append(couponItem);
		couponItem.removeClass('pendiente');
		couponItem.children('.itmListBtnWrapp').append('<span class="itmListBtn itmRemoveBtn">Remover</span>');
		couponItem.find('.reproveBtn').remove();
		
		
	});
</script>

<script> //show/hides item's edit buttons when on mobile
	$('.openBtn').click(function(event){
		
		var list_parent = $(this).parent().parent('.itemDetWrapp');
		
		if($(this).hasClass('active')){
			$(this).removeClass('active');
			list_parent.removeClass('active');
		}
		
		else{
			$('.openBtn').removeClass('active');
			$('.itemDetWrapp').removeClass('active');
			list_parent.addClass('active');
			$(this).addClass('active');
		}
		
	});
	
	/* changes coupon status on click */
	$('.aprovedWrap').on('click', '.itmStatBtn', function(){
		var list_parent = $(this).parent().parent('.itemDetWrapp');
		
		$('.openBtn').removeClass('active');
		list_parent.removeClass('active');

		var idc = $(this).siblings('.idcup').val();

		if($(this).hasClass('active'))
		{
			
			list_parent.addClass('inactive');
			$(this).html('Restaurar').removeClass('active').addClass('inactive');
		}
		else
		{
			list_parent.removeClass('inactive');
			$(this).html('Inactivar').removeClass('inactive').addClass('active');
			
		}
		
		console.log(idc);
		
		$.ajax({
			type : "POST",
			url : '/administracion/cup_func/status_cup.php',
			data :{idcupon : idc}
		});
		
	})
	
</script>