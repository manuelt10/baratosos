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
require_once('phpfn/mysqlManager.php');
$con = conection();
$sM = new stringManager();
$query = $con->prepare('SELECT * FROM `usuario` where idusuario = ?');
$query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
$query->execute();
$usuario = $query->fetch(PDO::FETCH_OBJ);
require_once('templates/headgeneral.php');
$db = new mysqlManager();
$cupon_usr = $db->selectRecord('cupon_compras',NULL,Array('idusuario' => $session["usuario"], 'idcupon' => $_GET["id"], 'fecha_creacion' => '%' . date("Y-m-d") . '%'));
$cupon = $db->selectRecord('cupon',NULL,Array('idcupon' => $cupon_usr->data[0]->idcupon));
?>

<div class="genContentWrap group cupons-wrap"> 
	<p class="coupEndGreeting">Gracias por reservar tu cupón! Imprime cada cupón o presenta el código en el establecimiento de intercambio:</p>
	<br />
	<h2 class="couponResumeName"><span class="couponResumeQty">[<? echo $cupon_usr->rowcount; ?>] x</span> <? echo $cupon->data[0]->titulo; ?></h2>
	<?php 
	foreach($cupon_usr->data as $c){
		?>
		<div class="couponResumeWrap">
			<span class="couponResumeID"><?php echo $c->codigo ?></span>
			<a class="printBtn actionBtn" target="_blank" href="/reports/rep_cupon.php?id=<?php echo $c->idcupon_compras ?>">Versión para impresión</a>
		</div>
		<?php
	}
	?>
	
	<p class="coupEndSecondary">Puedes volver a ver esta información, y la lista de tus cupones, dando click en "<a href="/cliente/fav">Mi cuenta</a>" y luego en "<a href="/cliente/cupon">Cupones</a>". Ver <a href="/cliente/cupon">mis cupones</a>.</p>
	
	<br />

	<h2 class="mainHeading">Condiciones</h2>
	<ul class="coupEndSecondary">  
		<li>Cada código de canje es equivalente a la disponibilidad de una oferta para canjear.</li>
		<li>La oferta debe ser canjeada en el plazo establecido en la descripción de la oferta.</li>
		<li>Para realizar el canje de la oferta, debes presentar el código de canje ya sea físico o digital.</li>
		<li>Darle uso comercial a las ofertas viola nuestras políticas de uso y resultaría en la suspensión del usuario.</li>
		<li>En caso de que desees regalar el cupón, solo tienes que entregarle el código a la persona deseada. El usuario no podrá hacer uso del cupón regalado una vez entregado y canjeado el código.</li>
		<li>Solo se debes reservar ofertas que realmente quieres canjear, reservar ofertas y nunca canjearlas puede ser sancionado con la suspensión de tu usuario.</li>
	</ul>

</div>

<?php 
	require_once('templates/foo.php');
?>