<?php 
if($usuario->idtipousuario == 3)
{
	header("location: http://tumall.doadministracion/solicitudes ");
}

?>
<div class="topBar">
	<h2 class="settHead">Realizar solicitud</h2>
</div>
<div class="settWrap requestFormWrap">
	
	<div class="formSelectWrap hideable fluidTransTw">
		<p class="tipRequests popUpMsg">
			Puedes colocar tu publicidad por todo <strong>tuMall</strong>, promocionar una oferta, o certificar tu tienda. Las solicitudes primero son aprobadas por un administrador. Para empezar, selecciona el tipo de solicitud que quieres realizar:
		</p>
		
		<div class="styledSelect formTypeSel">
			<select class="normalSelect selectFormType">
				<option value="0">Tipo de solicitud</option>
				<option value="1">Certificarme</option>
				<option value="2">Publicidad</option>
				<option value="3">Oferta</option>
			</select>
		</div>
	</div>
	<form class="requestForm fluidTransTw" method="post" action="#" enctype="multipart/form-data">
	
	</form>
</div>

<script>

function hideFN(){
	$('.hideable').addClass('hideTop');
	$('.requestForm').addClass('shown');
	
}

function showFN(){
	$('.selectFormType').val(0);
	$('.hideable').removeClass('hideTop');
	$('.requestForm').removeClass('shown');
}

$('.selectFormType').change(function(){
	var formType = $(this).val();

	if (formType == 1)
	{
		$('.requestForm').attr("action", "p/publicidad_func/update_cert.php");
	}
	else if (formType == 2)
	{
		$('.requestForm').attr("action", "p/publicidad_func/update_adver.php");
	}
	else if (formType == 3)
	{
		$('.requestForm').attr("action", "p/publicidad_func/update_offer.php");
	}
	
	if (formType >= 1){
		$.ajax({
			type : "POST",
			url : "p/publicidad_func/get_form.php",
			data: {form : formType}
		}).done(function(html)
		{
			$('.requestForm').html(html);
			hideFN();
		});
	}
});

$('.requestForm').on('click', '.back', function(){
	showFN();
});
	
</script>

<script>
	$(function(){
    	$('.requestForm').on("change",".fileUpload",showPreviewImage_click);
	})

	function showPreviewImage_click(e) {
	    var $input = $(this);
	    var inputFiles = this.files;
	    if(inputFiles == undefined || inputFiles.length == 0) return;
	    var inputFile = inputFiles[0];
	
	    var reader = new FileReader();
	    reader.onload = function(event) {
	    	$input.siblings('.newImage').html('<img src="' + event.target.result + '">')
	        //$input.next().attr("src", event.target.result);
	    };
	    reader.readAsDataURL(inputFile);
	}
</script>
