<div class="topBar">
	<h2 class="settHead">Solicitudes recibidas</h2>
</div>


<div class="settWrap recAdvWrapp certificates ">

	<a class="tabItm requestTabs certTab active" href="/administracion/solicitudes">Certificados</a>
	<a class="tabItm requestTabs advertTab" href="/administracion/solicitudes1">Publicidad</a>
	<a class="tabItm requestTabs offerTab" href="/administracion/solicitudes2">Oferta</a>
	
	<?php 
	$count_cert = 3;
	
	$pagecert = $_GET["page"];
	
			if(!$pagecert)
			{
				$startcert = 0;
				$pagecert  = 1;
			}
			else {
				$startcert = ($pagecert - 1) * $count_cert; 
			}
			
	$prevPage = $pagecert-1;
	$nextPage = $pagecert+1;		
			
	$query = $con->prepare('SELECT * FROM `certificacion`');
			 $query->execute();
	$conteoCertificado = $query->fetchAll(PDO::FETCH_OBJ);
	$total = count($conteoCertificado);
	$total_pages = ceil($total / $count_cert);
	
	
	$query = $con->prepare('SELECT * FROM `certificacion` LIMIT ' . $startcert .','. $count_cert);
			 $query->execute();
	$certificado = $query->fetchAll(PDO::FETCH_OBJ);
	 
	?>
	<div class="advListBlock certificates">
		<div class="settingsPagination">
			<?php
			if($total_pages > 1)
				{
					echo $pagecert == 1 ? 1 : (($pagecert-1)*$count_cert)+1 ?> al <?php echo $pagecert == $total_pages ? $total : ($pagecert*$count_cert) ?> de <span><?php echo $total; ?></span>
							
						<a <?php echo $pagecert > 1 ? "href='http://tumall.do/administracion/solicitudes/page/$prevPage/'" : "" ?> class="pageCtrls prevPage"><i class="fa fa-angle-left"></i></a>
		
		
						<a <?php echo $pagecert < $total_pages ? "href='http://tumall.do/administracion/solicitudes/page/$nextPage/'" : "" ?> class="pageCtrls nextPage"><i class="fa fa-angle-right"></i></a>
							
				<?		
					
				}
			?>
		</div>
		<div class="advListHead">
			<span class="advListHeadItem advListItm advID">ID</span>
			<span class="advListHeadItem advListItm advDescr">Título</span>
			<span class="advListHeadItem advListItm advStatus">Estado</span>
		</div>
	<?php
	
	$oddCnt = 0;
	foreach($certificado as $c)
	{
		?> 
		<div class="advDet <? if($oddCnt%2==1){ echo "odd";} $oddCnt++  ?>">
			<span class="advListDetItem advListItm advID"><span class="helpText">ID: </span><?php echo $c->idcertificacion; ?></span>
			<a href="/administracion/certificado/<?php echo $c->idcertificacion ?>" class="advListDetItem advListItm advDescr"><span class="helpText">Titulo: </span><?php echo substr($c->texto,0,15); ?></a>
			<span class="advListDetItem advListItm advStatus"><span class="helpText">Status: </span><?php 
					if($c->estado == 'E')
					{
						echo "Pendiente";
					}  
					else if($c->estado == 'A')
					{
						echo "Aprobado";
					}
					else if($c->estado == 'N')
					{
						echo "Reprobado";
					}
			 ?></span>
			 
		</div>
		<?php
	}
	?>
	</div>
</div>
