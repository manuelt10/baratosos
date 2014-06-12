<div class="topBar">
	<h2 class="settHead">Ajustes de cuenta</h2>
</div>
<div class="settWrap editOptionsWrap">

	<div class="tabNavWrap transTw">
		<span class="tabItm optTab info active" data-tab="optInfoSec">Información personal</span>
		<span class="tabItm optTab email" data-tab="optEmailSec">Email</span>
		<span class="tabItm optTab password" data-tab="optPassSec">Contraseña</span>
		<span class="tabItm optTab suspend" data-tab="optSuspendSec">Suspender cuenta</span>
	</div>
	<form class="optionForm tabWrap editOptionsSec transTw optInfoSec" action="/cliente/ajustes_func/update_name_phone.php" method="post">
		<legend class="settLegend">Información</legend>
		<label class="settingLbl">Nombre y apellidos</label>
		<br/>
		<input type="text" class="txtField mid transTw personalName" value="<?php echo $usuario->nombre ?>" name="nombre">
		<br/>
		
		<label class="settingLbl">Teléfono</label>
		<br/>
		<input type="text" class="txtField mid transTw personalPhone" value="<?php echo $usuario->telefonopersonal ?>" name="telefonopersonal">
		<br/>
		
		<div class="ctrlBtnWrap">
			<span class="tabNavBack backBtn formActionBtn back">Atrás</span>
			<button type="submit" class="sendForm formActionBtn transTw">Guardar cambios</button>
		</div>
		
	</form>
	<form class="optionForm tabWrap editOptionsSec transTw optEmailSec hidden" action="/cliente/ajustes_func/change_mail.php" method="post">
		<legend class="settLegend">Cambiar correo</legend>
		
		<div class="infoBox opts-InfoBox transTw">
			<h3 class="requLegend">Nota:</h3>
			<p>Se enviará un correo de confirmación a la dirección nueva, deberás seguir el enlace en el correo para finalizar el proceso.</p>
		</div>
		
		<label class="settingLbl">Correo nuevo</label>
		<br/>
		<input type="text" class="txtField mid transTw personalMail" name="correoNuevo">
		<br/>
		<div class="mailErrorVerification"></div>
		
		<div class="ctrlBtnWrap">
			<span class="tabNavBack backBtn formActionBtn back">Atrás</span>
			<button type="submit" class="sendForm formActionBtn transTw">Cambiar correo</button>
		</div>
		
	</form>
	<form class="optionForm tabWrap editOptionsSec transTw optPassSec hidden" action="/cliente/ajustes_func/change_pass.php" method="post"> 
		<legend class="settLegend">Modificar contraseña</legend>
		<label class="settingLbl">Contraseña actual</label>
		<br/>
		<input type="password" class="txtField mid transTw password old" name="clave">
		<br/>
		
		<label class="settingLbl">Nueva contraseña</label> 
		<br/>
		<input type="password" class="txtField mid transTw password one" name="nuevaclave">
		<br/>
		
		<label class="settingLbl">Repetir contraseña</label>
		<br/>
		<input type="password" class="txtField mid transTw password two" name="nuevaclave2">
		<br/>
		
		<div class="ctrlBtnWrap">
			<span class="tabNavBack backBtn formActionBtn back">Atrás</span>
			<button type="submit" class="clearPass sendForm formActionBtn transTw">Cambiar contraseña</button>
		</div>
	</form> 
	<form class="tabWrap editOptionsSec transTw optSuspendSec hidden" action="/cliente/ajustes_func/suspend_user.php" method="post">
		<legend class="settLegend">Suspender cuenta</legend>
		
		<div class="infoBox opts-InfoBox transTw">
			<h3 class="requLegend">Advertencia</h3>
			<p>Suspender tu cuenta colocará todos tus artículos como inactivos. Tu tienda, anuncios, ofertas, artículos, y cualquier otra información no aparecerán en ninguna sección de tuMall. Tus datos no se eliminarán sino hasta después de pasados 14 días sin iniciar sesión en tuMall.</p>
		</div>
		
		<label class="settingLbl">Contraseña</label>
		<br/>
		<input type="password" class="txtField mid transTw password old" name="clave">
		<br/>
		
		<div class="ctrlBtnWrap">
			<span class="tabNavBack backBtn formActionBtn back">Atrás</span>
			<button type="submit" class="clearPass sendForm formActionBtn transTw">Suspender cuenta</button>
		</div>
	</form>
</div>

<script src='/Scripts/jquery.form.min.js'></script>

<script>
	$('.personalMail').keyup(function()
	{
		var cor = $(this).val();
		$.ajax({
			type: "POST",
			url : "/phpfn/verify_mail.php",
			data: {correo : cor}
		}).done(function(html)
		{
			$('.mailErrorVerification').html('<span class="error-label">' + html  + '</span>');
		})
	})
</script>

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

<script> //shows success message when sending a form
$('.optionForm').submit(function(event) { 
   
   function addHidden(){
	   $('.successMsg').addClass('hidden');
   }
	  
   function remHidden(){
	   $('.successMsg').removeClass('hidden');
	   
	   setTimeout(addHidden, 5000);
   }

   function saveSuccess(){
	   $('.successMsg').remove();
	   $('.settWrap').append('<span class="successMsg popUpMsg hidden transTw">Los cambios han sido guardados</span>');
	   
	  setTimeout(remHidden, 1);
   }
   
   var options = {
   		success: saveSuccess
   		};
   
   $(this).ajaxSubmit(options); 
   
   return false;
});
</script>