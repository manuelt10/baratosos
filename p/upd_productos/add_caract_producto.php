<?php 
session_start();
$session = $_SESSION;
session_write_close();
if($session["autenticado"])
{
	require_once('../../phpfn/cndb.php');
	$con = conection();
	#INSERT INTO `caracteristica_producto`( `idproducto`, `descripcion`) VALUES (?,?)
	$query = $con->prepare('INSERT INTO `caracteristica_producto`( `idproducto`, `descripcion`, `temp`) 
							VALUES (?,?,1)');
    $query->bindValue(1,$_POST["prod"]);
	$query->bindValue(2,$_POST["descripcion"], PDO::PARAM_INT);
    $query->execute();
	$lastid = $con->lastInsertId();
	?>
	<form method="post" class="caracteristica xtraCharacDetail characDetail hidden transTw" action="p/upd_productos/upd_img_caract.php">
    	<!-- <label class="settingLbl itmsLbl">Color:</label> -->
    	<input type="text" class="caracteristica txtField itmTxtFld" value="<?php echo $_POST["descripcion"] ?>">
    	<input type="hidden" name="idproducto" value="<?php echo $_POST["prod"] ?>">
    	<input type="hidden" name="id" class ="idCar" value="<?php echo $lastid ?>" >
    	<button class="removeCharacBtn" type="button"></button>
    	<br />

    	<input type="hidden" class="cont" value="<?php echo $_POST["con"] ?>"> 
    	<div class="upldInptMask">
    		<input type="file" name="file" class="imgcarupl">
    	</div>	
    	
    	<div class="imgCaract imgListWrap imgChWrap<?php echo $_POST["con"] ?>" data-id="imgChWrap<?php echo $_POST["con"] ?>">
    		
    	</div>
    	
       	
    
   	</form>
	
	<?php
	
}
?>