<div class="featItmWrap responsive">
	<h3 class="featuredItemsHead">Artículos destacados</h3>	
	<?php 
	 $query = $con->prepare('SELECT pp.*, p.nombre, p.precio, p.preciooferta, p.enoferta, ip.imagen, p.moneda
							FROM productopub pp
							JOIN producto p 
							on p.idproducto = pp.idproducto
							LEFT JOIN (select idproducto, max(`imagen`) as imagen from imagenproducto group by idproducto order by principal desc, fecha_creacion asc) ip
							on ip.idproducto = pp.idproducto
							WHERE pp.terminado = 0
							and pp.fecha_creacion + INTERVAL duracion DAY >= curdate()
							and p.borrado = 0
							and p.activo = 1
							order by rand()
							limit 0,4
							');
		$query->execute();
		$productosOferta = $query->fetchAll(PDO::FETCH_OBJ);
		foreach($productosOferta as $pO)
		{
			$link = $sM->remove_accents($pO->nombre);
			$link = str_replace("-", " ", $link);
			$link = preg_replace('/\s\s+/', ' ', $link);
			$link = str_replace(" ", "-", $link);
			$link = strtolower($link);
			$link = preg_replace('/[^A-Za-z0-9\-]/', '', $link);
			?>
			<div class="featItem">
				<?php 
				if($pO->enoferta == 1)
				{
				?>
					<span class="priceDisc">-<?php echo number_format(100-(($pO->preciooferta / $pO->precio) * 100), 2) ?>%</span>
				<?php	
				}
				?>
				<a href="articulo/<?php echo $link."-".$pO->idproducto; ?>" class="imgMask">
					<span class="maskMidle">
							<?php 
							if(empty($pO->imagen))
							{
							?>
								<img src="http://tumall.doimages/NoImage.png" alt="No image" title="No image">
							<?php 
							}
							else
							{
							?>
								<img src="images/productos/thumb150/<?php echo $pO->imagen ?>" title="<?php echo $pO->nombre; ?>" alt="<?php echo $pO->nombre; ?>" >
							<?php 
							}
							?>
					</span>
				</a>
				<a class="featItmName" href="articulo/<?php echo $link."-".$pO->idproducto; ?>"><?php echo $pO->nombre ?></a>
				<span class="featItmPric <?php echo $pO->enoferta == 1 ? "enoferta" : ""; ?>"><?php echo $pO->moneda ?>$ <?php echo number_format($pO->enoferta == 1 ? $pO->preciooferta : $pO->precio,2,'.',',') ?></span>
			</div>
			<?php
		}
	?>
			

</div><!-- close featured -->