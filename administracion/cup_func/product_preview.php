<?php 
session_start();
    $session = $_SESSION;
session_write_close();
if($session['autenticado'])
{
	require_once('../../phpfn/mysqlManager.php');
	$db = new mysqlManager();
	$where = Array(
	"idproducto" => $_POST["idproducto"],
	"idusuario" => $_POST["idusuario"]
	);
	$record = $db->selectRecord("producto",null,$where);
	if(!empty($record->data[0]))
	{
	?>
	
		<span class="pictureWrapper">
			<?php 
			if(empty($prod->data[0]->imagen))
			{
			?>
				<img src="images/NoImage.png" alt="No image" title="No image">
			<?php 
			}
			else
			{
			?>
				<img src="images/productos/thumb200/<?php echo $prod->data[0]->imagen ?>" title="<?php echo $prod->data[0]->nombre; ?>" alt="<?php echo $prod->data[0]->nombre; ?>" >
			<?php 
			}
			?>
		</span>
		<div class="itmListDescrWrap">
			<span><?php echo $record->data[0]->nombre ?></span>
			<!-- <span><?php echo $record->data[0]->price ?></span> -->
		</div>
	<?php
	}
	else {
		?>
		<span class="error-label validation-label">Este producto no existe o no pertenece a este usuario.</span>
		<?php
	}
}

?>

