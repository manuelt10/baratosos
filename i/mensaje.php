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
<div>
	Se ha enviado un correo
</div>
<?php 
	require_once('templates/fooOthers.php');
?>