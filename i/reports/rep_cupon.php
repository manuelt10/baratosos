<?php
#cupones_usuarios_compra
session_start();
    $session = $_SESSION;
session_write_close();
if($session['autenticado'])
{
	require_once('../phpfn/mysqlManager.php');
	$db = new mysqlManager();
	$cup = $db->selectRecord('cupones_usuarios_compra',NULL,Array('idcupon_compras' => $_GET["id"]));
	$cupon = $db->selectRecord('cupon',NULL,Array('idcupon' => $cup->data[0]->idcupon));
	require_once('../templates/headExtras.php');
   ?>
   

		<div class="printWrap">

			<a href="/home" class="regLogo"><img src="/images/logo-min-NS.png" alt="Logo TuMall" class="replace2X" /></a>
			<h2 class="welcomMsg">Cupones Tu Mall</h2>
			
			<h3 class="mainHeading">Datos del Cupón</h3>	
			<h4 class="subHeading"><? echo $cupon->data[0]->titulo; ?></h4>
			<p>Paga $<?php echo number_format($cup->data[0]->precio_oferta,2) ?> en vez de $
<?php echo number_format($cup->data[0]->precio_normal,2) ?> (<?php echo number_format((($cup->data[0]->precio_normal - $cup->data[0]->precio_oferta)*100)/$cup->data[0]->precio_normal,2)?> % de descuento)</p>
			
			<span class="couponResumeID">Código: <?php echo $cup->data[0]->codigo ?> </span>
						
			<span>Reservado por: <?php echo $cup->data[0]->nombre  ?></span>
			<br />
			
			
			<span>Publicado el: <?php  echo $cup->data[0]->dia_publicacion ?></span><br>
			<span>Canjeable hasta el: <?php $cup->data[0]->dia_finalizacion  ?></span>
			<br />
			<br />
		
			<h4 class="subHeading">Descripción</h4>
			<p><?php echo $cup->data[0]->caracteristicas  ?></p>
			<br />
			
			<h4 class="subHeading">Dirección y Contacto</h4>
			<p><?php echo $cup->data[0]->contacto ?></p>
			
			<h3 class="mainHeading">Condiciones generales</h3>
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
    }

?>

	</div> <!-- close body -->
</div> <!-- close container -->
</body>
</html>