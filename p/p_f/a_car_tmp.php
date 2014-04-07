<?php 
	 	session_start();
		    $session = $_SESSION;
		session_write_close();
		require_once('../../phpfn/cndb.php');
		$con = conection();
		if($session["autenticado"])
		{
	        $query = $con->prepare('
	        INSERT INTO `temp_caracteristicas`(`caracteristica`, `idusuario`) VALUES (?,?)');
	        $query->bindValue(1,$_POST["caracteristica"]);
	        $query->bindValue(2,$session["usuario"], PDO::PARAM_INT);
			$query->execute();
	      	$lastid = $con->lastInsertId();
			
			#UPDATE `img_temp_caractprod` SET  `idtemp_caracteristicas`=[value-4] WHERE 1
			
			$query = $con->prepare('
	        UPDATE `img_temp_caractprod` SET `idtemp_caracteristicas`= ? where idusuario = ? and idtemp_caracteristicas is null');
			$query->bindValue(1,$lastid, PDO::PARAM_INT);
	        $query->bindValue(2,$session["usuario"], PDO::PARAM_INT);
			$query->execute();
			
		    		
			$query3 = $con->prepare('SELECT * FROM `temp_caracteristicas` WHERE idtemp_caracteristicas = ?');
            $query3->bindValue(1,$lastid, PDO::PARAM_INT);
            $query3->execute();
            $caracteristica_temp = $query3->fetch(PDO::FETCH_OBJ);
				?>
				<form class="caracteristica xtraCharacDetail characDetail transTw hidden" action="p/p_f/upl_caract.php">
						
			    	<!-- <label class="settingLbl itmsLbl characLbl">Característica:</label> -->
			    	<input type="text" class="txtField itmTxtFld charactTxt" name="caracteristica[]" placeholder="Nombre característica">
			    	<input type="hidden" name="id" class ="idCar" value="<?php echo $caracteristica_temp->idtemp_caracteristicas ?>" >
			    	<button class="removeCharacBtn" type="button"></button>
			    	<br />
			    					    	
			    	<div class="upldInptMask">
			    		<input type="file" name="file" class="imgcarupl" multiple>
			    	</div>
			    	<input type="hidden" value="<?php echo $lastid ?>" name="id">
			    	<input type="hidden" class="cont" value="<?php echo $_POST["cont"] ?>">
			    	
					<div class="imgCaract imgChWrap<?php echo $_POST["cont"] ?>" data-id="imgChWrap<?php echo $_POST["cont"] ?>">
			    		<?php
							$query3 = $con->prepare('SELECT * FROM `img_temp_caractprod` WHERE `idtemp_caracteristicas` = ?');
				            $query3->bindValue(1,$lastid, PDO::PARAM_INT);
				            $query3->execute();
				            $img_caract = $query3->fetchAll(PDO::FETCH_OBJ);
							foreach($img_caract as $iC)
							{
								?>
								<img src="images/c_prod/thumb150/<?php echo $iC->imagen ?>">
								<?php
							}
						?>
			    	</div>	
			   	</form>
				<?php 
				}
				?>