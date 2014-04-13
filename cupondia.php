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
if(empty($_GET["id"]))
{
	$where = Array(
		'dia_publicacion' => $curdate,
		'estatus' => 1,
		'aprobado' => 1
	);
}
else
{
	$where = Array(
		'idcupon' => $_GET["id"],
		'estatus' => 1,
		'aprobado' => 1
	
	);
}
$cupon = $db->selectRecord('cupon',NULL,$where);
if($cupon->rowcount)
{
$imagenCupon = $db->selectRecord('cupon_galeria',NULL,Array('idcupon' => $cupon->data[0]->idcupon),Array('principal' => 'DESC'));
$posicionCupon = $db->selectRecord('cupon_posicion',NULL,Array('idcupon' => $cupon->data[0]->idcupon));

$cupon_usr = $db->selectRecord('cupon_compras',NULL,Array('idusuario' => $session["usuario"], 'idcupon' => $cupon->data[0]->idcupon));
$cupon_reserv = $db->selectRecord('cupon_compras',NULL,Array('idcupon' => $cupon->data[0]->idcupon));
?> 

<div class="genContentWrap group cupons-wrap"> 
	<div id="itemDetailsWrapper" class="group">
		
		<div class="itemPreviewsWrapper">
			<div class="itemMainPrev">
				
				<?php 
				if(!empty($imagenCupon->data[0]->imagen))
				{
					?>
				<img src="/images/cupon/<?php echo $imagenCupon->data[0]->imagen ?>" alt="<?php echo $cupon->data[0]->titulo; ?>" title="<?php echo $cupon->data[0]->titulo; ?>" />
					<?php 
				}
				
				else{
				?>
					<img src="/images/NoImage.png" alt="No image">
				<?php
				}
				?>			
			</div>		
			<div class="itemSmallerPrevWrapper">
				<?php 
				$smallImgCount = 0; 
				foreach($imagenCupon->data as $ic)
				{
					list($width, $height) = getimagesize("images/cupon/thumb150/".$ic->imagen."");
					$baseDimm = 50;
	
					if($height > $width){
						$ratio = $width/$baseDimm;
						$modVal = $height;
					}
					
					else{
						$ratio = $height/$baseDimm;
						$modVal = $width;
					}
					
					$modVal /= $ratio; 
					$pos = ($modVal - $baseDimm)/2
					
					?> 
						<div class="smallPrev <?php echo $smallImgCount == 0 ? "selectedItem" : "" ?>">
							<img <?php if($height > $width){  ?> width="<?php echo $baseDimm; ?>" height="<?php echo $modVal; ?>" style="top: -<?php echo $pos; ?>px;"  <?php }  else{ ?> height="<?php echo $baseDimm; ?>" width="<?php echo $modVal; ?>" style="left: -<?php echo $pos; ?>px;" <?php } ?> src="/images/cupon/thumb150/<?php echo $ic->imagen ?>" />
						</div>
					<?php
					
					$smallImgCount++;
					}
					?>
			</div>
	
		</div>
		
		<div class="itemInfoWrapper couponInfoWrap">	
			<h2 class="itemName"><?php echo $cupon->data[0]->titulo; ?></h2>
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

			<p> Quedan 
				<?php 
					echo $cupon->data[0]->cantidad - $cupon_reserv->rowcount;
				?> cupones
			</p>
			<?php 
			if($usuario->idtipousuario == 1 and $dias->invert == 0)
			{
				?>
				<form class="reserveCouponForm" method="post" action="/phpfn/add_cupon_usr.php">
					<input type="number" min="1" max="<?php echo $cupon->data[0]->cantidad_compra  - $cupon_usr->rowcount; ?>" class="qtyBox couponQty transOn" value="1" name="cantidad_comprar" onkeypress="return isNumberKey(event)">
					<input type="hidden" name="idCupon" value="<?php echo $cupon->data[0]->idcupon; ?>">
					<button class="buyButton orderButton transOn actionBtn" type="button">Reservar cupón</button>
					<!-- <button class="endOrder orderButton transOn actionBtn" type="submit" style="display: none">Finalizar Orden</button> -->
				</form>
				<?php 
				if($session["error"] == 1)
				{
					?>
					<span class="error-label validation-label">No puedes reservar más de <?php echo $cupon->data[0]->cantidad_compra - $cupon_usr->rowcount; ?> cupones.</span>
					<br />
					<?php
				}
				?>
				<label class="fldLbl">*Tienes <b><?php echo $cupon_usr->rowcount; ?></b><?php echo $cupon_usr->rowcount == 1 ? ' cupon reservado' : ' cupones reservados' ?>, puedes reservar hasta <b><?php echo $cupon->data[0]->cantidad_compra - $cupon_usr->rowcount; ?></b> cupones</label>
				<?php
			}
			else if($dias->invert == 1)
			{
				?>
				<span class="error-label validation-label">La oferta ha finalizado</span>
				<?php
			}
	else if($usuario->idtipousuario <> 1)
	{
		?>
		<span class="error-label validation-label">Necesitas accesar como usuario normal para poder reservar cupones!</span>
		<?php
	}
			
	?>
	
		<div class="actionBar">
		
			<?php $URL = "$_SERVER[REQUEST_URI]"; ?>
			
			<span class="actBarTitle">Compartir:</span>
			<a href="https://twitter.com/share?url=http%3A%2F%2Fwww%2Etumall%2Edo<?php echo $URL; ?>
				&text=Paga $<?php echo number_format($cupon->data[0]->precio_oferta,2,'.',',') ; ?> en vez de 
			$<?php echo number_format($cupon->data[0]->precio_normal,2,'.',','); ?> por <?php echo $cupon->data[0]->titulo; ?>
				&via=TuMall" 
				class="shareToBtn socialShare twettBtn transTw">
				<i class="fa fa-twitter"></i>
			</a>
			
			<a href="https://www.facebook.com/sharer.php?
				s=100
				&p[url]=http%3A%2F%2Fwww%2Etumall%2Edo<?php echo $URL; ?>
				&p[title]=<?php echo $cupon->data[0]->titulo; ?> en Tu Mall
				&p[images][0]=http://www.tumall.do/images/cupon/<?php echo $imagenCupon->data[0]->imagen ?>
				&p[summary]=Paga $<?php echo number_format($cupon->data[0]->precio_oferta,2,'.',',') ; ?> en vez de 
			$<?php echo number_format($cupon->data[0]->precio_normal,2,'.',','); ?>"
			class="shareToBtn socialShare fbShareBtn transTw">
			<i class="fa fa-facebook"></i>
			</a>								
			
			<a href="mailto:?subject=Chequea ésta oferta que encontré en Tu Mall&amp;body=Hola, mira la oferta que encontré en Tu Mall: Paga $<?php echo number_format($cupon->data[0]->precio_oferta,2,'.',',') ; ?> en vez de 
			$<?php echo number_format($cupon->data[0]->precio_normal,2,'.',','); ?> por <?php echo $cupon->data[0]->titulo; ?> TuMall.do" class="shareToBtn messageBtn transTw"><i class="fa fa-envelope"></i></a>
			
		</div>
	
	</div> 
	</div> <!-- end top -->
	
	<h3 class="itemDescrTitle">Detalles de la oferta</h3>
	<div id="itemDescriptionWrapper"> 
		<div class="descrip-area">
			<h3 class="descrip-sub-head">Descripción</h3>
			<?php echo $cupon->data[0]->descripcion ?>
			<h3 class="descrip-sub-head">Caracteristicas</h3>
			<?php echo $cupon->data[0]->caracteristicas ?>
			<h3 class="descrip-sub-head">Condiciones</h3>
			<?php echo $cupon->data[0]->condiciones ?>
		</div>
		
		<div class="map-wrap">
			<h3 class="descrip-sub-head">Dirección y contacto</h3>
			<p><?php echo $cupon->data[0]->contacto ?></p>
			<div id="map-canvas" class="map-canvas"></div>
		</div>
		
		
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



<?php 
}
else
{
	?>
	<p class="deletedItemMsg">No hay cupones para este día.</p>
	<?php
}
?>


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

