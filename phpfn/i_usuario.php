<?php 
require_once('cndb.php');
$con = conection();
$query = $con->prepare('SELECT * FROM `usuario` where correo = ?');
$query->bindValue(1,$_POST["correo"]);
$query->execute();
$existeCorreo = $query->fetchAll(PDO::FETCH_OBJ);
if(empty($_POST["nombre"]))
{
	session_start();
	$_SESSION["error"] = 1;
	session_write_close();
	echo "<script type='application/javascript'>history.go(-1)</script>";
}
else if(empty($_POST["correo"]))
{
	session_start();
	$_SESSION["error"] = 2;
	session_write_close();
	echo "<script type='application/javascript'>history.go(-1)</script>";
}
else if(empty($_POST["contra1"]))
{
	session_start();
	$_SESSION["error"] = 3;
	session_write_close();
	echo "<script type='application/javascript'>history.go(-1)</script>";
}
else if(empty($_POST["contra2"]))
{
	session_start();
	$_SESSION["error"] = 4;
	session_write_close();
	echo "<script type='application/javascript'>history.go(-1)</script>";
}
else if($_POST["contra1"] <> $_POST["contra2"])
{
	session_start();
	$_SESSION["error"] = 5;
	session_write_close();
	echo "<script type='application/javascript'>history.go(-1)</script>";
}
else if(count($existeCorreo))
{
	session_start();
	$_SESSION["error"] = 6;
	session_write_close();
	echo "<script type='application/javascript'>history.go(-1)</script>";
}

else if(($_POST["tipoUsuario"] == 2) and (empty($_POST["nombreTienda"])))
{
        session_start();
	$_SESSION["error"] = 7;
	session_write_close();
	echo "<script type='application/javascript'>history.go(-1)</script>";
}
else if(!filter_var($_POST["correo"], FILTER_VALIDATE_EMAIL))
{
        session_start();
	$_SESSION["error"] = 8;
	session_write_close();
	echo "<script type='application/javascript'>history.go(-1)</script>";
}
else
{
    if($_POST["tipoUsuario"] == 2)
    {
	$query = $con->prepare('insert into usuario(`nombre`,`correo`,`nombretienda`,`contrasena`,`idtipousuario`,`telefono1`) values (?,?,?,?,?,?)');
	$query->bindValue(1,$_POST["nombre"]);
	$query->bindValue(2,$_POST["correo"]);
        $query->bindValue(3,$_POST["nombreTienda"]);
	$query->bindValue(4,md5($_POST["contra1"]));
        $query->bindValue(5,$_POST["tipoUsuario"],PDO::PARAM_INT);
        $query->bindValue(6,$_POST["telefono"]);
    }
    else
    {
        $query = $con->prepare('insert into usuario(`nombre`,`correo`,`contrasena`) values (?,?,?)');
	$query->bindValue(1,$_POST["nombre"]);
	$query->bindValue(2,$_POST["correo"]);
	$query->bindValue(3,md5($_POST["contra1"]));
    }
	$query->execute();
	$lastid = $con->lastInsertId();
	
	$to = $_POST["correo"];
	$subject = 'Bienvenido a TuMall!'; 
	$message = '
		<html>
		<head>
		</head>
		<body>
			<div class="container">
				
				<div class="body">
					<span>Para finalizar el registro de usuario, por favor entra a este <a href="http://tumall.dophpfn/activar_usr.php?id='. md5($lastid) .'">link</a></span>
				</div>
			</div>
		</body>
	</html>
		';
		
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: TuMall <no-reply@tumall.do>' .  "\r\n";
	mail($to,$subject,$message,$headers);
	
        header("location: ../finregistro");
	#echo "<script type='application/javascript'>history.go(-1)</script>";
#insert into usuario(`nombre`,`correo`,`contrasena`) values (?,?,?)
		
}
?>