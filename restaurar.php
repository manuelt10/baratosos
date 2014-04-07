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
	
	<h2 class="welcomMsg">Restaurar contraseña</h2>

	<form method="post" class="registerForm restore" action="phpfn/restaurar_pass.php">
		<label class="fldLbl">Usuario/Correo: </label>
		<input type="text" name="correo" class="txtField mailFld" placeholder="Correo de login">
		<button type="submit" class="formActionBtn registerBtn">Restaurar contraseña</button>
	</form>
</div>
<?php 
	require_once('templates/fooOthers.php');
?>