<script>
	$('.reserveCouponForm').on('click', '.buyButton', function()
	{
		$('.qtyBox').addClass('shown');
		$(this).text('Confirmar orden').removeClass('buyButton').addClass('endOrder');
		/* $('.endOrder').show(); */
		return false;
		
	});
	
	$('.reserveCouponForm').on('click', '.endOrder', function(){
		$('.reserveCouponForm').submit();
	})
	
</script>
<script>
  function isNumberKey(evt)
  {
     var charCode = (evt.which) ? evt.which : event.keyCode
     if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
     return true;

  }

</script>

<script> //selects item image to display when hovering the thumbs
	$('#itemDetailsWrapper').on('mouseenter', '.smallPrev', function(event){
		var hoverElem = $(this).children('img'),
			previewDir = hoverElem.attr('src').split("/")[3],
			previewSrc = hoverElem.attr('src').split("/")[5];
			
			$('.smallPrev').removeClass('selectedItem');
			$(this).addClass('selectedItem');
			
			$('.previewImage').removeClass('active');
			
			if($(this).hasClass('alt')){
				$('.itemMainPrev img').attr('src', '/images/' + previewDir + '/' +previewSrc);
			}
			
			else{
				$('.itemMainPrev img').attr('src', '/images/cupon/' +previewSrc);
			}
			
	});
