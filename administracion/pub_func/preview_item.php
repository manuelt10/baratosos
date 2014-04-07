<?php 
session_start();
$session = $_SESSION;
session_write_close();
if($session["autenticado"])
{
	require_once('../../phpfn/cndb.php');
	$con = conection();
	$query = $con->prepare('select p.nombre, p.moneda, p.precio, p.preciooferta, p.enoferta, p.borrado, p.activo, max(i.imagen) as imagen
							from producto p
							left join imagenproducto i
							on i.idproducto = p.idproducto
							where p.idproducto = :idproducto
							group by p.nombre;');
	$query->bindValue(':idproducto',$_POST["id"], PDO::PARAM_INT);
	$query->execute();
	$producto = $query->fetch(PDO::FETCH_OBJ);
	if(count($producto->nombre))
	{
		?>
		<div>
		<?php 
		if(empty($producto->imagen))
		{
		?>
			<img src="images/productos/no-image.png" alt="resource1">
		<?php 
		}
		else
		{
		?>
			<img src="images/productos/thumb150/<?php echo $producto->imagen ?>" alt="resource1">
		<?php 
		}
		?>
		</div>
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
		if($producto->borrado == 1)
		{
			?>
			<span class="error-label">Este producto esta borrado</span>
			<?php
		} 
		else if($producto->activo == 0)
		{
			?>
			<span  class="error-label">Este producto esta inactivo</span>
			<?php
		}
		?>
		
		<?php
	}
	else
	{
		?>
		<span class="error-label">No existe este id</span>
		<?php
	}
}
?>