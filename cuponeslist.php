<?php 

session_start();
if(!empty($_SESSION["usuarioReal"]))
{
	$_SESSION["usuario"] = $_SESSION["usuarioReal"];
	unset($_SESSION["usuarioReal"]);
}
$session = $_SESSION;
unset($_SESSION["error"]);
session_write_close();
require_once('phpfn/stringmanager.php');
require_once('phpfn/cndb.php');
require_once('phpfn/mysqlManager.php');
$con = conection();
$sM = new stringManager();
$query = $con->prepare('SELECT * FROM `usuario` where idusuario = ?');
$query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
$query->execute();
$usuario = $query->fetch(PDO::FETCH_OBJ);
require_once('templates/headgeneral.php');
$db = new mysqlManager();

$curdate = date('Y-m-d 00:00:00');
$where = Array(
		'dia_publicacion' => $curdate,
		'estatus' => 1,
		'aprobado' => 1
	);
	
	
$where2 = Array(
		'estatus' => 1,
		'aprobado' => 1
	);
$cupon = $db->selectRecord('cupon',NULL,$where);
$imagenCupon = $db->selectRecord('cupon_galeria',NULL,Array('idcupon' => $cupon->data[0]->idcupon),Array('principal' => 'DESC'));
$cupon_reserv = $db->selectRecord('cupon_compras',NULL,Array('idcupon' => $cupon->data[0]->idcupon ));

$cupones = $db->selectRecord('v_cupones_activos', NULL, $where2, Array('dia_publicacion' => 'desc'));


