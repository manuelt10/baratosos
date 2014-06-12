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
	
	<img class="businessLogo" src="images/logo-min-NS.png" alt="Logo Tu Mall" title="Logo Tu Mall" />
	
	<h2 class="businessInfoSubHead">Acerca de Tu Mall</h2>
	<p>El primer Mall Digital de la ciudad de Santo Domingo, consiste en una plataforma interactiva en donde usuarios podrán tener acceso a sus marcas y establecimientos favoritos desde la comodidad de sus pantallas, también brinda la posibilidad de crear su propia tienda virtual.</p>
	
	<h2 class="businessInfoSubHead">¿Qué busca Tu Mall?</h2>
	<p>Ser el enlace directo entre el comercio y el consumidor, de una forma innovadora y sofisticada.</p>
	
	<h2 class="businessInfoSubHead">Fundamentos de Tu Mall</h2>
	<ul>
		<li>Responsabilidad</li>
		<li>Honestidad</li>
		<li>Servicio</li>
		<li>Calidad</li>
	</ul>
</div>

<?php 
	require_once('templates/foo.php');
	
?>