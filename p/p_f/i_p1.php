<?php
session_start();
    $session = $_SESSION;
session_write_close();



require_once('../../phpfn/cndb.php');
$con = conection();
/*
$query = $con->prepare('SELECT * FROM `img_temporales` WHERE `idusuario` = ?');
$query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
$query->execute();
$imagenes = $query->fetchAll(PDO::FETCH_OBJ);
if(!count($imagenes))
{
    session_start();
    $_SESSION["error"] = 9;
    echo "<script>window.history.go(-1);</script>";
    session_write_close();
}
 */

 
if(empty($_POST["categoria1"]))
{
    session_start();
    $_SESSION["error"] = 1;
    echo "<script>window.history.go(-1);</script>";
    session_write_close();
}
else if(empty($_POST["categoria2"]))
{
    $query = $con->prepare('SELECT * FROM `categoria2` where idcategoria1 = ?');
    $query->bindValue(1,$_POST["categoria1"], PDO::PARAM_INT);
    $query->execute();
    $categoria2 = $query->fetchAll(PDO::FETCH_OBJ);
    if(count($categoria2))
    {
        session_start();
        $_SESSION["error"] = 2;
        echo "<script>window.history.go(-1);</script>";
        session_write_close();
    }
	
}
else if(empty($_POST["categoria3"]))
{
    $query = $con->prepare('SELECT * FROM `categoria3` where idcategoria2 = ?');
    $query->bindValue(1,$_POST["categoria2"], PDO::PARAM_INT);
    $query->execute();
    $categoria3 = $query->fetchAll(PDO::FETCH_OBJ); 
    if(count($categoria3))
    {
        session_start();
        $_SESSION["error"] = 3;
        echo "<script>window.history.go(-1);</script>";
        session_write_close();
    }
}
if(empty($_POST["nombreProducto"]))
{
    session_start();
    $_SESSION["error"] = 4;
    echo "<script>window.history.go(-1);</script>";
    session_write_close();
}
else if(empty($_POST["descripcionProducto"]))
{
    session_start();
    $_SESSION["error"] = 5;
    echo "<script>window.history.go(-1);</script>";
    session_write_close();
}
else if(!is_numeric($_POST["precioProducto"]))
{
    session_start();
    $_SESSION["error"] = 6;
    echo "<script>window.history.go(-1);</script>";
    session_write_close();
}
else if(empty($_POST["precioProducto"]))
{
    session_start();
    $_SESSION["error"] = 7;
    echo "<script>window.history.go(-1);</script>";
    session_write_close();
}
/*
else if(empty($_POST["palabrasClave"]))
{
    session_start();
    $_SESSION["error"] = 8;
    echo "<script>window.history.go(-1);</script>";
    session_write_close();
}
*/
else
{
    if(isset($_POST["enOferta"]))
	{
		$enOferta = 1;
	}
	else
	{
		$enOferta = 0;
	}
    #Inserta producto
	   try {
	   	#Gracias Stackoverflow
			$_POST["descripcionProducto"] = preg_replace("/<style\\b[^>]*>(.*?)<\\/style>/s", "", $_POST["descripcionProducto"]);
	    $query = $con->prepare('
	        insert into producto(`nombre`,`descripcion`,`idusuario`,`idtipotransaccion`,`palabras_claves`,`caracteristica_pred`,`idcategoria1`,`idcategoria2`,`idcategoria3`,`precio`, `moneda`, `preciooferta`, `enoferta`) 
	        values (?,?,?,?,?,?,?,?,?,?,?,?,?)');
			$query->bindValue(1,$_POST["nombreProducto"]);
			$query->bindValue(2,$_POST["descripcionProducto"]);
	        $query->bindValue(3,$session["usuario"], PDO::PARAM_INT);
	        $query->bindValue(4, $_POST["tipoTransaccion"], PDO::PARAM_INT);
			$query->bindValue(5,$_POST["palabrasClave"]);
			$query->bindValue(6,$_POST["caract_default"]);
	        $query->bindValue(7,$_POST["categoria1"], PDO::PARAM_INT);
			$query->bindValue(8,$_POST["categoria2"], PDO::PARAM_INT);
	        $query->bindValue(9,$_POST["categoria3"], PDO::PARAM_INT);
			$query->bindValue(10,$_POST["precioProducto"]);
			$query->bindValue(11,$_POST["moneda"]);
			$query->bindValue(12,$_POST["precioOferta"]);
			$query->bindValue(13,$enOferta, PDO::PARAM_INT);
			$query->execute();
		$lastid = $con->lastInsertId();
		}
		catch (PDOException $err) {
		    $trace = '<table border="0">';
		    foreach ($err->getTrace() as $a => $b) {
		        foreach ($b as $c => $d) {
		            if ($c == 'args') {
		                foreach ($d as $e => $f) {
		                    $trace .= '<tr><td><b>' . strval($a) . '#</b></td><td align="right"><u>args:</u></td> <td><u>' . $e . '</u>:</td><td><i>' . $f . '</i></td></tr>';
		                }
		            } else {
		                $trace .= '<tr><td><b>' . strval($a) . '#</b></td><td align="right"><u>' . $c . '</u>:</td><td></td><td><i>' . $d . '</i></td>';
		            }
		        }
		    }
		    $trace .= '</table>';
		    echo '<br /><br /><br /><font face="Verdana"><center><fieldset style="width: 66%; border: 4px solid white; background: black;"><legend><b>[</b>PHP PDO Error ' . strval($err->getCode()) . '<b>]</b></legend> <table border="0"><tr><td align="right"><b><u>Message:</u></b></td><td><i>' . $err->getMessage() . '</i></td></tr><tr><td align="right"><b><u>Code:</u></b></td><td><i>' . strval($err->getCode()) . '</i></td></tr><tr><td align="right"><b><u>File:</u></b></td><td><i>' . $err->getFile() . '</i></td></tr><tr><td align="right"><b><u>Line:</u></b></td><td><i>' . strval($err->getLine()) . '</i></td></tr><tr><td align="right"><b><u>Trace:</u></b></td><td><br /><br />' . $trace . '</td></tr></table></fieldset></center></font>';
		}
    #inserta imagenes producto
    if(!empty($lastid))
	{
	     try {
		    $query2 = $con->prepare('CALL prod_ins_caract(?,?)');
		        $query2->bindValue(1,$lastid, PDO::PARAM_INT);
		        $query2->bindValue(2,$session["usuario"], PDO::PARAM_INT);
			$query2->execute();
			}
			catch (PDOException $err) {
			    $trace = '<table border="0">';
			    foreach ($err->getTrace() as $a => $b) {
			        foreach ($b as $c => $d) {
			            if ($c == 'args') {
			                foreach ($d as $e => $f) {
			                    $trace .= '<tr><td><b>' . strval($a) . '#</b></td><td align="right"><u>args:</u></td> <td><u>' . $e . '</u>:</td><td><i>' . $f . '</i></td></tr>';
			                }
			            } else {
			                $trace .= '<tr><td><b>' . strval($a) . '#</b></td><td align="right"><u>' . $c . '</u>:</td><td></td><td><i>' . $d . '</i></td>';
			            }
			        }
			    }
			    $trace .= '</table>';
			    echo '<br /><br /><br /><font face="Verdana"><center><fieldset style="width: 66%; border: 4px solid white; background: black;"><legend><b>[</b>PHP PDO Error ' . strval($err->getCode()) . '<b>]</b></legend> <table border="0"><tr><td align="right"><b><u>Message:</u></b></td><td><i>' . $err->getMessage() . '</i></td></tr><tr><td align="right"><b><u>Code:</u></b></td><td><i>' . strval($err->getCode()) . '</i></td></tr><tr><td align="right"><b><u>File:</u></b></td><td><i>' . $err->getFile() . '</i></td></tr><tr><td align="right"><b><u>Line:</u></b></td><td><i>' . strval($err->getLine()) . '</i></td></tr><tr><td align="right"><b><u>Trace:</u></b></td><td><br /><br />' . $trace . '</td></tr></table></fieldset></center></font>';
			}
	}
	

    #elimina imagenes de la tabla temporal
    /* $query3 = $con->prepare('
        delete FROM `img_temporales` WHERE `idusuario` = ?');
        $query3->bindValue(1,$session["usuario"], PDO::PARAM_INT);
	$query3->execute();*/
        
    
         
     header("location: ../../productos/lista/");
}

?>
