<?php 
session_start();
$session = $_SESSION;
session_write_close();
require_once('../../phpfn/cndb.php');
$con = conection();
	
	$query = $con->prepare('SELECT * FROM `usuario` where md5(correonuevo) = ?');
	$query->bindValue(1,$_POST["correoNuevo"]);
	$query->execute();
	$existeCorreo = $query->fetch(PDO::FETCH_OBJ);
	if(count($existeCorreo->idusuario) == 0)
	{
		
	
	$query = $con->prepare('SELECT * FROM `usuario` where idusuario = ?');
	$query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
	$query->execute();
	$usuario = $query->fetch(PDO::FETCH_OBJ);
	if(($session["autenticado"]) and
	($_POST["correoNuevo"] <> $usuario->correo) and
	filter_var($_POST["correoNuevo"], FILTER_VALIDATE_EMAIL))
	{
		$query = $con->prepare('UPDATE `usuario` 
								SET `correonuevo` = ?
								WHERE `idusuario` = ?');
	    $query->bindValue(1,$_POST["correoNuevo"]);
		$query->bindValue(2,$session["usuario"], PDO::PARAM_INT);
	    $query->execute();
		
		$to = $_POST["correoNuevo"];
		$subject = 'Cambio de correo TuMall'; 
		$message = '
			<html>
			<head>
			</head>
			<body>
				<div class="container">
					
					<div class="body">
						<span>Para finalizar el cambio de correo, por favor entra a este <a href="http://tumall.dophpfn/change_mail.php?id='. md5($session["usuario"]) .'&nm=' . md5($_POST["correoNuevo"]) . '">link</a></span>
					</div>
				</div>
			</body>
		</html>
			';
			
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: TuMall <no-reply@tumall.do>' .  "\r\n";
		mail($to,$subject,$message,$headers);
	}
}

?>
	