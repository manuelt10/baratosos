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

?>

<?php 
require_once('phpfn/cndb.php');
$con = conection();
$query = $con->prepare('SELECT * FROM `usuario` where idusuario = ?');
$query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
$query->execute();
$usuario = $query->fetch(PDO::FETCH_OBJ);
require_once('templates/headgeneral.php');
?>

<div class="businessInfoWrap">
	<h1 class="businessInfoHeading">Necesitas ayuda o quieres contactarnos?</h1>
	
	<p class="businessInfoMessage">En Tu Mall, nos tomamos muy en serio a nuestros clientes, por eso siempre estamos dispuestos a responder a cualquier duda o ayudar a resolver cualquier inconveniente con nuestro servicio.</p>
	
	<h2 class="businessInfoSubHead">Escríbenos a: </h2>
	<a href="mailto:info@tumall.do">info@tumall.do</a>
	
	<h2 class="businessInfoSubHead">Llámanos al:</h2>
	<span class="companyPhone">809-867-2230</span>
	
	<h2 class="businessInfoSubHead">O visítanos en:</h2>
	<p>Manzueta & Peña Group S.R.L, Av. Rómulo Betancourt 299, Plaza Madelta, Bella Vista.</p>
</div>

<?php 
	require_once('templates/foo.php');
	
?>