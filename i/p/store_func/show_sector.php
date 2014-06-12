<?php 

require_once('../../phpfn/cndb.php');
	$con = conection();
$query = $con->prepare('SELECT * FROM `sector` WHERE `idprovincia` = ? order by descripcion');
$query->bindValue(1, $_POST["idprovincia"],PDO::PARAM_INT);
$query->execute();
$sector = $query->fetchAll(PDO::FETCH_OBJ);
foreach($sector as $st)
			{
				if($st->idsector <> 1)
				{
				?>
				<div class="sectorDetail ">
					<input type="hidden" class="sectorVal" value="<?php echo $st->idsector ?>">
					<span class="listSector"><?php echo $st->descripcion ?></span>
				</div>
				<?
				}
			}
?>