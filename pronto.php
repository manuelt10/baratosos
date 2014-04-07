<form id="signUpFormWrapper" method="post" action="phpfn/csoonusr.php">
			<fieldset>
				<legend>Registro de Usuarios</legend>
				<label>Nombre: </label><input type="text" name="nombre" class="signFormText">
				<?php echo $session["error"]==1 ? '<span class="error-label">Es necesario el nombre</span>' : "" ?>
				<label>Correo: </label><input type="text" name="correo" class="signFormText emailAdd">
				<?php echo $session["error"]==2 ? '<span class="error-label">Es necesario el correo</span>' : "" ?>
				<label>Contraseña: </label><input type="password" name="contra1" class="signFormText signUpPass">
				<label>Repetir Contraseña: </label><input type="password" name="contra2" class="signFormText signUpPass2">
				<?php echo $session["error"]==3 ? '<span class="error-label">Las contraseñas son necesarias</span>' : "" ?>
				<?php echo $session["error"]==4 ? '<span class="error-label">Las contraseñas son necesarias</span>' : "" ?>
				<?php echo $session["error"]==5 ? '<span class="error-label">Las contraseñas son distintas</span>' : "" ?>
				<?php /*<label>Provincia</label>
				<select>
					<?php 
					foreach($provincias as $provincia)
					{
						?>
						<option value="<?php echo $provincia->idprovincia ?>"><?php echo $provincia->descripcion ?></option>
						<?php
					}
					?>
				</select> */?>
				<?php echo $session["error"]==6 ? '<span class="error-label">Usuario ya existe</span>' : "" ?>
				<button type="submit">Registro</button>
			</fieldset>
		</form>