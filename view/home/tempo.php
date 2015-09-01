<?php
	if (isset ($requisicao) ) {
		
		
		if ($requisicao == "panel") {
			?>
			<div class="panel-heading">Controles de Tempo</div>
				<div class="panel-body">
					<table style="margin: auto;">
						<tr>
							<td><label for="amount-num-pulsos">Nº. Pulsos:</label></td>
							<td><input type="number" id="amount-num-pulsos" style="text-align: center; width: 75px;" value="0" readonly></input></td>
							<td><button id="botaoControlePulsante" class="botao-icon">Iniciar/Pausar</button></td>
							<!--<td><button id="botaoPararPulsante">Parar</button></td>-->
						</tr>
					</table>
					<table style="margin: auto;">
						<tr>
							<!--<td><label>Intervalo:</label></td>
							<td><input type="number" class="form-control" placeholder="Search for..."></td>
							<td><button id="botaoAplicarPulsante">Aplicar</button></td>-->
							<button id="botaoTempoReset" class="botao-icon">Voltar ao Padrão</button>
							<label for="amount-tempo">Intervalo:</label><input type="text" id="amount-tempo" style="width: 60px; text-align: center" value="" readonly></input>
							<div id="slider-tempo" style="width: calc(100% - 10px); margin: auto;"></div>
						</tr>
					</table>
				</div>
			<?php 
		}
		
		
		
		else if ($requisicao == "javascript"){
			?> 
			<script>
				
				var contadorPulsos = new ( (function () {
	
					function ContadorPulsos (pulsador,textExibicao) {
						this.i = 0;
						this.textExibicao = textExibicao;
						var eu = this;
						var pulsoContadorID = pulsador.novaAcao(function(){
							eu.i++;
							$(textExibicao).val(eu.i);
						},0);
						this.pulso = {pulsoID: pulsoContadorID, pulsante: pulsador};
					}
					
					ContadorPulsos.prototype.reset = function() {
						this.i = 0;
						$(this.textExibicao).val(0);
					}
					
					ContadorPulsos.prototype.reestabelecer = function() {
						if (this.pulso.pulsoID)
							return;
						else {
							this.i = 0;
							var eu = this;
							this.pulso.pulsoID = this.pulso.pulsante.novaAcao(function(){
								eu.i++;
								$(eu.textExibicao).val(eu.i);
							},0);
						}
					}
					
					ContadorPulsos.prototype.cancelar = function() {
						this.pulso.pulsante.delAcao(this.pulso.pulsoID)
						this.pulso.pulsoID = null;
					}
					
					return ContadorPulsos;
	
				})() ) (pulsante,$("#amount-num-pulsos"));
				
				pulsante.iniciar();
				
				
				$("#botaoControlePulsante").button({
					icons: {primary: "ui-icon-pause"},
					text: false
				}).click(function() {
					if (pulsante.ligado) {
						pulsante.pausar();
						$(this).button({
							icons: {primary: "ui-icon-play"}
						});
					}
					else{
						pulsante.iniciar();
						$(this).button({
							icons: {primary: "ui-icon-pause"}
						});
						//contadorPulsos.reestabelecer();
					}
				});
				
				/*
				$("#botaoPararPulsante").button({
					icons: {primary: "ui-icon-stop"},
					text: false
				}).click(function() {
					if (pulsante.ligado) {
						$("#botaoControlePulsante").button({
							icons: {primary: "ui-icon-play"}
						});
					}
					contadorPulsos.cancelar();
					pulsante.parar();
					contadorPulsos.reset();
				});*/
				
				
				
				$( "button#botaoTempoReset" ).button({
					icons: {primary: "ui-icon-circle-close"},
					text: false,
				}).click( function() {
					$( "#slider-tempo" ).slider("value", INTERVALO_TEMPO_PULSOS/10);
					$("#amount-tempo").val( (INTERVALO_TEMPO_PULSOS) + "ms");
					pulsante.intervalo = INTERVALO_TEMPO_PULSOS;
				});
				
				$( "#slider-tempo" ).slider({
					range: "min",
					value: pulsante.intervalo/10,
					min: 1,
					max: 50,
					slide: function( event, ui ) {
						$("#amount-tempo").val( (ui.value*10) + "ms");
						pulsante.intervalo = ui.value*10;
					}
				});
				$("#amount-tempo").val( pulsante.intervalo + "ms");
			</script>
		<?php
		}
	}
?>