<?php 
require_once('../../phpfn/cndb.php');
$con = conection();
$query = $con->prepare('SELECT * FROM `categoria3` where idcategoria2 = ? order by descripcion');
$query->bindValue(1,$_POST["categoria2"]);
$query->execute();
$categoria3 = $query->fetchAll(PDO::FETCH_OBJ);
foreach($categoria3 as $ct3)
{
	?>
	<option value="<?php echo $ct3->idcategoria3 ?>"><?php echo $ct3->descripcion ?></option>
	<?php
} 
?>
