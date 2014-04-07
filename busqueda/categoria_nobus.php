<?php 
								require_once('phpfn/stringmanager.php');
								#CATEGORIES
								$sM = new stringManager();
							 	$query = $con->prepare('SELECT * FROM `categoria1` 
							 	where idcategoria1 = :categoria1
							 	');
								$query-> bindValue(':categoria1', $categoria1, PDO::PARAM_INT);
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
									<h5 class="resultsSubCategory"><a href="lista/categoria/<?php echo $link . "-" . $c1->idcategoria1 ?>/"><?php echo $c1->descripcion ?></a></h5>
									<?php
									$query = $con->prepare('SELECT * FROM `categoria2` where idcategoria1 = ?');
									$query->bindValue(1,$c1->idcategoria1, PDO::PARAM_INT);
									$query->execute();
									$categoria2 = $query->fetchAll(PDO::FETCH_OBJ);
									if(!empty($categoria2))
									{
										?>
										<ul class="resultsCategoryList">
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
								}
								?>
								<span><a href="lista">Ver otras categorías</a></span>