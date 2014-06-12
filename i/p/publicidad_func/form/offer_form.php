<?php 
session_start();
$session = $_SESSION;
session_write_close();
require_once('../../phpfn/cndb.php');
$con = conection();
?>
<div class="infoBox requ-InfoBox offerInfoBox transTw">
	<h3 class="requLegend">Ofertas y promociones</h3>
	<p>Las ofertas aparecen en la página principal y en diferentes secciones dentro de tuMall. Las ofertas se usan para promocionar uno o varios artículos en descuento, o especiales que quiera promover la tienda. Puedes tener hasta 3 ofertas publicadas al mismo tiempo.</p>
</div>

<div class="requFileWrap">
	<span class="inputFileText">Agregar imagen</span>
	<input class="fileUpload requFileUpld" type="file" name="file">
	<div class="newImage"></div>
</div>

<div class="requDetailWrap">	
	<label class="settingLbl requLbl">Título de la oferta: </label>
	<br />
	<input type="text" name="titulo" class="txtField transTw requTitle largeField"/>
	<br />
	<br />
	<label class="settingLbl requLbl">Detalles de la oferta: </label>
	<br />
	<textarea name="descripcion" class="txtField transTw requDescr largeField"></textarea>
	<br />
	<label class="settingLbl requLbl">Duración: </label>
	<br/ >
	<div class="styledSelect durationSel fluidTransTw">
		<select class="normalSelect selectDuration" name="tiempoDuracion">
			<option value="1">1 dia</option>
			<option value="2">2 dias</option>
			<option value="3">3 dias</option>
			<option value="4">4 dias</option>
			<option value="5">5 dias</option>
			<option value="6">6 dias</option>
			<option value="7">7 dias</option>
			<option value="14">14 dias</option>
			<option value="21">21 dias</option>
			<option value="30">30 dias</option>
		</select>
	</div>
	<?php /*
	<label class="settingLbl requLbl">Monto a pagar</label>
	<input type="text" name="monto">
	 
	 */
		$query = $con->prepare('select * from producto where idusuario = :id and activo = 1 and borrado = 0');
		$query->bindValue(':id',$session["usuario"], PDO::PARAM_INT);
		$query->execute();
		$productos = $query->fetchAll(PDO::FETCH_OBJ);
	?>
	 <div>
	 	<select name="idproducto[]">
	 		<option>Seleccionar producto</option>
	 		<?php 
	 		foreach($productos as $p)
			{
				?>
				<option value="<?php echo $p->idproducto ?>"><?php echo $p->idproducto .' - ' .$p->nombre ?></option>
				<?php
			}
	 		?>
	 	</select>
	 	<select name="idproducto[]">
	 		<option>Seleccionar producto</option>
	 		<?php 
	 		foreach($productos as $p)
			{
				?>
				<option value="<?php echo $p->idproducto ?>"><?php echo $p->idproducto .' - ' .$p->nombre ?></option>
				<?php
			}
	 		?>
	 	</select>
	 	<select name="idproducto[]">
	 		<option>Seleccionar producto</option>
	 		<?php 
	 		foreach($productos as $p)
			{
				?>
				<option value="<?php echo $p->idproducto ?>"><?php echo $p->idproducto .' - ' .$p->nombre ?></option>
				<?php
			}
	 		?>
	 	</select>
	 	<select name="idproducto[]">
	 		<option>Seleccionar producto</option>
	 		<?php 
	 		foreach($productos as $p)
			{
				?>
				<option value="<?php echo $p->idproducto ?>"><?php echo $p->idproducto .' - ' . $p->nombre ?></option>
				<?php
			}
	 		?>
	 	</select>
	 	<select name="idproducto[]">
	 		<option>Seleccionar producto</option>
	 		<?php 
	 		foreach($productos as $p)
			{
				?>
				<option value="<?php echo $p->idproducto ?>"><?php echo $p->idproducto .' - ' .$p->nombre ?></option>
				<?php
			}
	 		?>
	 	</select>
	 </div>
</div>	

<?php 
echo $session["error"] == 1 ? "<span class='error'>Campos necesarios incompletos</span>" : "";
echo $session["error"] == 2 ? "<span class='error'>Dimension de la imagen erronea</span>" : ""; 
echo $session["error"] == 3 ? "<span class='error'>Solo se aceptan imagenes</span>" : "";
echo $session["error"] == 4 ? "<span class='error'>Hubo un problema inesperado con la imagen</span>" : "";
?>

<div class="ctrlBtnWrap requ-CtrlBtnWrap">
	<a class="formActionBtn back cancel transTw">Atrás</a>
	<button type="submit" class="formActionBtn sendForm">Enviar oferta</button>
</div>