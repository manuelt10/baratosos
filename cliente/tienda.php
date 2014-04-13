<div class="topBar">
	<h2 class="settHead">Convertirse en tienda</h2>
</div>
<div class="settWrap becomeStoreWrap editOptionsWrap">
	<form class="optionForm editOptionsSec" method="post" action="/cliente/cambio_tienda/cliente_a_tienda.php">
		<div class="infoBox opts-InfoBox transTw">
				<h3 class="requLegend">Advertencia</h3>
				<p>Para vender, tu usuario primero debe ser convertido a tipo tienda. Convertirte en tienda eliminará tu wishlist y los favoritos que hayas dado. Deberás proveer un nombre de tienda y número de teléfono válido para poder proseguir.<br /><b>Éste procedimiento es irreversible.</b></p>
		</div>
		
		<label class="settingLbl">Nombre de la Tienda</label>
		<br ?>
		<input type="text" name="nombreTienda" class="txtField mid transTw">
		<br />
		<label class="settingLbl">Teléfono</label>
		<br />
		<input type="text" name="telefono1" class="txtField mid transTw">
		<br />
		<input type="checkbox" name="terminos" id="agreementCheck">
		<?php echo $session["error"] == 1 ? "<span class='validation-label error-label'>Todos los campos deben estar llenos.<span>" : "" ?>
		<label class="settingLbl" for="agreementCheck">Estoy de acuerdo con las <a href="#">políticas de uso</a>.</label>
		<?php echo $session["error"] == 2 ? "<span class='validation-label error-label'>Debe estar de acuerdo con lo planteado.<span>" : "" ?>
		<br />
		<button type="submit" class="sendForm formActionBtn transTw">Cambiar a tienda</button>
			
	</form>
</div>