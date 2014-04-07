<?php 
session_start();
    $session = $_SESSION;
session_write_close();
require_once('../../phpfn/cndb.php');
$query2 = $con->prepare('SELECT `imagen` FROM `img_temp_caractprod` where `idusuario` = ?');
            $query2->bindValue(1,$session["usuario"], PDO::PARAM_INT);
            $query2->execute();
            $imagenes = $query2->fetchAll(PDO::FETCH_OBJ);
foreach($imagenes as $imgs)
{
	?>
	<div>
		<input type="hidden" class="idImageCaractProductoVal" value="<?php echo $imgs->imagen ?>">
		<img class="imageCaractProducto" src="images/c_prod/thumb150/<?php echo $imgs->imagen ?>">
	</div>
	<?php
}
?>