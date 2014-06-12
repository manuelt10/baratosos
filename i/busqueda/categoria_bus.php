<?php 
							require_once('phpfn/stringmanager.php');
								#CATEGORIES
								$sM = new stringManager();
							 	$query = $con->prepare('SELECT * FROM `categoria1`
									where idcategoria1 in 
									(
									select idcategoria1 from producto
									WHERE (nombre like :nombre or palabras_claves like :nombre)
									)
									');
								$query->bindValue(':nombre','%' . $_GET["buscar"] . '%');
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
									<h3 class="resultsSubCategory"><a href="/lista/categoria/<?php echo $link . "-" . $c1->idcategoria1 ?>/
										<?php echo empty($_GET["buscar"]) ? "" : "buscar/". $_GET["buscar"] ."/" ?>"><?php echo $c1->descripcion ?></a></h3>
									<?php
									$query = $con->prepare('SELECT * FROM `categoria2` 
									where idcategoria1 = :idcat1
									and idcategoria2 in  
									(
									select idcategoria2 from producto
									WHERE (nombre like :nombre or palabras_claves like :nombre)
									)
									');
									$query->bindValue(':idcat1',$c1->idcategoria1, PDO::PARAM_INT);
									$query->bindValue(':nombre','%' . $_GET["buscar"] . '%');
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
											<li><h4><a class="last-category" href="/lista/categoria2/<?php echo $link . "-" . $c2->idcategoria2 ?>/
												<?php echo empty($_GET["buscar"]) ? "" : "buscar/". $_GET["buscar"] ."/" ?>"><?php echo $c2->descripcion ?></a></h4></li>
											<?php
										}
										?>
										</ul>
										<?php
									}
								}
								?>
								<a class="view-more" href="/lista">Ver otras categorías</a>