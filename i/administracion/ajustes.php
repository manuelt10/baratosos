<div class="topBar">
	<h2 class="settHead">Ajustes de cuenta</h2>
</div>
<div class="settWrap editOptionsWrap">
	
	<div class="tabNavWrap transTw">
		<span class="tabItm optTab password active" data-tab="optPassSec">Contraseña</span>
	</div>
	<form class="optionForm tabWrap editOptionsSec transTw optPassSec" action="/administracion/aju_func/change_pass.php" method="post"> 
			<legend class="settLegend">Modificar contraseña</legend>
			<label class="settingLbl">Contraseña actual:</label>
			<br/>
			<input type="password" class="txtField mid transTw password old" name="clave">
			<br/>
			<label class="settingLbl">Nueva contraseña:</label>
			<br/>
			<input type="password" class="txtField mid transTw password one" name="nuevaclave">
			<br/>
			
			<label class="settingLbl">Repetir contraseña:</label>
			<br/>
			<input type="password" class="txtField mid transTw password two" name="nuevaclave2">
			<br/>
			<?php echo $session["error"] == 1 ? "<span class='validation-label error-label'>Datos incorrectos</span>" : " ";?>	
			<div class="ctrlBtnWrap">
				<span class="tabNavBack backBtn formActionBtn back">Atrás</span>
				<button type="submit" class="clearPass sendForm formActionBtn transTw">Cambiar contraseña</button>
			</div>
			
	</form> 
</div>	

<script>
	//shows corresponding form on click/tap
	$('.tabItm').click(function(){
		
		var tab = $(this).data('tab'),
			formHeight = $('.'+tab).height();
			
		$('.settWrap').css('min-height', formHeight+75);
		
		$('.tabItm').removeClass('active');
		$(this).addClass('active');
		
		
		$('.tabWrap').addClass('hidden').removeClass('shown');
		$('.'+tab).removeClass('hidden').addClass('shown');

		
		//mobile specific events
		//separated from prev func to avoid confusion
		$(this).parent().addClass('hidden');
		$('.tabWrap').removeClass('shown');
		$('.'+tab).addClass('shown');
	});
	
	$('.backBtn').click(function(){
		$('.tabWrap').removeClass('shown');
		$('.tabNavWrap').removeClass('hidden');
	});

	
</script>