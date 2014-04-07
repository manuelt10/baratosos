<?php 
require_once('../../phpfn/cndb.php');
$con = conection();
$query = $con->prepare('SELECT * FROM `categoria2` where idcategoria1 = ? order by descripcion');
$query->bindValue(1,$_POST["categoria1"]);
$query->execute();
$categoria2 = $query->fetchAll(PDO::FETCH_OBJ);
foreach($categoria2 as $ct2)
{
	?>
	<option value="<?php echo $ct2->idcategoria2 ?>"><?php echo $ct2->descripcion ?></option>
	<?php
} 
?>
