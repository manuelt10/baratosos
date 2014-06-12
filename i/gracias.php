<?php 

require_once('i/phpfn/cndb.php');
$con = conection();
$query = $con->prepare('update `concurso` set verificado = 1 where md5(idconcurso) = ?');
$query->bindValue(1,$_GET["id"]);
$query->execute();

?>
<h2>Gracias por registrarte!</h2>
<?php  echo 'hola' . $_GET["id"];?>