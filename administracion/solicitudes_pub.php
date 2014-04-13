<div class="topBar">
	<h2 class="settHead">Solicitudes recibidas</h2>
</div>


<div class="settWrap recAdvWrapp adverts">

	<a class="tabItm requestTabs certTab" href="/administracion/solicitudes">Certificados</a>
	<a class="tabItm requestTabs advertTab active" href="/administracion/solicitudes1">Publicidad</a>
	<a class="tabItm requestTabs offerTab" href="/administracion/solicitudes2">Oferta</a>

	<?php 
	$count_adv = 2;
	$pageadver = $_GET["page"];
			if(!$pageadver)
			{
				$startadver = 0;
				$pageadver  = 1;
			}
			else {
				$startadver = ($pageadver - 1) * $count_adv; 
			}
			
			$query = $con->prepare("SELECT * FROM `ofertas` WHERE tipo like 'Anuncio'");
			$query->execute();
			$conteoPublicidad = $query->fetchAll(PDO::FETCH_OBJ);
			
	$total = count($conteoPublicidad);
	$total_pages = ceil($total / $count_adv);
	
	$prevPage = $pageadver-1;
	$nextPage = $pageadver+1;
	
			$query = $con->prepare("SELECT * FROM `ofertas` WHERE tipo like 'Anuncio' LIMIT " . $startadver .','. $count_adv);
			$query->execute();
			$publicidad = $query->fetchAll(PDO::FETCH_OBJ);
			
	?>
	<div class="advListBlock adverts">
		<div class="settingsPagination">
			<?php
			if($total_pages > 1)
				{
					echo $pageadver == 1 ? 1 : (($pageadver-1)*$count_adv)+1 ?> al <?php echo $pageadver == $total_pages ? $total : ($pageadver*$count_adv) ?> de <span><?php echo $total; ?></span>
							
						<a <?php echo $pageadver > 1 ? "href='http://tumall.do/administracion/solicitudes1/page/$prevPage/'" : "" ?> class="pageCtrls prevPage"><i class="fa fa-angle-left"></i></a>
		
		
						<a <?php echo $pageadver < $total_pages ? "href='http://tumall.do/administracion/solicitudes1/page/$nextPage/'" : "" ?> class="pageCtrls nextPage"><i class="fa fa-angle-right"></i></a>
							
				<?		
					
				}
			?>
		</div>

		<div class="advListHead">
			<span class="advListHeadItem advListItm advID">ID</span>
			<span class="advListHeadItem advListItm advDescr">Título</span>
			<span class="advListHeadItem advListItm advStatus">Estado</span>
			<span class="advListHeadItem advListItm advTime">Tiempo restante</span>
		</div>
		
		<?php 
			$oddCnt = 0;
			foreach($publicidad as $p)
			{
				?>
				<div  class="advDet <? if($oddCnt%2==1){ echo "odd";} $oddCnt++  ?>">
					<span class="advListDetItem advListItm advID"><span class="helpText">ID: </span><?php echo $p->idofertas ?></span>
					<a href="/administracion/anuncio/<?php echo $p->idofertas ?>" class="advListDetItem advListItm advDescr"><span class="helpText">Titulo: </span><?php echo $p->titulo ?></a>
					<span class="advListDetItem advListItm advStatus"><span class="helpText">Status: </span><?php 
							if($p->estado == 'E')
							{
								echo "Pendiente";
							}
							else if($p->estado == 'A')
							{
								echo "Aprobado";
							}
							else if($p->estado == 'N')
							{
								echo "Reprobado";
							}
					 ?>
					 </span>
					 <span class="advListDetItem advListItm advTime">
					 	<span class="helpText">Restante: </span>
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
					 	?>
					 </span>
					 
				</div>
				
				<?php
			}
			?>
	</div>
</div>