?>
<div class="genContentWrap group couponsList">
	<?php 
	$query = $con->prepare("SELECT * FROM `anunciopub`
						WHERE  fecha_creacion + INTERVAL duracion DAY >= curdate()
						and terminado = 0
						and posicion like 'banner-top'
						order by rand()
						limit 0,1");
						$query->execute();
						$anuncio = $query->fetchAll(PDO::FETCH_OBJ);

	if(!empty($anuncio[0]->image))
	{
		?>
		<div class="adTop ads">
			<a href="<?php echo $anuncio[0]->link ?>"><img src="/images/publicidad/<?php echo $anuncio[0]->image ?>"></a>
		</div>
		<?php 
	}
		
		require_once('templates/featuredItems.php');
	?>
	
	<div class="resultsDisplayArea ">
	<?php if($cupon->rowcount)
		{
			$link = $sM->remove_accents($cupon->data[0]->titulo);
			$link = str_replace("-", " ", $link);
			$link = preg_replace('/\s\s+/', ' ', $link);
			$link = str_replace(" ", "-", $link);
			$link = strtolower($link);
			$link = preg_replace('/[^A-Za-z0-9\-]/', '', $link);
			?>
			<div class="featuredCoupon">
				<div class="itemPreviewsWrapper">
				<div class="itemMainPrev">
					
					<?php 
					if(!empty($imagenCupon->data[0]->imagen))
					{
						?>
						<a href="/cupon/<?php echo $link . '-' . $cupon->data[0]->idcupon;  ?>">
							<img src="/images/cupon/<?php echo $imagenCupon->data[0]->imagen ?>" alt="<?php echo $cupon->data[0]->titulo; ?>" title="<?php echo $cupon->data[0]->titulo; ?>" />
						</a>
						<?php 
					}
					
					else{
					?>
						<img src="/images/NoImage.png" alt="No image">
					<?php
					}
					?>			
				</div>	
				</div>
				
				<div class="itemInfoWrapper couponInfoWrap">	
					<h2 class="itemName"><a href="/cupon/<?php echo $link . '-' . $cupon->data[0]->idcupon;  ?>"><?php echo $cupon->data[0]->titulo; ?></a></h2>
					
						<p class="couponShortDescr">Paga <span class="couponDiscPrice couponPrice">$<?php echo number_format($cupon->data[0]->precio_oferta,2,'.',',') ; ?></span> en vez de 
						<span class="coupounNormPrice couponPrice">$<?php echo number_format($cupon->data[0]->precio_normal,2,'.',','); ?> (<?php echo number_format((($cupon->data[0]->precio_normal - $cupon->data[0]->precio_oferta)*100)/$cupon->data[0]->precio_normal,2); ?>%
			 de descuento)</span></p>
									
					<label class="fldLbl">Tiempo restante:</label>
					<span class="couponTimer">
						<?php 
						$fecha_final = date("Y-m-d H:i:s",strtotime($cupon->data[0]->dia_publicacion . ' + '. $cupon->data[0]->duracion_dias .' days'));
						$fecha_final = date("Y-m-d H:i:s",strtotime($fecha_final . ' + '. $cupon->data[0]->duracion_horas .' hours'));
						
						$fecha1 = new DateTime($fecha_final); 
						$fecha2 = new DateTime("now");  
						$dias = $fecha2->diff($fecha1); 
						$dia = $dias->format('%R%a'); 
						$horas = $dias->format('%R%H'); 
						$minutos = $dias->format('%R%I'); 
						$segundos = $dias->format('%R%S'); 
						if($dias->invert == 1)
						{
							?>
							<span class="days">00</span> :
							<span class="hours">00</span> :
							<span class="minutes">00</span> :
							<span class="seconds">00</span>
							
							<?php
						}
						else {
							?>
							<span class="couponDays"><?php echo substr($dia,1) ?></span>d <span class="timerColon">:</span>
							<span class="couponHours"><?php echo substr($horas,1) ?></span>h <span class="timerColon">:</span>
							<span class="couponMinutes"><?php echo substr($minutos,1) ?></span>m <span class="timerColon">:</span>
							<span class="couponSeconds"><?php echo substr($segundos,1) ?></span>s
		
							<?php
						}
						?> 
						 
					</span>
					<br />
					<a class="buyButton orderButton transOn actionBtn" href="/cupon/<?php echo $link . '-' . $cupon->data[0]->idcupon;  ?>">Reservar cupón</a>
					
				</div>	
					
			</div>
			<?php
		}
		?>
		
		<div class="storeDisplayContent">
		
			<?php
			foreach($cupones->data as $c)
			{
				$link = $sM->remove_accents($c->titulo);
				$link = str_replace("-", " ", $link);
				$link = preg_replace('/\s\s+/', ' ', $link);
				$link = str_replace(" ", "-", $link);
				$link = strtolower($link);
				$link = preg_replace('/[^A-Za-z0-9\-]/', '', $link);
				?>
				<div class="itemWrap">
					<a href="/cupon/<?php echo $link . '-' . $c->idcupon;  ?>" class="itemImg">
						<?php 
							if(empty($c->imagen)){
							?>
								<img src="/images/NoImage.png" alt="No image" />
							<?php 
							}
							
							else{
							?>
								<img src="/images/cupon/thumb400/<?php echo $c->imagen ?>" alt="<?php echo $c->titulo ?>" title="<?php echo $c->titulo ?>" />
							<?php 
							}
							?>
					</a>
					<div class="itemDescrip">
						<?php 
							
							?>
							<a class="itemListName"  href="/cupon/<?php echo $link . '-' . $c->idcupon;  ?>"><?php echo $c->titulo ?></a>
							<br />
						<span class="itemListPrice">
							Paga <b>$<?php echo number_format($c->precio_oferta,2,'.',',') ; ?></b> en vez de 
							<b>$<?php echo number_format($c->precio_normal,2,'.',','); ?></b> (-<?php echo number_format((($c->precio_normal - $c->precio_oferta)*100)/$c->precio_normal,2); ?>%)
			 			</span>
					</div>
				</div>
				<?php
			}
			?>
		</div>	
	</div>
	
	<div class="adsWrap">
		<?php 
	 	$query = $con->prepare("SELECT * FROM `anunciopub`
								WHERE  fecha_creacion + INTERVAL duracion DAY >= curdate()
								and terminado = 0
								and posicion = 'banner-right'
								order by rand()
								limit 0,3");
								$query->execute();
								$anuncio = $query->fetchAll(PDO::FETCH_OBJ);
			?>
			
				<?php 
				if(!empty($anuncio[0]->image))
				{
					?>
					<a class="top ads" href="<?php echo $anuncio[0]->link ?>"><img src="/images/publicidad/<?php echo $anuncio[0]->image ?>"></a>
					<?php 
				}
				?>
			
			
				<?php 
				if(!empty($anuncio[1]->image))
				{
					?>
					<a class="mid ads" href="<?php echo $anuncio[1]->link ?>"><img src="/images/publicidad/<?php echo $anuncio[1]->image ?>"></a>
					<?php 
				}
				?>
			
			
				<?php 
				if(!empty($anuncio[2]->image))
				{
					?>
					<a class="bottom ads" href="<?php echo $anuncio[2]->link ?>"><img src="/images/publicidad/<?php echo $anuncio[2]->image ?>"></a>
					<?php
				}
				?>
			
	</div>
	
</div>
<script> 
/* timer script */

var days = parseInt($('.couponDays').text()),
	hours = parseInt($('.couponHours').text()),
	mins = parseInt($('.couponMinutes').text()),
	secs = parseInt($('.couponSeconds').text());
	
	function secondsFN(){
		secs -= 1;
		
		if(secs < 0){
			secs = 59;
			
			mins -= 1
			
			if(mins < 0 ){
				mins = 59;
				
				hours -= 1;
				
				if(hours < 0){
					hours = 23;
					
					days -= 1;
					
					if(days < 0){
						clearInterval(timeInterval);
						
						$('.couponTimer').text('Oferta Terminada');
					}
					
					$('.couponDays').text(days);
					
				}
				
				$('.couponHours').text(hours);
				
			}
			
			$('.couponMinutes').text(mins);
			
		}
		
		$('.couponSeconds').text(secs);
	}
	
	var timeInterval = setInterval(secondsFN, 1000);
</script>

<script> //arranges correctly the sections
	
	if($('.responsive').css('font-size') == '1px'){
		var featSection = $('.featItmWrap');
		
		$('.genContentWrap').append(featSection);
	}
</script>
<?php
	require_once('templates/foo.php');
	
?>