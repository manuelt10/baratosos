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

 
if($session["autenticado"])
{
	
		
	if(empty($_POST["nombreProducto"]))
	{
	    session_start();
	    $_SESSION["error"] = 1;
	    echo "<script>window.history.go(-1);</script>";
	    session_write_close();
	}
	else if(empty($_POST["precioProducto"]))
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
	else if(empty($_POST["descripcionProducto"]))
	{
	    session_start();
	    $_SESSION["error"] = 7;
	    echo "<script>window.history.go(-1);</script>";
	    session_write_close();
	}	
	else
	{
	    if(isset($_POST["enoferta"]))
		{
			$enoferta = 1;
		}
		else {
			$enoferta = 0;
		}
			#Gracias Stackoverflow
			$_POST["descripcionProducto"] = preg_replace("/<style\\b[^>]*>(.*?)<\\/style>/s", "", $_POST["descripcionProducto"]);
		    $query = $con->prepare('
		        update producto
				set `nombre` = ?,
					`descripcion` = ?,
					`idtipotransaccion` = ?,
					`caracteristica_pred` = ?,
					`palabras_claves` = ?,
					`idcategoria1` = ?,
					`idcategoria2` = ?,
					`idcategoria3` = ?,
					`precio` = ?,
					`fecha_modificacion` = CURRENT_TIMESTAMP(),
					`moneda` = ?,
					`preciooferta` = ?,
					`enoferta` = ?
		        where idproducto = ?');
				$query->bindValue(1,$_POST["nombreProducto"]);
				$query->bindValue(2,$_POST["descripcionProducto"]);
		        $query->bindValue(3, $_POST["tipoTransaccion"], PDO::PARAM_INT);
		        $query->bindValue(4,$_POST["caract_default"]);
				$query->bindValue(5,$_POST["palabrasClave"]);
		        $query->bindValue(6,$_POST["categoria1"], PDO::PARAM_INT);
				$query->bindValue(7,$_POST["categoria2"], PDO::PARAM_INT);
		        $query->bindValue(8,$_POST["categoria3"], PDO::PARAM_INT);
				$query->bindValue(9,$_POST["precioProducto"]);
				$query->bindValue(10,$_POST["moneda"]);
				$query->bindValue(11,$_POST["precioOferta"]);
				$query->bindValue(12,$enoferta, PDO::PARAM_INT);
				$query->bindValue(13,$_POST["idproduct"], PDO::PARAM_INT);
				$query->execute();
				
				
					$query3 = $con->prepare('SELECT * FROM `imagenproducto` WHERE `idproducto` = ? and `delete` = 1');
		            $query3->bindValue(1,$_POST["idproduct"], PDO::PARAM_INT);
		            $query3->execute();
		            $imgBorrar = $query3->fetchAll(PDO::FETCH_OBJ);
					foreach($imgBorrar as $iB)
					{
						unlink('../../images/productos/' . $iB->imagen);
						unlink('../../images/productos/thumb50/' . $iB->imagen);
						unlink('../../images/productos/thumb150/' . $iB->imagen);
						unlink('../../images/productos/thumb200/' . $iB->imagen);
						unlink('../../images/productos/thumb400/' . $iB->imagen);
					}
					$query3 = $con->prepare('DELETE FROM `imagenproducto` WHERE `idproducto` = ? and `delete` = 1');
		            $query3->bindValue(1,$_POST["idproduct"], PDO::PARAM_INT);
		            $query3->execute();
					
					$query3 = $con->prepare('SELECT * FROM `img_temporales` WHERE `idproducto` = ? and `delete` = 1');
		            $query3->bindValue(1,$_POST["idproduct"], PDO::PARAM_INT);
		            $query3->execute();
		            $imgBorrar = $query3->fetchAll(PDO::FETCH_OBJ);
					foreach($imgBorrar as $iB)
					{
						unlink('../../images/productos/' . $iB->imagen);
						unlink('../../images/productos/thumb50/' . $iB->imagen);
						unlink('../../images/productos/thumb150/' . $iB->imagen);
						unlink('../../images/productos/thumb200/' . $iB->imagen);
						unlink('../../images/productos/thumb400/' . $iB->imagen);
					}
					$query3 = $con->prepare('DELETE FROM `img_temporales` WHERE `idproducto` = ? and `delete` = 1');
		            $query3->bindValue(1,$_POST["idproduct"], PDO::PARAM_INT);
		            $query3->execute();
				
				
				$query3 = $con->prepare('
				INSERT INTO imagenproducto(idproducto, imagen, principal)
				SELECT ?,img_temporales,0 FROM img_temporales WHERE idproducto = ?');
	            $query3->bindValue(1,$_POST["idproduct"], PDO::PARAM_INT);
				$query3->bindValue(2,$_POST["idproduct"], PDO::PARAM_INT);
	            $query3->execute();
				
				$query3 = $con->prepare('
				DELETE FROM img_temporales WHERE  idproducto = ?');
	            $query3->bindValue(1,$_POST["idproduct"], PDO::PARAM_INT);
	            $query3->execute();
				/*
					INSERT INTO imagenproducto(idproducto, imagen, principal)
	SELECT producto,img_temporales,0 FROM img_temporales WHERE idusuario = usuario LIMIT 0,max_image;

	DELETE FROM img_temporales WHERE  idusuario = usuario; 
				*/

		#Actualiza imagenes
		
		 	$query3 = $con->prepare('SELECT * FROM `caracteristica_producto` 
		 							WHERE `idproducto` = ? and `delete` = 1');
	            $query3->bindValue(1,$_POST["idproduct"], PDO::PARAM_INT);
	            $query3->execute();
	            $caracBorrar = $query3->fetchAll(PDO::FETCH_OBJ);
				foreach($caracBorrar as $cB)
				{
					$query3 = $con->prepare('SELECT * FROM `imagencaracteristica` 
											WHERE `idcaracteristica_producto` = ?');
		            $query3->bindValue(1,(int)$cB->idcaracteristica_producto, PDO::PARAM_INT);
		            $query3->execute();
		            $imgBorrar = $query3->fetchAll(PDO::FETCH_OBJ);
					foreach($imgBorrar as $iB)
					{
						unlink('../../images/c_prod/' . $iB->imagen);
						unlink('../../images/c_prod/thumb50/' . $iB->imagen);
						unlink('../../images/c_prod/thumb150/' . $iB->imagen);
						unlink('../../images/c_prod/thumb200/' . $iB->imagen);
						unlink('../../images/c_prod/thumb400/' . $iB->imagen);
					}
					$query3 = $con->prepare('DELETE FROM `imagencaracteristica` 
											WHERE `idcaracteristica_producto` = ?');
		            $query3->bindValue(1,(int)$cB->idcaracteristica_producto, PDO::PARAM_INT);
		            $query3->execute();
				}
				$query3 = $con->prepare('DELETE FROM `caracteristica_producto` 
										WHERE `idproducto` = ? and `delete` = 1');
	            $query3->bindValue(1,$_POST["idproduct"], PDO::PARAM_INT);
	            $query3->execute();
				
				
				
					$query3 = $con->prepare('SELECT * FROM `imagencaracteristica` 
											WHERE `idcaracteristica_producto` 
											IN (SELECT `idcaracteristica_producto` 
												FROM `caracteristica_producto` 
			 									WHERE `idproducto` = ?)
											AND `delete` = 1
											');
		            $query3->bindValue(1,$_POST["idproduct"], PDO::PARAM_INT);
		            $query3->execute();
		            $imgBorrar = $query3->fetchAll(PDO::FETCH_OBJ);
					foreach($imgBorrar as $iB)
					{
						unlink('../../images/c_prod/' . $iB->imagen);
						unlink('../../images/c_prod/thumb50/' . $iB->imagen);
						unlink('../../images/c_prod/thumb150/' . $iB->imagen);
						unlink('../../images/c_prod/thumb200/' . $iB->imagen);
						unlink('../../images/c_prod/thumb400/' . $iB->imagen);
					}
					
					$query3 = $con->prepare('DELETE FROM `imagencaracteristica` WHERE `idcaracteristica_producto` 
											IN (SELECT `idcaracteristica_producto` 
												FROM `caracteristica_producto` 
			 									WHERE `idproducto` = ?)
											AND `delete` = 1');
		            $query3->bindValue(1,$_POST["idproduct"], PDO::PARAM_INT);
		            $query3->execute();
		            
		            $query3 = $con->prepare('UPDATE `caracteristica_producto` 
		            						set temp = 0
		 							WHERE `idproducto` = ?
		 							');
						            $query3->bindValue(1,$_POST["idproduct"], PDO::PARAM_INT);
						            $query3->execute();
							
					$query3 = $con->prepare('UPDATE `caracteristica_producto` 
		            						set `descripcion` = `temp_descripcion`, `temp_descripcion` = NULL
		 							WHERE `idproducto` = ?
		 							and `temp_descripcion` is not null
		 							');
						            $query3->bindValue(1,$_POST["idproduct"], PDO::PARAM_INT);
						            $query3->execute();		
							
					$query3 = $con->prepare('UPDATE `imagencaracteristica` 
											SET temp = 0
											WHERE `idcaracteristica_producto` 
											IN (SELECT `idcaracteristica_producto` 
												FROM `caracteristica_producto` 
			 									WHERE `idproducto` = ? )');
		            $query3->bindValue(1,$_POST["idproduct"], PDO::PARAM_INT);
		            $query3->execute();
	
	
	
	}
}
if(!empty($session["usuarioReal"]))
{
	header("location: http://tumall.do/administracion/usr/" . $session["usuario"]);	
}
else {
	header("location: http://tumall.do/productos/lista/");	
}

?>