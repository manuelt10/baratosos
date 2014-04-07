<?php 
require_once('phpfn/mysqlManager.php');
$db = new mysqlManager();

$cupones = $db->selectRecord('cupones_aprobados_ac', NULL, Array('idusuario' => $session["usuario"], 'elim_tienda' => 0), Array('estatus' => 'desc','dia_publicacion' => 'desc'));
?>

<div class="topBar">
	<h2 class="settHead">Lista de cupones</h2>
</div>
<div class="settWrap manageCuponsWrap">
<div class="listActionsWrap listSearchWrap">
	<form method="post" action="http://tumall.docupones/add">
		<button type="submit" class="sendForm formActionBtn addCuponBtn">Agregar cupón</button>
	</form>
</div>
	<div >
	<?php 
	foreach($cupones->data as $c){
	?>
	<div class="itemDetWrapp fluidTransTw <? if($oddCnt%2==1){ echo "odd ";} echo $c->estatus == 1 ? "" : "inactive"; echo $c->aprobado == 0 ? "pendiente" : ""; $oddCnt++  ?>">
		<a class="itmImageMask itmListMask"  href="cupon/<?php echo $link . '-' . $c->idcupon;  ?>">
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
			<img class="itmListImg itmImage" <?php if($height > $width){  ?> width="<?php echo $baseDimm; ?>" height="<?php echo $modVal; ?>" style="top: -<?php echo $pos; ?>px;"  <?php }  else{ ?> height="<?php echo $baseDimm; ?>" width="<?php echo $modVal; ?>" style="left: -<?php echo $pos; ?>px;" <?php } ?> src="images/cupon/thumb150/<?php echo $img->data[0]->imagen; ?>">
			<?php 
			}
			?>
		</a>
		
		<div class="itmListDescrWrap">
			
			<h3><a class="itmListName transOn itmListDescr" href="cupon/<?php echo $link . '-' . $c->idcupon;  ?>"><?php echo $c->titulo ?></a></h3>
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
			<a class="itmListBtn viewReservBtn editBtn" href="reservas/<?php echo $c->idcupon ?>">Reservas</a>
			<a class="itmListBtn viewCouponBtn itmStatBtn" href="cupon/<?php echo $link . '-' . $c->idcupon;  ?>">Ver cupón</a>
		</div>
		
	</div>
		<?php
	}
	?>
	</div>
	
	
	<div>
		<div class="listActionsWrap listSearchWrap">
			<h3 class="settLegend listSeparator">Cupones rechazados</h3>
		</div>
		<?php 
		//RECORDS NO APROBADOS
		$records_noap = $db->selectRecord('cupones_aprobados_inac',NULL,Array('idusuario' => $session["usuario"] , 'elim_tienda' => 0),Array('estatus' => 'desc','dia_publicacion' => 'desc'));
		foreach($records_noap->data as $c)
		{
			?>
			<div class="itemDetWrapp fluidTransTw <? if($oddCnt%2==1){ echo "odd";} echo $c->estatus == 1 ? "" : " inactive"; $oddCnt++  ?>">
				<a class="itmImageMask itmListMask"  href="cupon/<?php echo $link . '-' . $c->idcupon;  ?>">
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
					<img class="itmListImg itmImage" <?php if($height > $width){  ?> width="<?php echo $baseDimm; ?>" height="<?php echo $modVal; ?>" style="top: -<?php echo $pos; ?>px;"  <?php }  else{ ?> height="<?php echo $baseDimm; ?>" width="<?php echo $modVal; ?>" style="left: -<?php echo $pos; ?>px;" <?php } ?> src="images/cupon/thumb150/<?php echo $img->data[0]->imagen; ?>">
					<?php 
					}
					?>
				</a>
				
				<div class="itmListDescrWrap">
					<h3><a class="itmListName transOn itmListDescr" href="cupon/<?php echo $link . '-' . $c->idcupon;  ?>"><?php echo $c->titulo ?></a></h3>
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
					if($c->aprobado <> 0)
					{
						?>
						<span href="#" class="itmListBtn itmRemoveBtn">Remover</span>
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
			url : 'p/cup_func/rem_cupon.php',
			data :{idcupon : $(this).siblings('.idcup').val()}
		});
	})
</script>
<script> //show/hides item's management buttons when on mobile
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
</script>	