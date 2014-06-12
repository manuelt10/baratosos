<?php 
session_start();
if(!empty($_SESSION["usuarioReal"]))
{
	$_SESSION["usuario"] = $_SESSION["usuarioReal"];
	unset($_SESSION["usuarioReal"]);
}
unset($_SESSION["buscarTienda"]);
$session = $_SESSION;
unset($_SESSION["error"]);
session_write_close();
	require_once('phpfn/cndb.php');
	$con = conection();
	#$_GET["buscar"] = $session["buscarLista"];
	
	$query = $con->prepare('SELECT * FROM `usuario` where idusuario = ?');
        $query->bindValue(1,$session["usuario"]);
        $query->execute();
	$usuario = $query->fetch(PDO::FETCH_OBJ);


	require_once('templates/headgeneral.php');
        
?>
			<div class="genContentWrap group">
				<?php 
				if(!empty($_GET["buscar"]))
				{
					require_once('busqueda/lista.php');
				}
				else if (!empty($_GET["categoria1"]) or !empty($_GET["categoria2"]))
				{
					require_once('busqueda/lista.php');
				}
				else
				{
					require_once('busqueda/categorias.php');
				}
				echo "<script>console.log('". $_GET["buscar"] ."');</script>";
				?>	
					
			</div>
<?php 
	require_once('templates/foo.php');

?>