</script>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCFFubWMV4M8wn1zmkZqCPS2OrSponWpbg&sensor=true"></script>
<script type="text/javascript">
 var locations = [    	<?php foreach($posicionCupon->data as $p)
		{
			$var = $var . '['. substr(substr($p->posicion,0,-1),1) .'],';
		} 
		echo substr($var, 0, -1); ?> 
		];
		
  var map;
  var markers = [];
	function placeMarker(position, map) {
		
	    var  marker = new google.maps.Marker({
	    position: position,
	    map: map,
	    icon : '/images/icono_tumall.png'
	    }); 
	    $('#cuponForm').append("<input type='hidden' name='position[]' class='mapPosition' value='"+ position +"'>");
	    
	    markers.push(marker);
	}
	
	function initialize() {
        var mapOptions = {
          center: new google.maps.LatLng<?php echo $posicionCupon->data[0]->posicion ?>,
          zoom: 15
        };
        map = new google.maps.Map(document.getElementById("map-canvas"),
            mapOptions);
        
        for(i = 0; i < locations.length; i++) { 
    		/*var latlng = new google.maps.LatLng(parseFloat(locations[i][0]), parseFloat(locations[i][1]));
    		placeMarker(latlng,map);*/
    		
    		marker = new google.maps.Marker({
		        position: new google.maps.LatLng(locations[i][0], locations[i][1]),
		        map: map,
		        icon : '/images/icono_tumall.png'
		    });
	     }    
        
        /*google.maps.event.addListener(map, 'click', function(e) {
		    placeMarker(e.latLng, map);
		});*/
      }
      
      function setAllMap(map) {
	  for (var i = 0; i < markers.length; i++) {
	    markers[i].setMap(map);
		  }
		}
		
	  function clearMarkers() {
		  setAllMap(null);
		}
	  function deleteMarkers() {
		  clearMarkers();
		  markers = [];
		  $('.mapPosition').remove();
		}
		
		google.maps.event.addDomListener(window, 'load', initialize);
</script>

<script> /* window pop-ups for social sharing */
	$('.socialShare').click(function(event){
		var URL = $(this).attr('href');
		window.open(URL, 'targetWindow', 'toolbar=no, location=no, status=no, scrollbars=yes, resizable=yes, width=700, height=450, top=50, left=200');
		return false;		
	});
</script>

<?php
	require_once('templates/foo.php');
	
?>