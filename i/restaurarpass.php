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

	<a href="/home" class="regLogo"><img src="/images/logo-min-NS.png" alt="Logo TuMall" class="replace2X" /></a>
	
	<h2 class="welcomMsg">Contraseña nueva</h2>
	
	<form method="post" class="registerForm restore"  action="/phpfn/resetear_pass.php?id=<?php echo $_GET["id"] ?>&e=<?php echo $_GET["e"] ?>&cd=<?php echo $_GET["cd"] ?>">
		<label class="fldLbl">Contraseña nueva: </label>
		<input type="password" name="pass1" class="txtField passFld" placeholder="Contraseña nueva">
		<label class="fldLbl">Repetir contraseña: </label>
		<input type="password" name="pass2" class="txtField passFld" placeholder="Repetir contraseña">
		<button type="submit" class="formActionBtn registerBtn">Restaurar</button>
	</form>
</div>
<?php 
	require_once('templates/fooOthers.php');
?>