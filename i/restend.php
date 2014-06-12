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
<script>
	$(document).ready(function () {
	    // Handler for .ready() called.
	    window.setTimeout(function () {
	        location.href = "http://tumall.do/home";
	    }, 5000)
	});
</script>

<div class="registerWrapp loginWrapp">
	
	<a href="/home" class="regLogo"><img src="/images/logo-min-NS.png" alt="Logo TuMall" class="replace2X" /></a>
	
	<h2 class="welcomMsg">Revise el Correo</h2>

	<div class="registerForm restore">
		<span class="fldLbl">Se ha enviado un correo para continuar con la recuperación de la contraseña</span>
	</div>
</div>
<?php 
	require_once('templates/fooOthers.php');
?>