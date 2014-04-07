<?php 
$query = $con->prepare('SELECT * FROM `certificacion` where idcertificacion = ?');
		 $query->bindValue(1,$_GET["id"], PDO::PARAM_INT);
		 $query->execute();
$certificado = $query->fetch(PDO::FETCH_OBJ);
$query = $con->prepare('SELECT * FROM `usuario` where idusuario = ?');
		 $query->bindValue(1,$certificado->idusuario, PDO::PARAM_INT);
		 $query->execute();
$usr = $query->fetch(PDO::FETCH_OBJ);
?>
<div class="topBar">
	<h2 class="settHead">Revisar solicitud</h2>
</div>
	<div class="settWrap requestFormWrap adminReviewWrap">
		<div class="requestForm shown">
		
		<span class="settingLbl requLbl">Tienda: <?php echo $usr->nombretienda ?></span>
		<br />
		<span class="settingLbl requLbl">Propietario: <?php echo $usr->nombre ?></span>
		<br />
		<span class="settingLbl requLbl">Telefono Personal: <?php echo $usr->telefono1 ?></span>
		<br />
		<span class="settingLbl requLbl">Telefono Adicional: <?php echo $usr->telefono2 ?></span>
		<br />
		<br />
		<label class="settingLbl requLbl">Descripción: </label>
		<br />
		<textarea class="txtField transTw requDescr largeField"><?php echo nl2br($certificado->texto) ?></textarea>
		<br />
		
		
		<span class="status settingLbl requLbl">Status: <?php 
				if($certificado->estado == 'E')
				{
					echo "Pendiente";
				}
				else if($certificado->estado == 'A')
				{
					echo "Aprobado";
				}
				else if($certificado->estado == 'N')
				{
					echo "Reprobado";
				}
		 ?>
		 </span>
		
		
		<div class="ctrlBtnWrap requ-CtrlBtnWrap">
			<a class="formActionBtn back transTw" href="administracion/solicitudes">Volver</a>
			<button type="button" class="changeStat formActionBtn cancel transTw" value="N">Reprobar</button>
			<button type="button" class="changeStat formActionBtn sendForm transTw" value="A">Aprobar</button>
		</div>
	</div>
</div>
<script>
	$('.changeStat').click(function()
	{
		var id = <?php echo $_GET["id"]?>;
		var stat = $(this).val();
		$.ajax({
			type : "POST",
			url : "administracion/cert_func/change_certificate_status.php",
			data: {idCertif : id, status : stat}
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
