<?php 
if(!empty($_POST["correo"]) and filter_var($_POST["correo"], FILTER_VALIDATE_EMAIL))
{
require_once('cndb.php');
$con = conection();
$query = $con->prepare('SELECT * FROM `usuario` where correo = ?');
$query->bindValue(1,$_POST["correo"]);
$query->execute();
$usuario = $query->fetch(PDO::FETCH_OBJ);

$to = $_POST["correo"];
	$subject = 'Reinicio de contraseña TuMall'; 
	$message = '
		<html>
		<head>
		</head>
		<body>
			<div class="container">
				
				<div class="body">
					<span>Para reiniciar su contraseña, por favor entra a este <a href="http://tumall.do/restaurarpass.php?id='. md5($usuario->idusuario) .'&e='. md5($usuario->correo) .'&cd=' . md5($usuario->fecha_creacion) . '">link</a></span>
				</div>
			</div>
		</body>
	</html>
		';
		
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: TuMall <no-reply@tumall.do>' .  "\r\n";
	mail($to,$subject,$message,$headers);
	header('location: ../mensaje.php');
	
}
header("location: ../restend");

?>