
		</div> <!-- close body -->
		<div id="footer">
			<div class="fooCenter">
				
				<div class="fooGroup socialGroup">
					<h2 class="fooGroupTitle">Síguenos</h2>
					<a class="fooLinks fooIcons socIcons twitter" href="http://www.twitter.com/TuMall"><i class="fa fa-twitter"></i> Twitter</a>
					<a class="fooLinks fooIcons socIcons facebook" href="https://www.facebook.com/pages/TuMall/212274405603265"><i class="fa fa-facebook"></i> Facebook</a>
					<a class="fooLinks fooIcons socIcons instagram" href="http://www.instagram.com/TuMall"><i class="fa fa-instagram"></i> Instagram</a>
				</div>
				
				<div class="fooGroup buyGroup">
					<h2 class="fooGroupTitle">Comprar</h2>
					<a class="fooLinks" href="registro">Crear una cuenta</a>
					<a class="fooLinks" href="cliente/fav/">Tu cuenta</a>
					<a class="fooLinks" href="tiendas">Listado de tiendas</a>
					<a class="fooLinks" href="FAQ#sobre-publicaciones">Seguridad</a>
				</div>
				
				<div class="fooGroup sellGroup">
					<h2 class="fooGroupTitle">Vender</h2>
					<a class="fooLinks" href="FAQ#como-vender">Abrir una tienda</a>
					<a class="fooLinks" href="productos/lista/">Tu tienda</a>
					<a class="fooLinks" href="FAQ#sobre-publicidad">Publicidad</a>
					<a class="fooLinks" href="FAQ#sobre-CFT">Tiendas certificadas</a>
				</div>
				
				<div class="fooGroup helpGroup">
					<h2 class="fooGroupTitle">Ayuda</h2>
					<a class="fooLinks" href="FAQ">Preguntas frecuentes</a>
					<a class="fooLinks" href="contacto">Ayuda</a>
					<a class="fooLinks" href="contacto">Resoluciones</a>
				</div>
				
				<div class="fooGroup aboutGroup">
					<h2 class="fooGroupTitle">Tu Mall</h2>
					<a class="fooLinks" href="acercade">Acerca de</a>
					<a class="fooLinks" href="contacto">Contactos</a>
					<a class="fooLinks" href="contacto">Direcciones</a>
				</div>
					
				<p class="fooNote">Derechos de autor 2013 M&P Group SRL. Todos los derechos reservados. <a href="http://tumall.doTerminos">Términos de uso & Condiciones.</a></p>
				
			</div>
			<p class="designedBy">Designed and developed by <a href="http://mixart.do">Mixart</a></p>
		</div> <!-- close footer -->
	</div> <!-- close container -->
	
	<script>/*  shows retina logo only on high res displays */
		
		$(document).ready(function(){
			if($('.replace2X').css('font-size') == '1px'){
				var imgs = $('img.replace2X').get();
				
				for(var i = 0; i < imgs.length; i++){
					var src = imgs[i].src;
					src = src.replace('.png', '@2x.png');
					imgs[i].src = src;
				}
			}	
		});
		
	</script>	
	
	<script> /* show/hides mobile menu */
	
		function hideMenu(){
			$('.menuTopRight').removeClass('shown');
			$('.mobSearchBtn').removeClass('active');
			$('.pageBlur').remove();
		}
		
		function showMenu(){
			$('.menuTopRight').addClass('shown');
			$('.mobSearchBtn').addClass('active');
			$('body').append('<div class="pageBlur"></div>');
		}
	
		$('.mobSearchBtn').click(function(event){
			
			if($(this).hasClass('active')){
				hideMenu();	
			}
			
			else{
				showMenu();
			}
			
			event.stopPropagation();
			
		});
		
		$('.menuTopRight').click(function(event){
			
			event.stopPropagation();
		});
		
		$('html').click(function(event){
			hideMenu();	
		});
		
	</script>	
	
</body>
</html>