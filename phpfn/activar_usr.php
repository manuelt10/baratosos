<?php 
require_once('cndb.php');
$con = conection();
$query = $con->prepare('update `usuario` set activo = 1 where md5(idusuario) = ?');
$query->bindValue(1,$_GET["id"]);
$query->execute();
header("location: ../accesar");
?>