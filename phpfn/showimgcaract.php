<?php 
require_once('cndb.php');
$con = conection();
if($_POST["idCaracteristica"] <> 0)
{
$query = $con->prepare('select * from imagencaracteristica where idcaracteristica_producto = :id');
	$query->bindValue(':id', $_POST["idCaracteristica"], PDO::PARAM_INT);
	$query->execute();
	$imagenC = $query->fetchAll(PDO::FETCH_OBJ);

?>

<div class="itemMainPrev">
	<img src="/images/c_prod/<?php echo $imagenC[0]->imagen ?>" alt="<?php echo $producto->nombre ?>" title="<?php echo $producto->nombre ?>"/>
</div>
<div class="itemSmallerPrevWrapper">
	<?php 
	$smallImgCount = 0;
	foreach($imagenC as $iC)
	{
	list($width, $height) = getimagesize("../images/c_prod/thumb150/".$iC->imagen."");
	$baseDimm = 50;

	if($height > $width){
		$ratio = $width/$baseDimm;
		$modVal = $height;
	}
	
	else{
		$ratio = $height/$baseDimm;
		$modVal = $width;
	}
	
	$modVal /= $ratio; 
	$pos = ($modVal - $baseDimm)/2
	
	
		?> 
		<div class="smallPrev alt <?php echo $smallImgCount == 0 ? "selectedItem" : "" ?>">
			<img <?php if($height > $width){  ?> width="<?php echo $baseDimm; ?>" height="<?php echo $modVal; ?>" style="top: -<?php echo $pos; ?>px;"  <?php }  else{ ?> height="<?php echo $baseDimm; ?>" width="<?php echo $modVal; ?>" style="left: -<?php echo $pos; ?>px;" <?php } ?> src="/images/c_prod/thumb150/<?php echo $iC->imagen ?>" alt="<?php echo $producto->nombre ?>" title="<?php echo $producto->nombre ?>"/>
		</div>
		<?php
		$smallImgCount++;
	}
	?>
	
</div>
<?php 
}
else
	{
		$query = $con->prepare('SELECT * FROM imagenproducto where idproducto = :idproducto order by principal desc, fecha_creacion asc');
		$query->bindValue(':idproducto', $_POST["idproducto"], PDO::PARAM_INT);
		$query->execute();
		$imagenproducto = $query->fetchAll(PDO::FETCH_OBJ);
	?>
	<div class="itemMainPrev">
		<img src="/images/productos/<?php echo $imagenproducto[0]->imagen ?>" alt="<?php echo $producto->nombre ?>" title="<?php echo $producto->nombre ?>" />
	</div>
	<div class="itemSmallerPrevWrapper">
		<?php 
		$smallImgCount = 0;
		foreach($imagenproducto as $iP)
		{
		
		list($width, $height) = getimagesize("../images/productos/thumb150/".$iP->imagen."");
		$baseDimm = 50;
	
		if($height > $width){
			$ratio = $width/$baseDimm;
			$modVal = $height;
		}
		
		else{
			$ratio = $height/$baseDimm;
			$modVal = $width;
		}
		
		$modVal /= $ratio; 
		$pos = ($modVal - $baseDimm)/2
		
			?> 
			<div class="smallPrev <?php echo $smallImgCount == 0 ? "selectedItem" : "" ?>">
				<img <?php if($height > $width){  ?> width="<?php echo $baseDimm; ?>" height="<?php echo $modVal; ?>" style="top: -<?php echo $pos; ?>px;"  <?php }  else{ ?> height="<?php echo $baseDimm; ?>" width="<?php echo $modVal; ?>" style="left: -<?php echo $pos; ?>px;" <?php } ?> src="/images/productos/thumb150/<?php echo $iP->imagen ?>" alt="<?php echo $producto->nombre ?>" title="<?php echo $producto->nombre ?>"/>
			</div>
			<?php
			$smallImgCount++;
		}
		?>
		
	</div>
	
	<?php
	}
	
?>
