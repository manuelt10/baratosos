<?php 
require_once('cndb.php');
$con = conection();

$query = $con->prepare('SELECT * FROM `usuario` where correo = ?');
$query->bindValue(1,$_POST["correo"]);
$query->execute();
$existeCorreo = $query->fetch(PDO::FETCH_OBJ);
if(count($existeCorreo->idusuario))
{
	echo "<span class='validation-label error-label'>Correo ya existe.</span>";
}
else if(!filter_var($_POST["correo"], FILTER_VALIDATE_EMAIL))
{
	echo "<span class='validation-label error-label'>No es un formato válido.</span>";
}
else
	{
		echo "<span class='validation-label valid-label'>Correo válido.</span>";
	}

?>