<?php 
session_start();
$session = $_SESSION;
session_write_close();
if($session["autenticado"])
{
	require_once('../../phpfn/cndb.php');
	$con = conection();
	$query = $con->prepare('select * from producto where idproducto = :idproducto
							and activo = 1
							and borrado = 0');
			$query->bindValue(':idproducto',$_POST["id"], PDO::PARAM_INT);
			$query->execute();
			$producto = $query->fetch(PDO::FETCH_OBJ);
			
	$query = $con->prepare('select count(*) as conteo from productopub');
			$query->bindValue(':idproducto',$_POST["id"], PDO::PARAM_INT);
			$query->execute();
			$cantidadProdPub = $query->fetch(PDO::FETCH_OBJ);
	
	if($cantidadProdPub->conteo <= 12)
	{
		if(count($producto->idproducto))
		{
			if(!empty($_POST["id"]))
			{
				$query = $con->prepare('insert into productopub(idproducto, duracion)
										select idproducto, :duracion
										from producto 
										where idproducto = :idproducto
										and activo = 1
										and borrado = 0');
				$query->bindValue(':idproducto',$_POST["id"], PDO::PARAM_INT);
				$query->bindValue(':duracion',$_POST["dias"], PDO::PARAM_INT);
				$query->execute();
				
				/*lastInsertId*/
					$query = $con->prepare('select p.nombre, p.moneda, p.precio, p.preciooferta, p.enoferta, p.borrado, p.activo
										from producto p
										where p.idproducto = :idproducto
										group by p.nombre, p.moneda, p.precio, p.preciooferta, p.enoferta, p.borrado, p.activo');
					$query->bindValue(':idproducto',$_POST["id"], PDO::PARAM_INT);
					$query->execute();
					$producto = $query->fetch(PDO::FETCH_OBJ);
					if(count($producto->nombre))
					{
						?>
						
						<h4><?php echo $producto->nombre ?></h4>
						<span>
							<span class="<?php echo $producto->enoferta == 1 ? "enoferta" : ""; ?>"><?php echo $producto->moneda ?>$ <?php echo number_format($producto->precio,2,'.',',') ?></span>
							<?php 
							if($p->enoferta == 1)
							{
							?>
								<span><?php echo $producto->moneda ?>$ <?php echo number_format($producto->preciooferta,2,'.',',') ?></span>
								<span><?php echo number_format(($producto->preciooferta / $producto->precio) * 100, 2)?>% descuento</span>
							<?php	
							}
							?>
						</span>
						<?php 
					
				}
			}
		}
	}
}
?>