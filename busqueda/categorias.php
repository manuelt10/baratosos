<div class="allCategoriesWrap">
	<h1 class="allCatHeading">Lista de todas las categorías</h1>
	
		
		<?php 
		require_once('phpfn/stringmanager.php');
			$sM = new stringManager();
		 	$query = $con->prepare('SELECT * FROM `categoria1`');
			$query->execute();
			$categoria1 = $query->fetchAll(PDO::FETCH_OBJ);
		  	foreach($categoria1 as $c1)
			{
					$link = $sM->remove_accents($c1->descripcion);
					$link = str_replace("-", " ", $link);
					$link = preg_replace('/\s\s+/', ' ', $link);
					$link = str_replace(" ", "-", $link);
					$link = strtolower($link);
					$link = preg_replace('/[^A-Za-z0-9\-]/', '', $link);
				?>
				<div class="categoryWrap">
				<h2 class="subCategoryHeading"><a href="lista/categoria/<?php echo $link . "-" . $c1->idcategoria1 ?>/"><?php echo $c1->descripcion ?></a></h2>
				<?php
				$query = $con->prepare('SELECT * FROM `categoria2` where idcategoria1 = ?');
				$query->bindValue(1,$c1->idcategoria1, PDO::PARAM_INT);
				$query->execute();
				$categoria2 = $query->fetchAll(PDO::FETCH_OBJ);
				if(!empty($categoria2))
				{
					?>
					<ul class="subCategoryList">
					<?php
					foreach($categoria2 as $c2)
					{
						$link = $sM->remove_accents($c2->descripcion);
						$link = str_replace("-", " ", $link);
						$link = preg_replace('/\s\s+/', ' ', $link);
						$link = str_replace(" ", "-", $link);
						$link = strtolower($link);
						$link = preg_replace('/[^A-Za-z0-9\-]/', '', $link);
						?>
						<li><a href="lista/categoria2/<?php echo $link . "-" . $c2->idcategoria2 ?>/"><?php echo $c2->descripcion ?></a></li>
						<?php
					}
					?>
					</ul>
					<?php
				}
			?>
			
			</div>
			
			<?php 
			}
			?>
	
</div>