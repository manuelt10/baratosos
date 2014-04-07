<?php 
session_start();
$session = $_SESSION;
unset($_SESSION["error"]);
session_write_close();
if($session["autenticado"])
{
	header("location: home.php");
}
require_once('templates/headExtras.php');
?>

<div class="registerWrapp loginWrapp">

	<a href="home" class="regLogo"><img src="images/logo-min-NS.png" alt="Logo TuMall" class="replace2X" /></a>
	
	<h2 class="welcomMsg">Bienvenido a Baratosos</h2>
	<form method="post" class="registerForm login" action="phpfn/acceso">
		<label class="fldLbl">Usuario: </label><input type="text" name="correo" class="txtField mailFld" placeholder="Correo electrónico">
		<label class="fldLbl">Contraseña: </label><input type="password" name="contrasena" class="txtField passFld" placeholder="Contraseña">
		<?php echo $session["error"]==1 ? '<span class="validation-label error-label ">Debe introducir correo y contraseña.</span>' : "" ?>
		<?php echo $session["error"]==2 ? '<span class="validation-label error-label ">Usuario o contraseña equivocado.</span>' : "" ?>
		<?php echo $session["error"]==3 ? '<span class="validation-label error-label ">Verifique su correo para activar su cuenta.</span>' : "" ?>
		<?php echo $session["error"]==4 ? '<span class="validation-label error-label ">Usted suspendió su cuenta, contacte al administrador,</span>' : "" ?>
		<?php echo $session["error"]==5 ? '<span class="validation-label error-label ">Su cuenta está suspendida, favor contactarnos a serviciocliente@tumall.do</span>' : "" ?>
		<button type="submit" class="formActionBtn registerBtn">Ingresar</button>
		
	</form>
	<a href="restaurar" class="loginHelpLinks">Olvidé mi contraseña</a>
	<a href="registro" class="loginHelpLinks">¿No tienes una cuenta? <span class="underline">Crea una</span></a>
</div>	



<?php 
	require_once('templates/foo.php');
?>