
<script src='Scripts/jquery.form.min.js'></script>


<div class="topBar">
	<h2 class="settHead">Colocar artículos destacados</h2>
</div>

<div class="settWrap applyAdvertsWrap ">
	
	<div>
		<a class="tabItm active" href="administracion/publicidad">Artículos destacados</a>
		<a class="tabItm" href="administracion/tiendas">Tiendas destacadas</a>
		<a class="tabItm" href="administracion/anuncios">Anuncios</a>
	</div>

	<form id="addPubForm" class="displayAdvertForm" method="post" action="administracion/pub_func/add_item_pub.php">
		<label class="settingLbl">Ingresar Producto:</label>
		<input name="id" type="text" class="txtField itemShow">
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
		
		<br />
		<div class="previewItem">
			
		</div>
	</form>
	<h3>Lista de artículos destacados</h3>
	<div class="featuredItemsList">
		<div class="advListHead featItemHead">
			<span class="advListHeadItem advListItm featItemName">Artículo</span>
			<span class="advListHeadItem advListItm featItemPrice">Precio</span>
			<span class="advListHeadItem advListItm featItemTime">Tiempo</span>
		</div>
		<?php 
			$query = $con->prepare('select p.nombre, p.moneda, p.precio, p.preciooferta, p.enoferta, p.borrado, p.activo, pp.idproductopub, pp.terminado, pp.duracion, pp.fecha_creacion, max(i.imagen) as imagen
								from producto p
								left join imagenproducto i
								on i.idproducto = p.idproducto
								join productopub pp
								on pp.idproducto = p.idproducto
								group by p.nombre, p.moneda, p.precio, p.preciooferta, p.enoferta, p.borrado, p.activo, pp.idproductopub, pp.terminado, pp.duracion, pp.fecha_creacion
								order by pp.fecha_creacion desc
								');
			$query->execute();
			$productoPub = $query->fetchAll(PDO::FETCH_OBJ);
			$oddCount = 0;
			foreach($productoPub as $producto)
			{
				if(count($producto->nombre))
				{
					?>
					
					
				<div class="featItemDescript <?php echo ($oddCount % 2) == 0 ? "" : "odd"; $oddCount++; ?>">	
					<span class="featItemName"><?php echo $producto->nombre ?></span>
					<span class="featItemPrice">
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
						
						$fecha_final = date("Y-m-d H:i:s",strtotime('+'. $producto->duracion .' days', strtotime($producto->fecha_creacion)));
						$fecha1 = new DateTime($fecha_final);
						$fecha2 = new DateTime("now");
						$dias = $fecha2->diff($fecha1);
						$dia = $dias->format('%R%a');
						$horas = $dias->format('%R%H');
						if(($dia <= 0 and $horas <= 0) or ($producto->terminado == 1))
						{
							?>
							<span>Terminado</span>
							<?php
						}
						else {
							
							?>
							<span class="featItemTime"><?php echo $dia. 'd ' . $horas . 'h'; ?></span>
							<form method="post" action="administracion/pub_func/finish_pub.php">
								<input type="hidden" name="idproductopub" value="<?php echo $producto->idproductopub ?>">
								<button class="finishOffer">Terminar oferta</button>
							</form>
							<?php
						}
						
						?>
						<form method="post" action="administracion/pub_func/remove_pub.php">
								<input type="hidden" name="idproductopub" value="<?php echo $producto->idproductopub ?>">
								<button class="removeOffer">Quitar</button>
						</form>
				</div>			
					<?php 
				}
			}
			?>
	</div>
</div>

<script>
	function appendItemFN(html){
		$('.featuredItemsList').prepend(html);
	}
	
	
		$('#addPubForm').ajaxForm(
			{
				success: appendItemFN
			}
		).submit();
</script>


<script>
	$('.itemShow').keyup(function(){
		var itemId = $(this).val();
		if(itemId)
		{
			$.ajax({
				type : "POST",
				url  : "administracion/pub_func/preview_item.php",
				data : {id : itemId}
			}).done(function(html){
				$('.previewItem').html(html);
			});
		}
	})
</script>
