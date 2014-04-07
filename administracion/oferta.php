<?php 
require_once('phpfn/stringmanager.php');
$sM = new stringManager();
$query = $con->prepare('SELECT * FROM `ofertas` where idofertas = ?');
		 $query->bindValue(1,$_GET["id"], PDO::PARAM_INT);
		 $query->execute();
$oferta = $query->fetch(PDO::FETCH_OBJ);
$query = $con->prepare('SELECT * FROM `usuario` where idusuario = ?');
		 $query->bindValue(1,$oferta->idusuario, PDO::PARAM_INT);
		 $query->execute();
$usr = $query->fetch(PDO::FETCH_OBJ);
?>
<script src='Scripts/jquery.form.min.js'></script>
<div class="topBar">
	<h2 class="settHead">Revisar solicitud</h2>
</div>
<div class="settWrap requestFormWrap adminReviewWrap">
	<div class="requestForm shown">
		<?php 
		if(!empty($oferta->imagen))
		{
			?>
			<div class="requFileWrap">
				<div class="newImage">
					<img src="images/publicidad/<?php echo $oferta->imagen ?>">
				</div>
			</div>
			<?php
		}
		?>
	<div class="requDetailWrap">
		<span class="settingLbl requLbl">Tipo: <?php echo $oferta->tipo ?></span>
		<br />
		<span class="settingLbl requLbl">Tienda: <?php echo $usr->nombretienda ?></span>
		<br />
		<span class="settingLbl requLbl">Propietario: <?php echo $usr->nombre ?></span>
		<br />
		<span class="settingLbl requLbl">Telefono Personal: <?php echo $usr->telefono1 ?></span>
		<br />
		<span class="settingLbl requLbl">Telefono Adicional: <?php echo $usr->telefono2 ?></span>
		<br />
		<span class="settingLbl requLbl">Titulo: <?php echo $oferta->titulo ?></span>
		<span><?php echo $oferta->tiempo_duracion ?> Dias</span> 
		<br />
		<?php 
		if(!empty($oferta->link))
		{
			?>
			<label class="settingLbl requLbl">url: <?php echo $oferta->link; ?> </label>
		<br />
			<?php
		}
		?>
		<br />
		<label class="settingLbl requLbl">Descripción: </label>
		<br />
		
		<textarea class="txtField transTw requDescr largeField" disabled="disabled"><?php echo nl2br($oferta->descripcion); ?></textarea>
		<br />
		
	
		<span class="status settingLbl requLbl">Status: <?php 
							if($oferta->estado == 'E')
							{
								echo "Pendiente";
							}
							else if($oferta->estado == 'A')
							{
								echo "Aprobado";
							}
							else if($oferta->estado == 'N')
							{
								echo "Reprobado";
							}
					 ?></span>
	</div>
	<?php 
	
	
	if($oferta->tipo == 'Oferta')
	{
	$query = $con->prepare('SELECT op.idofertas_producto, op.idofertas, op.idproducto, op.fecha_creacion, p.nombre, p.moneda, p.precio, p.preciooferta, p.enoferta, p.activo, p.borrado, min(imagen) as imagen
							FROM ofertas_producto op
							join producto p
							on p.idproducto = op.idproducto
							left join imagenproducto ip
							on ip.idproducto = op.idproducto
							where op.idofertas = :id
							group by op.idofertas_producto, op.idofertas, op.idproducto, op.fecha_creacion, p.nombre, p.moneda, p.precio, p.preciooferta, p.enoferta, p.activo, p.borrado');
	$query->bindValue(':id',$_GET["id"], PDO::PARAM_INT);
	$query->execute();
	$ofertaProductos = $query->fetchAll(PDO::FETCH_OBJ);
	if(count($ofertaProductos))
	{
		?>
		<div>
			<?php
			foreach($ofertaProductos as $producto)
			{
				$link = $sM->remove_accents($producto->nombre);
				$link = str_replace("-", " ", $link);
				$link = preg_replace('/\s\s+/', ' ', $link);
				$link = str_replace(" ", "-", $link);
				$link = strtolower($link);
				$link = preg_replace('/[^A-Za-z0-9\-]/', '', $link);
				?>
				<div class="previewWrapper">
					<div>
					<?php 
					if(!empty($producto->imagen))
					{
					?>
						<a href="articulo/<?php echo $link."-".$producto->idproducto; ?>" class="pictureWrapper"><img src="images/productos/thumb50/<?php echo $producto->imagen ?>" alt="resource1"></a>
					<?php 
					}
					?>
					</div>
					<a href="articulo/<?php echo $link."-".$producto->idproducto; ?>" class="pictureWrapper"><h4><?php echo $producto->nombre ?></h4></a>
					<span>
						<span class="<?php echo $producto->enoferta == 1 ? "enoferta" : ""; ?>"><?php echo $producto->moneda ?>$ <?php echo number_format($producto->precio,2,'.',',') ?></span>
						<?php 
						if($p->enoferta == 1)
						{
						?>
							<span><?php echo $producto->moneda ?>$ <?php echo number_format($producto->preciooferta,2,'.',',') ?></span>
							<span><?php echo number_format(($producto->preciooferta / $producto->precio) * 100, 2)?>% descuento</span>
						<?php	
						}
						?>
					</span>
					<?php 
					if($producto->borrado == 1)
					{
						?>
						<span class="error-label">Este producto esta borrado</span>
						<?php
					} 
					else if($producto->activo == 0)
					{
						?>
						<span  class="error-label">Este producto esta inactivo</span>
						<?php
					}
					?>
					<?php 
						if($producto->borrado == 0)
						{
							?> 
							<form class="addPubForm" method="post" action="administracion/pub_func/add_item_pub_off.php">
								<input type="hidden" name="id_off_prod" value="<?php echo $producto->idofertas_producto; ?>">
								<input name="id" type="hidden" value="<?php echo $producto->idproducto; ?>" class="itemShow">
								<label>Duracion</label>
								<select name="dias">
									<option value="1" <?php echo $oferta->tiempo_duracion == 1 ? "selected" : "" ?>>1 dia</option>
									<option value="2" <?php echo $oferta->tiempo_duracion == 2 ? "selected" : "" ?>>2 dias</option>
									<option value="3" <?php echo $oferta->tiempo_duracion == 3 ? "selected" : "" ?>>3 dias</option>
									<option value="4" <?php echo $oferta->tiempo_duracion == 4 ? "selected" : "" ?>>4 dias</option>
									<option value="5" <?php echo $oferta->tiempo_duracion == 5 ? "selected" : "" ?>>5 dias</option>
									<option value="6" <?php echo $oferta->tiempo_duracion == 6 ? "selected" : "" ?>>6 dias</option>
									<option value="7" <?php echo $oferta->tiempo_duracion == 7 ? "selected" : "" ?>>7 dias</option>
									<option value="14" <?php echo $oferta->tiempo_duracion == 14 ? "selected" : "" ?>>14 dias</option>
									<option value="21" <?php echo $oferta->tiempo_duracion == 21 ? "selected" : "" ?>>21 dias</option>
									<option value="30" <?php echo $oferta->tiempo_duracion == 30 ? "selected" : "" ?>>30 dias</option>
								</select>
							<button class="sendBtn">Agregar</button>
							<?php
						}
						?>
						
					</form>
				</div>
				<?php
			}
			?>
			
			
		</div>
		
		
		<?php
		}
	}
	?>
	<div class="ctrlBtnWrap requ-CtrlBtnWrap">
		<a class="formActionBtn back transTw" href="administracion/solicitudes1">Volver</a>
		<button type="button" class="changeStat formActionBtn cancel transTw" value="N">Reprobar</button>
		<button type="button" class="changeStat formActionBtn sendForm transTw" value="A">Aprobar</button>
		<?php 
		if($oferta->tipo == 'Anuncio')
		{
			?>
			<label>Duracion</label>
			<select class="dias">
				<option value="1" <?php echo $oferta->tiempo_duracion == 1 ? "selected" : "" ?>>1 dia</option>
				<option value="2" <?php echo $oferta->tiempo_duracion == 2 ? "selected" : "" ?>>2 dias</option>
				<option value="3" <?php echo $oferta->tiempo_duracion == 3 ? "selected" : "" ?>>3 dias</option>
				<option value="4" <?php echo $oferta->tiempo_duracion == 4 ? "selected" : "" ?>>4 dias</option>
				<option value="5" <?php echo $oferta->tiempo_duracion == 5 ? "selected" : "" ?>>5 dias</option>
				<option value="6" <?php echo $oferta->tiempo_duracion == 6 ? "selected" : "" ?>>6 dias</option>
				<option value="7" <?php echo $oferta->tiempo_duracion == 7 ? "selected" : "" ?>>7 dias</option>
				<option value="14" <?php echo $oferta->tiempo_duracion == 14 ? "selected" : "" ?>>14 dias</option>
				<option value="21" <?php echo $oferta->tiempo_duracion == 21 ? "selected" : "" ?>>21 dias</option>
				<option value="30" <?php echo $oferta->tiempo_duracion == 30 ? "selected" : "" ?>>30 dias</option>
			</select>
			<?php
		}
		?>
	</div>	
	</div>
</div>
<script>
	
</script>
<script>
	/*$('.addPubForm').ajaxForm().submit(function(e){
		e.preventDefault();
		this.submit();
		$(this).parents('.previewWrapper').remove();
	});*/
</script>
<script>
	$('.changeStat').click(function()
	{
		var id = <?php echo $_GET["id"]?>;
		var dias = $(this).siblings('.dias').val();
		var stat = $(this).val();
		if(dias)
		{
			
		}
		else
		{
			dias = <?php echo $oferta->tiempo_duracion; ?>;
		}
		$.ajax({
			type : "POST",
			url : "administracion/cert_func/change_adver_status.php",
			data: {idOfer : id, status : stat, duracion : dias}
		}).done(function()
		{
			if(stat == 'A')
			{
				$('.status').html("Aprobado")
			}
			else if(stat == 'N')
			{
				$('.status').html("No aprobado");
			}
		});
	});
</script>
