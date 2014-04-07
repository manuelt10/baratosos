<?php 
session_start();
$session = $_SESSION;
session_write_close();
if($session["autenticado"])
{
	require_once('../../phpfn/cndb.php');
	$con = conection();
	$query = $con->prepare('update `producto` set `borrado` = 1 WHERE `idproducto` = ?');
    $query->bindValue(1,$_POST["idproducto"], PDO::PARAM_INT);
    $query->execute();

	$query = $con->prepare('select imagen from imagen_producto
							where idproducto = :idproducto');
    $query->bindValue(':idproducto',$_POST["idproducto"], PDO::PARAM_INT);
    $query->execute();
	$imgproductos = $query->fetchAll(PDO::FETCH_OBJ);
	foreach($imgproductos as $ip)
	{
		unlink('../../images/productos/' . $ip->imagen);
		unlink('../../images/productos/thumb50/' . $ip->imagen);
		unlink('../../images/productos/thumb150/' . $ip->imagen);
		unlink('../../images/productos/thumb200/' . $ip->imagen);
		unlink('../../images/productos/thumb400/' . $ip->imagen);
	}
	$query = $con->prepare('delete imagen_producto
							where idproducto = :idproducto');
    $query->bindValue(':idproducto',$_POST["idproducto"], PDO::PARAM_INT);
    $query->execute();
	
	
	$query = $con->prepare('select imagen from imagencaracteristica
							where idcaracteristica_producto in 
							(select idcaracteristica_producto from caracteristica_producto where idproducto = :idproducto)');
    $query->bindValue(':idproducto',$_POST["idproducto"], PDO::PARAM_INT);
    $query->execute();
	$imgproductos = $query->fetchAll(PDO::FETCH_OBJ);
	foreach($imgproductos as $ip)
	{
		unlink('../../images/c_prod/' . $ip->imagen);
		unlink('../../images/c_prod/thumb50/' . $ip->imagen);
		unlink('../../images/c_prod/thumb150/' . $ip->imagen);
		unlink('../../images/c_prod/thumb200/' . $ip->imagen);
		unlink('../../images/c_prod/thumb400/' . $ip->imagen);
	}
	$query = $con->prepare('select imagen from imagencaracteristica
							where idcaracteristica_producto in 
							(select idcaracteristica_producto from caracteristica_producto where idproducto = :idproducto)');
    $query->bindValue(':idproducto',$_POST["idproducto"], PDO::PARAM_INT);
    $query->execute();

}
?>