<?php 
session_start();
$session = $_SESSION;
unset($_SESSION["error"]);
session_write_close();
if($session["autenticado"])
{
	header("location: home.php");
}
require_once('phpfn/cndb.php');
$con = conection();
/*$query = $con->prepare('SELECT * FROM `provincia`');
$query->execute();
$provincias = $query->fetchAll(PDO::FETCH_OBJ);*/
require_once('templates/headExtras.php');
?>

<div class="registerWrapp">

	<a href="home" class="regLogo"><img src="images/logo-min-NS.png" alt="Logo TuMall" class="replace2X" /></a>
	
	<h2 class="welcomMsg">Registrate!</h2>
	
	<form id="signUpFormWrapper" class="registerForm register" method="post" action="phpfn/i_usuario.php">
		<!-- <input id="usrTypeNorm" type="radio" name="tipoUsuario" value="1" checked class="tipoUsuario"><label class="fldLbl radLbl" for="usrTypeNorm">Normal:</label> -->
		<!--<input id="usrTypeStore" type="checkbox" name="tipoUsuario" value="2" class="tipoUsuario"><label class="fldLbl radLbl" for="usrTypeStore">Quiero una tienda en tuMall:</label>-->
		<label class="fldLbl">Nombre: </label><input type="text" name="nombre" class="txtField nameFld" placeholder="Nombre">
		<?php echo $session["error"]==1 ? '<span class="error-label validation-label">Es necesario el nombre</span>' : "" ?>
		<label class="fldLbl">Correo: </label><input type="email" name="correo" class="txtField mailFld" placeholder="Correo electrónico">
		<?php echo $session["error"]==2 ? '<span class="error-label validation-label">Es necesario el correo</span>' : "" ?>
		<?php echo $session["error"]==8 ? '<span class="error-label validation-label">El correo no tiene formato valido</span>' : "" ?>
		<label class="fldLbl">Contraseña: </label><input type="password" name="contra1" class="txtField passFld" placeholder="Contraseña">
		<label class="fldLbl">Repetir Contraseña: </label><input type="password" name="contra2" class="txtField pass2Fld" placeholder="Repetir Contraseña">
		<?php echo $session["error"]==3 ? '<span class="error-label validation-label">Las contraseñas son necesarias</span>' : "" ?>
		<?php echo $session["error"]==4 ? '<span class="error-label validation-label">Las contraseñas son necesarias</span>' : "" ?>
		<?php echo $session["error"]==5 ? '<span class="error-label validation-label">Las contraseñas no concuerdan</span>' : "" ?>
		<?php echo $session["error"]==6 ? '<span class="error-label validation-label">Usuario ya existe</span>' : "" ?>	
	    <label class="fldLbl">Telefono: </label><input type="text" class="txtField phoneFld" name="telefono" placeholder="Teléfono">
	    <?php /*
	    <div class="additionalInformation" style="display:none">
	        <label class="fldLbl">Nombre tienda: </label><input type="text" class="txtField storeFld" name="nombreTienda" placeholder="Nombre de tu tienda">
	        <?php echo $session["error"]==6 ? '<span class="error-label validation-label">Es necesario llenar el campo de la tienda</span>' : "" ?>
	    </div>
	    */ ?>
		<button type="submit" class="formActionBtn registerBtn">Crear cuenta</button>
	</form>
	
	<a href="accesar" class="loginHelpLinks">¿Ya tienes una cuenta?</a>
</div>

<?php /*
 * 
<script>
	
	if($('.tipoUsuario').prop('checked')){
		$('.additionalInformation').show();
	}
	

    $('.tipoUsuario').click(function(){
       if(!$(this).prop('checked'))
           {
               $('.additionalInformation').hide();
           }
       else
           {
               $('.additionalInformation').show();
           }
               
    });
</script>
 */?>
<script>
	$('.signFormText').blur(function(){
				
		if($(this).val().trim() === ""){
			$('.error-label validation-label').remove();
			$(this).parent('.fieldWrapper').append('<i class="error-arrow icon-circle-arrow-left"></i>');
			$('.errorWrapper').append('<span class="error-label validation-label">Marked fields cannot be left blank.</span>');
		}
		
		else{
			$('.error-label validation-label').remove();
			$(this).siblings('.error-arrow').remove();
		}
	});
			
		
/*
			$('.emailAdd').blur(function(){
				$('.error-label validation-label').remove();
				$(this).siblings('.error-arrow').remove();
				var email = $(this).val();
				$.ajax({
					type: "POST",
					url: "functions/check_email.php",
					data: {mail : email}
				}).done(function(html){
					
					if(html !== ""){
						$('.emailField').append('<i class="error-arrow icon-circle-arrow-left"></i>');
						$('.errorWrapper').append(html);
					}
				});
			});
*/

	$('#signUpFormWrapper').submit(function(validateForm){
			var cont = 0,
					passCnt = 0;
				$('.error-arrow').remove();
				$(this).find('.signFormText').each(function(){
					
					if($(this).val().trim() === ""){
						$(this).parent('.fieldWrapper').append('<i class="error-arrow icon-circle-arrow-left"></i>');
						
						cont++;
						
					}
					
					else if($('.signUpPass2').val() !== $('.signUpPass').val()){
						$('.signUpPass2').parent('.fieldWrapper').append('<i class="error-arrow icon-circle-arrow-left"></i>');
						passCnt++;
					}
					
					else{
						$(this).siblings('.error-arrow').remove();
					}
					
				});
				
				
				if(cont > 0 || passCnt > 0){
					$('.error-label validation-label').remove();
										
					if(passCnt > 0){
						$('.errorWrapper').append('<span class="error-label validation-label">Passwords do not match.</span>');
						passCnt = 0;
					}
					
					else{
						$('.errorWrapper').append('<span class="error-label validation-label">Marked fields cannot be left blank.</span>');
						cont = 0;
					}
						
					return false;
					
				}				
	});
</script>
<?php 
require_once('templates/foo.php');
?>