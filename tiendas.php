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
require_once('templates/headgeneral.php');

$query = $con->prepare("SELECT * FROM `anunciopub`
					WHERE  fecha_creacion + INTERVAL duracion DAY >= curdate()
					and terminado = 0
					and posicion like 'banner-top'
					order by rand()
					limit 0,3");
					$query->execute();
					$anuncio = $query->fetchAll(PDO::FETCH_OBJ);
?>

	<?php 
	if(!empty($anuncio[0]->image))
	{
		?>
		<div class="adTop ads">
			<a href="<?php echo $anuncio[0]->link ?>"><img src="images/publicidad/<?php echo $anuncio[0]->image ?>"></a>
		</div>
		<?php 
	}
	?>

<div class="storeList">
	
	<h1 class="storeListHeading">Listado de tiendas en Tu Mall</h1>
	
	<?php 

	$query = $con->prepare('SELECT distinct substr(nombretienda, 1,1)  as letra FROM `usuario` 
							WHERE `idtipousuario` = 2
							and activo = 1
							and suspendido = 0
							and baneado = 0
							order by 1 asc');
	$query->execute();
	$letrasTiendas = $query->fetchAll(PDO::FETCH_OBJ);
	foreach($letrasTiendas as $lT)
	{
		?>
		<div class="listWrapper">
			<h2 class="leadInitial"><?php echo $lT->letra ?></h2>
			<?php 
			$query = $con->prepare('SELECT idusuario, nombretienda FROM `usuario` 
								WHERE `idtipousuario` = 2
								AND `nombretienda` like :nombretienda
								AND idusuario not in (2,4)
								and activo = 1
								and suspendido = 0
								and baneado = 0
								order by 2 asc');
			$query->bindValue(':nombretienda', "$lT->letra%");
			$query->execute();
			$tiendas = $query->fetchAll(PDO::FETCH_OBJ);
			foreach($tiendas as $t)
			{
				$tienda_url = $sM->remove_accents($t->nombretienda);
				$tienda_url = str_replace("-", " ", $tienda_url);
				$tienda_url = preg_replace('/\s\s+/', ' ', $tienda_url);
				$tienda_url = str_replace(" ", "-", $tienda_url);
				$tienda_url = strtolower($tienda_url);
				$tienda_url = preg_replace('/[^A-Za-z0-9\-]/', '', $tienda_url);
				?>
				<a class="listStoreName" href="<?php echo $tienda_url ?>-<?php echo $t->idusuario ?>/"><?php echo $t->nombretienda; ?></a>
				<?php
			}
			?>
		</div>
		<?php
	}
	?>
	
	
</div>
<div class="adsWrap">
		<?php 
 	$query = $con->prepare("SELECT * FROM `anunciopub`
							WHERE  fecha_creacion + INTERVAL duracion DAY >= curdate()
							and terminado = 0
							and posicion like 'banner-right'
							order by rand()
							limit 0,3");
							$query->execute();
							$anuncio = $query->fetchAll(PDO::FETCH_OBJ);
		?>
		
			<?php 
			if(!empty($anuncio[0]->image))
			{
				?>
				<a class="top ads" href="<?php echo $anuncio[0]->link ?>"><img src="images/publicidad/<?php echo $anuncio[0]->image ?>"></a>
				<?php 
			}
			?>
		
		
			<?php 
			if(!empty($anuncio[1]->image))
			{
				?>
				<a class="mid ads" href="<?php echo $anuncio[1]->link ?>"><img src="images/publicidad/<?php echo $anuncio[1]->image ?>"></a>
				<?php 
			}
			?>
		
		
			<?php 
			if(!empty($anuncio[2]->image))
			{
				?>
				<a class="bottom ads" href="<?php echo $anuncio[2]->link ?>"><img src="images/publicidad/<?php echo $anuncio[2]->image ?>"></a>
				<?php
			}
			?>
		
	</div>

<?php 

	require_once('templates/foo.php');
	
?>