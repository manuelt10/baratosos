<div class="infoBox requ-InfoBox advInfoBox transTw">
	<h3 class="requLegend">Anuncios publicitarios</h3>
	<p>Los anuncios aparecerán en las diferentes secciones alrededor de tuMall. Los anuncios ocupan un espacio de 200px de ancho por 300px de alto. Puedes tener hasta 3 anuncios publicados al mismo tiempo.</p>
</div>

<div class="requFileWrap">
	<span class="inputFileText">Agregar imagen</span>
	<input class="fileUpload requFileUpld" type="file" name="file">
	<div class="newImage"></div>
</div>

<div class="requDetailWrap">
	<label class="settingLbl requLbl">Título del anuncio: </label>
	<br />
	<input type="text" name="titulo" class="txtField transTw requTitle largeField"/>
	<br />
	<br />
	<label class="settingLbl requLbl">Detalles de la publicidad: </label>
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
	<br/ >	
	<label class="settingLbl requLbl">Link: </label>
	<br />
	<input type="text" name="link" class="txtField transTw urlField">
	<?php /*	
	<label class="settingLbl requLbl">Monto a pagar</label>
	<input type="text" name="monto">
	 
	 */?>
</div>

<?php 
echo $session["error"] == 1 ? "<span class='error'>Campos necesarios incompletos</span>" : "";
echo $session["error"] == 2 ? "<span class='error'>Dimension de la imagen erronea</span>" : ""; 
echo $session["error"] == 3 ? "<span class='error'>Solo se aceptan imagenes</span>" : "";
echo $session["error"] == 4 ? "<span class='error'>Hubo un problema inesperado con la imagen</span>" : "";
?>

<div class="ctrlBtnWrap requ-CtrlBtnWrap">
	<a class="formActionBtn back cancel transTw">Atrás</a>
	<button type="submit" class="formActionBtn sendForm transTw">Enviar publicidad</button>
</div>
	
