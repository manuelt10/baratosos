<?php 
if($usuario->idtipousuario == 3)
{
	header("location: http://tumall.doadministracion/solicitudes ");
}
?>
<div class="topBar">
	<h2 class="settHead">Solicitudes enviadas</h2>
</div>


<div class="settWrap sentAdvWrapp">
	<form method="post" action="solicitud"><button class="formActionBtn transTw addAdvbtn" type="submit">Agregar Solicitud</button></form>
	
	<div class="advListBlock certificates">
		<legend class="advListTitle">Certificados</legend>
		<div class="advListHead">
			<span class="advListHeadItem advListItm advID">ID</span>
			<span class="advListHeadItem advListItm advDescr">Título</span>
			<span class="advListHeadItem advListItm advStatus">Estado</span>
		</div>
		<?php 
		$query = $con->prepare('SELECT * FROM `certificacion` WHERE `idusuario` = ? and visible = 1');
		$query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
		$query->execute();
		$certificado = $query->fetchAll(PDO::FETCH_OBJ);
		$oddCnt = 0;
		foreach($certificado as $c)
		{
			?>
			<div class="advDet <? if($oddCnt%2==1){ echo "odd";} $oddCnt++  ?>">
				
					<span class="advListDetItem advListItm advID"><?php echo $c->idcertificacion; ?></span>
					<span class="advListDetItem advListItm advDescr"><?php echo substr($c->texto,0,15); ?></span>
					<span class="advListDetItem advListItm advStatus">
						<?php 
							if($c->estado == 'E')
							{
								echo "En revisión";
							}
							else if($c->estado == 'A')
							{
								echo "Aprobado";
							}
							else if($c->estado == 'N')
							{
								echo "Reprobado";
							}
							else if($c->estado == 'C')
							{
								echo "Caducado";
							}
						?>
					 </span>
				
				<?php 
				if ($c->estado <> 'E' and $c->estado <> 'A')
				{
					?>
					<div>
						<input type="hidden" value="1" class="type">
						<input type="hidden" value="<?php echo $c->idcertificacion ?>" class="id">
						<button class="removeA">Remover</button>
					</div>
					<?php
				}
				else {
					?>
					<div class="cancelRequest">
						<input type="hidden" value="1" class="type">
						<input type="hidden" value="<?php echo $c->idcertificacion ?>" class="id">
						<button class="removeA"><i class="fa fa-times"></i></button>
					</div>
					<?php
				}
				?>
			</div>
			<?php
		}
		?>
	</div>
	
	<div class="advListBlock adverts">
		<legend class="advListTitle">Anuncios solicitados</legend>
		<div class="advListHead">
			<span class="advListHeadItem advListItm advID">ID</span>
			<span class="advListHeadItem advListItm advDescr">Título</span>
			<span class="advListHeadItem advListItm advStatus">Estado</span>
			<span class="advListHeadItem advListItm advTime">Restante</span>
		</div>
		<?php 
		$query = $con->prepare("SELECT * FROM `ofertas` WHERE `idusuario` = ? and tipo like 'Anuncio'  and visible = 1");
		$query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
		$query->execute();
		$publicidad = $query->fetchAll(PDO::FETCH_OBJ);
		$oddCnt = 0;
		foreach($publicidad as $p)
		{
			?>
			<div  class="advDet <? if($oddCnt%2==1){ echo "odd";} $oddCnt++  ?>">
				<div>
					<span class="advListDetItem advListItm advID"><?php echo $p->idofertas ?></span>
					<span class="advListDetItem advListItm advDescr"><?php echo $p->titulo ?></span>
					<span class="advListDetItem advListItm advStatus"><?php 
							if($p->estado == 'E')
							{
								echo "En revisión";
							}
							else if($p->estado == 'A')
							{
								echo "Aprobado";
							}
							else if($p->estado == 'N')
							{
								echo "No aprobado";
							}
							else if($p->estado == 'C')
							{
								echo "Caduco";
							}
					 ?></span>
					 <span class="advListDetItem advListItm advTime <?php echo $p->estado == 'A' ? "hasTime" : "" ?>">
					 	<?php
					 	if($p->estado == 'A')
						{ 
						 	if(!empty($p->fecha_aprobacion))
							{
								$fecha_final = date("Y-m-d H:i:s",strtotime('+'. $p->tiempo_duracion .' days', strtotime($p->fecha_aprobacion)));
							}
							else
								{
									$fecha_final = date("Y-m-d H:i:s",strtotime('+'. $p->tiempo_duracion .' days', strtotime($p->fecha_aprobacion)));
								}
						 	
							$fecha1 = new DateTime($fecha_final);
							$fecha2 = new DateTime("now");
							$dias = $fecha1->diff($fecha2);
							echo $dias->format('%a'). 'd ' . $dias->format('%H') . 'h';
						}
						
						else{
							?>
							
							<span>---</span>
							
							<?php
							
							
						}
					 	?>
					 </span>
				</div>
				<?php 
				if ($p->estado <> 'E' and $p->estado <> 'A')
				{
					?>
					<div>
						<input type="hidden" value="2" class="type">
						<input type="hidden" value="<?php echo $p->idofertas ?>" class="id">
						<button class="removeA">Remover</button>
					</div>
					<?php
				}
				else {
					?>
					<div class="cancelRequest">
						<input type="hidden" value="2" class="type">
						<input type="hidden" value="<?php echo $p->idofertas ?>" class="id">
						<button class="removeA"><i class="fa fa-times"></i></button>
					</div>
					<?php
				}
				if($p->estado == 'C')
				{
					?>
					<form method="POST" action="p/publicidad_func/ren_publicidad.php">
						<input type="hidden" value="1" class="type" name="type">
						<input type="hidden" value="<?php echo $p->idofertas ?>" class="id" name="id">
						<button type="submit">Renovar</button>
						<select class="tiempoAprobacion" name="tiempoRenovacion">
							<option value="1">1 dia</option>
							<option value="2">2 dias</option>
							<option value="3">3 dias</option>
							<option value="4">4 dias</option>
							<option value="5">5 dias</option>
							<option value="6">6 dias</option>
							<option value="7">7 dias</option>
							<option value="14">14 dias</option>
							<option value="21">21 dias</option>
							<option value="30">30 dias</option>
						</select>
					</form>
					<?php
				}
				?>
			</div>
			<?php
		}
		?>
	</div>
	
	<div class="advListBlock offers">
		<legend class="advListTitle">Ofertas solicitadas</legend>
		<div class="advListHead">
			<span class="advListHeadItem advListItm advID">ID</span>
			<span class="advListHeadItem advListItm advDescr">Título</span>
			<span class="advListHeadItem advListItm advStatus">Estado</span>
			<span class="advListHeadItem advListItm advTime">Restante</span>
		</div>
		<?php 
		$query = $con->prepare("SELECT * FROM `ofertas` WHERE `idusuario` = ? and tipo like 'Oferta'  and visible = 1");
		$query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
		$query->execute();
		$publicidad = $query->fetchAll(PDO::FETCH_OBJ);
		$oddCnt = 0;
		foreach($publicidad as $p)
		{
			?>
			<div  class="advDet <? if($oddCnt%2==1){ echo "odd";} $oddCnt++  ?>">
				<div>
					<span class="advListDetItem advListItm advID"><?php echo $p->idofertas ?></span>
					<span class="advListDetItem advListItm advDescr"><?php echo $p->titulo ?></span>
					<span class="advListDetItem advListItm advStatus"><?php 
							if($p->estado == 'E')
							{
								echo "En revisión";
							}
							else if($p->estado == 'A')
							{
								echo "Aprobado";
							}
							else if($p->estado == 'N')
							{
								echo "No aprobado";
							}
							else if($p->estado == 'C')
							{
								echo "Caduco";
							}
					 ?></span>
					 <span class="advListDetItem advListItm advTime <?php echo $p->estado == 'A' ? "hasTime" : "" ?>">
					 	<?php 
					 	if($p->estado == 'A')
						{
						 	if(!empty($p->fecha_aprobacion))
							{
								$fecha_final = date("Y-m-d H:i:s",strtotime('+'. $p->tiempo_duracion .' days', strtotime($p->fecha_aprobacion)));
							}
							else
								{
									$fecha_final = date("Y-m-d H:i:s",strtotime('+'. $p->tiempo_duracion .' days', strtotime($p->fecha_aprobacion)));
								}
							$fecha1 = new DateTime($fecha_final);
							$fecha2 = new DateTime("now");
							$dias = $fecha1->diff($fecha2);
							echo $dias->format('%a'). 'd ' . $dias->format('%H') . 'h';
						}
						
						else{
							?>
							
							<span>---</span>
							
							<?php
						}
						 	?>
						 
					 </span>
				</div>
				<?php 
				if ($p->estado <> 'E' and $p->estado <> 'A')
				{
					?> 
					<div>
						<input type="hidden" value="3" class="type">
						<input type="hidden" value="<?php echo $p->idofertas ?>" class="id">
						<button class="removeA">Remover</button>
					</div>
					<?php
				}
				if($p->estado == 'C')
				{
					?>
					<form method="POST" action="p/publicidad_func/ren_publicidad.php">
						<input type="hidden" value="1" class="type" name="type">
						<input type="hidden" value="<?php echo $p->idofertas ?>" class="id" name="id">
						<button type="submit">Renovar</button>
						<select class="tiempoAprobacion" name="tiempoRenovacion">
							<option value="1">1 dia</option>
							<option value="2">2 dias</option>
							<option value="3">3 dias</option>
							<option value="4">4 dias</option>
							<option value="5">5 dias</option>
							<option value="6">6 dias</option>
							<option value="7">7 dias</option>
							<option value="14">14 dias</option>
							<option value="21">21 dias</option>
							<option value="30">30 dias</option>
						</select>
					</form>
					<?php
				}
				?>
			</div>
			<?php
		}
		?>
	</div>
</div>

<script>
	$('.removeA').click(function()
	{
		var idA = $(this).siblings('.id').val();
		var typeA = $(this).siblings('.type').val();
		$(this).parents('.advDet').remove();
		$.ajax({
			type: "POST",
			url: "p/publicidad_func/rem_publicidad.php",
			data: {type : typeA, id : idA}
		})
	})
</script>
