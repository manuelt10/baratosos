<?php 
session_start();
$session = $_SESSION;
session_write_close();
if($session["autenticado"])
{
	require_once('../../phpfn/cndb.php');
	$con = conection();
	
	$query = $con->prepare('SELECT * FROM `usuario` where idusuario = ?');
	$query->bindValue(1,$_POST["usr"], PDO::PARAM_INT);
	$query->execute();
	$usuario = $query->fetch(PDO::FETCH_OBJ);
	
	if($usuario->baneado == 1)
	{
		$ban = 0;
	}else
	{
		$ban = 1;
	}
	
	$query = $con->prepare('UPDATE `usuario` set baneado = ? where idusuario = ?');
	$query->bindValue(1,$ban, PDO::PARAM_INT);
	$query->bindValue(2,$_POST["usr"], PDO::PARAM_INT);
	$query->execute();
	
	$to = $usuario->correo;
	if($ban == 1)
	{
		$subject = 'Su cuenta ha sido Baneada'; 
		$message = '
			<html>
			<head>
			</head>
			<body>
				<div class="container">
					
					<div class="body">
						<span>Le informamos que su cuenta ha sido baneada, para mas informacion comuniquese con nosotros a serviciocliente@tumall.do </span>
					</div>
				</div>
			</body>
		</html>
			';
	}
	else
	{
		$subject = 'Su cuenta ha sido Restaurada'; 
		$message = '
			<html>
			<head>
			</head>
			<body>
				<div class="container">
					
					<div class="body">
						<span>Le informamos que su cuenta ha sido restaurada, gracias por contactarnos </span>
					</div>
				</div>
			</body>
		</html>
			';
	}
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: TuMall <no-reply@tumall.do>' .  "\r\n";
	mail($to,$subject,$message,$headers);
}
?>