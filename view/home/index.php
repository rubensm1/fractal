<?php
	global $width, $height;
	if (!isset ($width) || !isset($height)){
		$width = 800;
		$height = 600;
	}
	
?>

<script type="text/javascript">
	//Padrões
	const DESLOCAMENTO_X = 0;
	const DELSOCAMENTO_Y = 0;
	const INTERVALO_TEMPO_PULSOS = 100;
	const INTERVALO_QUANT_PULSOS = 0;
	const FRAGMENTOS = 50;
	const LARGURA_LINHA = 1;
	const COR = "black";
	const ESCALA = <?php echo $height/2; ?>;
	
	$("#input-dimensao-x").attr("placeholder", <?php echo $width; ?>);
	$("#input-dimensao-y").attr("placeholder", <?php echo $height; ?>);
</script>
<script type="text/javascript" src="view/home/geo/Ponto.js"></script>
<script type="text/javascript" src="view/home/base/Transform.js"></script>
<script type="text/javascript" src="view/home/geo/Segmento.js"></script>
<script type="text/javascript" src="view/home/geo/Plano.js"></script>
<script type="text/javascript" src="view/home/base/Pulsante.js"></script>
<script type="text/javascript" src="view/home/base/Motor.js"></script>

<table id="area-total-corpo" style="height: 100%; width: 100%;">
	<tr>
		<td>
			<div class="panel panel-jquery" style="overflow: auto; margin: 10px 5px 10px 10px; width: inherit; height: inherit;">
				<?php echo func_include_x ("view/home/desenho.php", array("requisicao"=> "panel")); ?>
				<?php echo func_include_x ("view/home/tempo.php", array("requisicao"=> "panel")); ?>
				<?php echo func_include_x ("view/home/imagem.php", array("requisicao"=> "panel")); ?>
				<?php echo func_include_x ("view/home/zoom.php", array("requisicao"=> "panel")); ?>
			</div>
		</td>
		<td style="width: 70%">
			<div class="panel panel-jquery" style="overflow: auto; margin: 10px 10px 10px 5px; width: inherit; height: inherit; background-color: whitesmoke;">
				<div id="container-svg" style="height: 100%;">
					<svg id="palco" width="<?php echo $width; ?>px" height="<?php echo $height; ?>px" style="border:1px solid #d3d3d3; background-color: white;">

					</svg>
				</div>
			</div>
		</td>
	</tr>
</table>

<?php echo func_include_x ("view/home/imagem.php", array("requisicao"=> "canvas","width"=> $width, "height"=>$height)); ?>

<script>
	var planoPrincipal = new Plano(<?php echo $width/2; ?>,<?php echo $height/2; ?>);
	document.getElementById("palco").appendChild(planoPrincipal.getSVG());
	
	var caneta = new Ponto(0,ESCALA);
	
	var pulsante = new Pulsante(INTERVALO_TEMPO_PULSOS);
	
	var motores = [];

	
	function novoMotor(plano, canetaSelecionada, pontoInicial) {
		var motor = new Motor(plano, plano, canetaSelecionada, pontoInicial);
		motores.push(motor);
		motor.ligar(pulsante);
	}
	
	$('button.bt-padrao').button().css('width', 120);
	
	function dimensionar() {
		const H_BARSUP = $(".navbar.navbar-inverse.navbar-fixed-top").height();
		const MARGIN = 10;
		const W = $(window).width() - MARGIN * 3;/*largura da janela menos as margens */
		const H = $(window).height() - H_BARSUP - MARGIN * 2;/*altura da janela, menos a barra superior e margens */
		
		var table = $('table#area-total-corpo');
		table.find('td').first().css('height', H);
		table.find('td').first().css('width', W * 3/10);
		table.find('td').last().css('height', H);
		table.find('td').last().css('width', W * 7/10);
	}
	window.onresize = function(){dimensionar()};
	dimensionar();
	
</script>
<?php echo func_include_x ("view/home/tempo.php", array("requisicao"=> "javascript")); ?>
<?php echo func_include_x ("view/home/desenho.php", array("requisicao"=> "javascript")); ?>
<?php echo func_include_x ("view/home/imagem.php", array("requisicao"=> "javascript","width"=> $width, "height"=>$height)); ?>
<?php echo func_include_x ("view/home/zoom.php", array("requisicao"=> "javascript")); ?>