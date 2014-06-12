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
	
	<h1 class="businessInfoHeading">F.A.Q. de Tu Mall</h1>
	
	<p class="businessInfoMessage">Si llegaste aquí es porque tienes alguna pregunta o duda. En ésta sección encontrarás respuesta a las preguntas más frecuentes. Si lo que buscas no aparece aquí, no dudes en escribirnos a <a href="mailto:info@tumall.do">info@tumall.do</a>.</p>
	
	<h2 id="facilidades" class="faqTitle">¿Qué facilidades me brinda Tu Mall como tienda?</h2>
	<ul>
		<li>Publicación de toda su cartera de productos con su descripción, imágenes e información.</li>
		<li>Manejo personalizado de su tienda Virtual.</li>
		<li>Fácil acceso a publicidad masiva e interactiva.</li>
		<li>Control de su Inventario Digital.</li>
		<li>Interacción en tiempo real con los consumidores.</li>
	</ul>
	
	<h2 class="faqTitle" id="beneficios-Tienda">¿Qué beneficios me brinda Tu Mall como tienda?</h2>
	<ul>
		<li>Llevar su tienda a miles de usuarios alrededor de todo el país. </li>
		<li>Optimización de sus gastos en publicidad.</li>
		<li>Ahorro en sus gastos fijos.</li>
		<li>Incremento en sus ventas.</li>
		<li>Servicio Personalizado a sus clientes.</li>
	</ul>
	
	<h2 class="faqTitle" id="beneficios-Usuario">¿Y como usuario?</h2>
	<ul>
		<li>La posibilidad de encontrar todo lo que buscas con tan solo un click.</li>
		<li>Llevar una lista "Wishlist" de los artículos que más te gusten o necesites comprar.</li>
		<li>Información de todas las tiendas registradas en Tu Mall y acceso a ofertas y promociones dentro de Tu Mall.</li>
	</ul>
	
	<h2 class="faqTitle" id="costo">¿Qué costo tiene publicarse en Tu Mall?</h2>
	<p>No tiene ningún tipo de costo, registrarse y publicar tus artículos o servicios es totalmente gratis.</p>
	
	<h2 class="faqTitle" id="poder-vender">¿Puedo vender mis artículos a través de la plataforma?</h2>
	<p>Si, puede vender todos sus artículos a través de la misma.</p>
	
	<h2 id="limite-articulos" class="faqTitle" id="limite-articulos">¿Existe un límite de artículos dentro de la tienda virtual?</h2>
	<p>No existe límite de artículos que puedas publicar en tu tienda, ni tampoco existe un costo alguno para poder subir más artículos de lo normal. Todos los usuarios tienen la capacidad de publicar ilimitadamente sin costo adicional.</p>
	
	<h2 id="beneficios-tumall" class="faqTitle">¿Qué beneficios obtiene Tu Mall, si es gratis?</h2>
	<p>Tu Mall obtiene sus beneficios de la publicidad dentro de la página y demás valores agregados.</p>
	
	<h2 id="moneda" class="faqTitle">¿Qué tipo de monedas puedo utilizar para vender mis productos?</h2>
	<p>Actualmente puedes publicar tus artículos en pesos (RD$), dólares (US$) y Euros ¿Entiendes que debemos incluir otro tipo de moneda? Escríbenos a <a href="mailto:info@tumall.do">info@tumall.do</a>.</p>
	
	<h2 id="subir-articulos" class="faqTitle">¿Tengo que subir mis propios artículos a la plataforma, o Tu Mall lo hace?</h2>
	<p>Si, debes subir tus propios artículos a la plataforma, puedes hacerlo a través de tu PC o Smartphone. En caso de necesitar ayuda, puedes <a href="/contacto">contactarnos</a> y te ayudaremos.</p>
	
	<h2 id="manejar-cuenta" class="faqTitle">¿Podría contratar algún personal que maneje mi cuenta de Tu Mall?</h2>
	<p>Publicar un artículo en Tu Mall es muy sencillo, pero en caso de ser necesario podríamos suministrarle un encargado que se administrará de la actualización de su página, esto tendría un costo por gestión. Para más información, escríbanos a <a href="mailto:info@tumall.do">info@tumall.do</a> llámenos al 809-867-2230.</p>
	
	<h2 id="requisitos" class="faqTitle">¿Qué requisitos debo cumplir para poder vender en Tu Mall?</h2>
	<p>Que tu negocio, ya sea formal o independiente, realice ventas de forma regular y no de forma esporádica, para evitar así las ventas particulares.</p>
	
	<h2 id="como-vender" class="faqTitle">¿Cómo puedo vender en Tu Mall?</h2>
	<p>Primero <a href="/registro">regístrate</a> y, luego de validar tu cuenta, simplemente accede a "Mi cuenta", y desde allí ve a "Publicar". Sigue los pasos en pantalla e intenta proveer tanta información como puedas sobre tu artículo.</p>
	
	<h2 id="sobre-publicidad" class="faqTitle">¿Cómo es la publicidad en Tu Mall?</h2>
	<p>Hay varias formas de hacer tu publicidad en Tu Mall:</p> 
	<ul>
		<li>Existen los comunes "banners" o "ad spots" que colocamos en la parte lateral derecha de los resultados, y tiendas dentro de Tu Mall. Estos espacios son rotatorios y consisten de una imagen estática o animada (GIF, HTML5/CSS/JS).</li>
		<li>También utilizamos publicidad por artículo específico, a lo que llamamos "Ofertas". Las Ofertas aparecen debajo de los resultados de búsqueda, dentro de las tiendas, y en la descripción de los artículos. Las Ofertas aparecen como "Artículos destacados.</li>
		<li>Finalmente, hacemos publicidad directa de las tiendas, es decir, en el <a href="/home">landing page/home page</a> aparecerán hasta 6 tiendas destacadas, estos espacios son rotatorios.</li>
	</ul>
	<br />
	<p>Todos los tipos y espacios de publicidad son "alquilados" por un tiempo determinado. Los anuncios, ofertas, y certificaciones deben primero ser revisadas y aprobadas por un administrador.</p>
	
	<h2 id="costo-publicidad" class="faqTitle">¿Cuál es el costo de la publicidad en Tu Mall?</h2>
	<p>El costo depende del tipo de publicidad que se quiera realizar, y del tiempo que pretenda durar con la publicidad en el portal. Puede enviar su solicitud dando click en "Mi Cuenta", y luego a en "Publicidad", finalmente en "Agregar Solicitud", y luego elija el tipo de publicidad de desee enviar. También puede dar <a href="http://tumall.do/publicidad">click aquí</a>. O escribirnos a <a href="mailto:info@tumall.do">info@tumall.do</a>.</p>
	
	<h2 id="como-publicidad" class="faqTitle">¿Cómo envío mi publicidad a Tu Mall?</h2>
	<p>Puede enviar su solicitud dando click en "Mi Cuenta", y luego a en "Publicidad", finalmente en "Agregar Solicitud", y luego elija el tipo de publicidad de desee enviar. También puede dar <a href="http://tumall.do/publicidad">click aquí</a>. O escribirnos a <a href="mailto:info@tumall.do">info@tumall.do</a>.</p>
	
	<h2 id="sobre-CFT" class="faqTitle">¿Qué es la certificación Tu Mall o CFT?</h2>
	<p>Es la certificación que valida que una tienda virtual es 100% confiable y que los artículos publicados cumplen con las especificaciones descritas.</p>
	
	<h2 id="como-CFT" class="faqTitle">¿Cómo puedo certificarme con la CFT?</h2>
	<p>Puedes enviar una solicitud de certificación a través del panel de tienda, dando click en "Publicidad", y luego en "Agregar Solicitud", o bien, dando <a href="http://tumall.do/solicitud">click aquí</a>. También puedes solicitar tu CFT vía correo electrónico a <a href="mailto:info@tumall.do">info@tumall.do</a>.</p>
	
	<h2 id="beneficios-CFT" class="faqTitle">¿Qué beneficios gano si obtengo la CFT?</h2>
	<p>Entre los beneficios por los que opta está la seguridad y confianza que le transmitirá a sus consumidores a la hora de hacer alguna compra a través de su tienda virtual.
Recibe un ranking A+ por encima de otras tiendas no certificadas, y obtiene en los anuncios y ofertas que nos envíe para ser publicados. Además, un sello distintivo que la diferencia de las tiendas no certificadas, y capacidades de edición de artículos avanzadas, como un editor full HTML para poder dar un estilo característico y único a tus artículos.</p>
	
	<h2 id="costo-CFT" class="faqTitle">¿Tiene la CFT algún tipo de costo?</h2>
	<p>Sí, para más detalles <a href="/contacto">contáctanos a servicio al cliente</a>.</p>
	
	<h2 id="sobre-publicaciones" class="faqTitle">¿Qué tan confiable son las publicaciones?</h2>
	<p>Todas las publicaciones en la plataforma de Tu Mall tratan de ser lo más veraz posible, sin embargo, las publicaciones de las tiendas pertenecen exclusivamente a las mismas y Tu Mall no puede hacerse responsables de estas.</p>
	
	<h2 id="garantia-venta" class="faqTitle">¿Qué garantía brinda Tu Mall de la veracidad de los artículos?</h2>
	<p>Tu Mall sólo brinda garantía a las tiendas certificadas con la CFT.</p>
	
</div>


<?php 
	require_once('templates/foo.php');
	
?>