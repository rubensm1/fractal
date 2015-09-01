<?php
	if (isset ($requisicao) ) {
		
		
		if ($requisicao == "panel") {
			?>
			<div class="panel-heading">Salvar Arquivos</div>
			<div class="panel-body">
				<input id="botao-check-pontos" type="checkbox" class="checkbox-panel"/>
				<label for="botao-check-pontos" style="margin-right: 20px;">Mostrar Pontos</label>
				<button id="botao-gerar-imagem" class="bt-padrao">Gerar PNG</button>
				<!--<button id="botao-limpar-imagem" class="bt-padrao">Limpar</button>-->
			</div>
			<?php 
		}
		
		
		
		else if ($requisicao == "canvas") {
			?>
			<div id="gerar-imagem">
				<canvas id="canvas" width="<?php echo $width; ?>px" height="<?php echo $height; ?>px"></canvas> <img id="imagem" />
			</div>
			<?php 
		}
		
		
		else if ($requisicao == "javascript"){
			?> 
			<script>
				
				$("#botao-gerar-imagem").click (function () {
					var canvas;
					var htmlSVG;
					if ($("#botao-check-pontos")[0].checked) 
						htmlSVG = $("#palco").html().replace(/>\s+/g, ">").replace(/\s+</g, "<").replace(" xlink=", " xmlns:xlink=").replace(/\shref=/g, " xlink:href=");
					else {
						for (var i in motores) 
							motores[i].ponto.getSVG().remove();
						htmlSVG = $("#palco").html().replace(/>\s+/g, ">").replace(/\s+</g, "<").replace(" xlink=", " xmlns:xlink=").replace(/\shref=/g, " xlink:href=");
						for (var i in motores) 
							planoPrincipal.getSVG().appendChild(motores[i].ponto.getSVG());
					}
					$("#gerar-imagem").html("<canvas id=\"canvas\" width=\"<?php echo $width; ?>px\" height=\"<?php echo $height; ?>px\"></canvas> <img id=\"imagem\" />");
					canvas = document.getElementById("canvas");
					canvg(canvas, htmlSVG);
					
					//Convertendo canvas em png
					var data = canvas.toDataURL("image/png");
					$('#imagem').attr('src', data);
					$('#canvas').remove();
					
					$("#gerar-imagem").dialog('open');
				});
				
				/*$("botao-limpar-imagem") .click (function () {
					$("#gerar-imagem").html("<canvas id=\"canvas\" width=\"<?php echo $width; ?>px\" height=\"<?php echo $height; ?>px\"></canvas> <img id=\"imagem\" />");
				});*/
				
				$("#gerar-imagem").dialog({
					title: "Imagem PNG",
					width: 600,
					height: 400,
					autoOpen: false
				});
				
			</script>
		<?php
		}
	}
?>