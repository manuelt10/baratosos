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
if(!$session["autenticado"])
{
	#header("location: accesar");
	?>
	<script>
		window.open('/accesar','_self');
	</script>
	<?php
}
?>

<?php 
require_once('phpfn/cndb.php');
$con = conection();
$query = $con->prepare('SELECT * FROM `usuario` where idusuario = ?');
$query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
$query->execute();
$usuario = $query->fetch(PDO::FETCH_OBJ);
if($usuario->idtipousuario <> 3)
{
    ?>
	<script>
		window.open('/home','_self');
	</script>
	<?php
}
require_once('templates/headOthers.php');
?>
				
	<div class="backendWrapper group">
		<div class="backendButtonsPane">
            <div class="profilePicWrapper">
				<div class="userProfilePic transTw">
					<img src="/images/resources/userPNG-admin-100.png" alt="No user picture" />
				</div>
				<span>Hey, Admin!</span>
			</div>
			<a href="/administracion/usuarios" class="settMenuItm adminUsrs <?php if(strpos($URL, '/administracion/usuarios') === 0 || strpos($URL, '/administracion/usr') === 0){ echo 'active'; } ?>">Usuarios</a>        
			<a href="/administracion/solicitudes" class="settMenuItm usrAdvert <?php if(strpos($URL, '/administracion/solicitudes') === 0){ echo 'active'; } ?>">Solicitudes</a>
			<a href="/administracion/publicidad" class="settMenuItm usrAdvert <?php if(strpos($URL, '/administracion/publicidad') === 0){ echo 'active'; } ?>">Publicidad</a>
			<a href="/administracion/galeria" class="settMenuItm usrAdvert <?php if(strpos($URL, '/administracion/galeria') === 0){ echo 'active'; } ?>">Galeria</a>
			<a href="/administracion/cupones" class="settMenuItm usrStore <?php if(strpos($URL, '/administracion/cupones') === 0){ echo 'active'; } ?>">Cupones</a>
			<a href="/administracion/ajustes" class="settMenuItm usrPrefs <?php if(strpos($URL, '/administracion/ajustes') === 0){ echo 'active'; } ?>">Ajustes</a>
			
		</div>
		<div class="rightPane">	
			<?php 
			if($_GET["form_name"] == 'usr' and !empty($_GET["id"]))
			{
				require_once('p/lista_producto1.php');
			}
			else if($_GET["form_name"] == 'prod' and !empty($_GET["prod_id"]) and !empty($_GET["usrid"]))
			{
				require_once('p/modificar_producto.php');
			}
			else if($_GET["form_name"] == 'galeria')
			{
				require_once('administracion/galeria.php');
			}
			else if($_GET["form_name"] == 'usuarios')
			{
				require_once('administracion/usuarios.php');
			}
			else if($_GET["form_name"] == 'cupones')
			{
				require_once('administracion/cupones.php');
			}
			else if($_GET["form_name"] == 'cuponesnuevo')
			{
				require_once('administracion/cuponesnuevo.php');
			}
			else if($_GET["form_name"] == 'cuponesmod')
			{
				require_once('administracion/cuponesmod.php');
			}
			else if($_GET["form_name"] == 'ajustes')
			{
				require_once('administracion/ajustes.php');
			}
			else if($_GET["form_name"] == 'solicitudes')
			{
				require_once('administracion/solicitudes.php');
			}
			else if($_GET["form_name"] == 'solicitudes1')
			{
				require_once('administracion/solicitudes_pub.php');
			}
			else if($_GET["form_name"] == 'solicitudes2')
			{
				require_once('administracion/solicitudes_off.php');
			}
			else if(($_GET["form_name"] == 'certificado') and !empty($_GET["id"]))
			{
				require_once('administracion/certificados.php');
			}
			else if(($_GET["form_name"] == 'oferta') and !empty($_GET["id"]))
			{
				require_once('administracion/oferta.php');
			}
			else if(($_GET["form_name"] == 'anuncio') and !empty($_GET["id"]))
			{
				require_once('administracion/oferta.php');
			}
			else if(($_GET["form_name"] == 'anuncios'))
			{
				require_once('administracion/anuncios.php');
			}
			else if ($_GET["form_name"] == 'publicidad')
			{
				require_once('administracion/publicidad.php');
			}
			else if ($_GET["form_name"] == 'tiendas')
			{
				require_once('administracion/tienda.php');
			}
			else
			{
				require_once('administracion/usuarios.php');
			}
			?>
		</div>  
		
	</div>

<?php 
require_once('templates/fooOthers.php');
?>