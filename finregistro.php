<?php 

session_start();
if(!empty($_SESSION["usuarioReal"]))
{
	$_SESSION["usuario"] = $_SESSION["usuarioReal"];
	unset($_SESSION["usuarioReal"]);
}
$session = $_SESSION;
unset($_SESSION["error"]);
session_write_close();
require_once('phpfn/stringmanager.php');
require_once('phpfn/cndb.php');
$con = conection();
$sM = new stringManager();
$query = $con->prepare('SELECT * FROM `usuario` where idusuario = ?');
$query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
$query->execute();
$usuario = $query->fetch(PDO::FETCH_OBJ);
require_once('templates/headExtras.php');
?>

<div class="sucessSignWrap">
	<img class="succesLogo" src="/images/logo-min-NS@2x.png" alt="Logo Tu Mall" title="Logo Tu Mall" width="250" />
	<h1>Gracias por registrarte en Tu Mall!</h1>
	<p class="successSignMsg">Ahora, revisa tu correo para finalizar el registro. Si no recibiste el correo, recuerda buscar en tu bandeja de "Spam" o "Junk".</p>
</div>
<?php 

	require_once('templates/foo.php');
	
?>