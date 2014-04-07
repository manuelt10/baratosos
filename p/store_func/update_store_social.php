<?php 
session_start();
$session = $_SESSION;
session_write_close();
if($session["autenticado"])
{
	require_once('../../phpfn/cndb.php');
	$con = conection();
	for($n = 0; $n < count($_POST["social"]); $n++)
	{
			$query = $con->prepare('SELECT * FROM `usuarioredes` WHERE `idredes_sociales` = ? and `idusuario` = ?');
			$query->bindValue(1, $_POST["social"][$n],PDO::PARAM_INT);
			$query->bindValue(2, $session["usuario"],PDO::PARAM_INT);
			$query->execute();
			$haveSocial = $query->fetchAll(PDO::FETCH_OBJ);
			if(!count($haveSocial))
			{
				$query = $con->prepare('INSERT INTO `usuarioredes`(`idredes_sociales`, `idusuario`, `nombre_red`) 
										VALUES (?,?,?)');
				$query->bindValue(1, $_POST["social"][$n],PDO::PARAM_INT);
				$query->bindValue(2, $session["usuario"],PDO::PARAM_INT);
				$query->bindValue(3, $_POST["descripcion"][$n]);
				$query->execute();
			}else
			{
				$query = $con->prepare('update `usuarioredes`
										SET `nombre_red` = ?
										WHERE `idredes_sociales` = ? AND `idusuario` = ?');
				$query->bindValue(1, $_POST["descripcion"][$n]);
				$query->bindValue(2, $_POST["social"][$n],PDO::PARAM_INT);
				$query->bindValue(3, $session["usuario"],PDO::PARAM_INT);
				$query->execute();	
			}
	}
}
?>