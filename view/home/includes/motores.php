<?php
if (isset($requisicao)) {


    if ($requisicao == "panel") {
        ?>
            <button id="botaoAvancado" class="bt-padrao">Avançado</button>
        <?php
    } else if ($requisicao == "dialog") {
        ?>
        <div id="dialog-motores">
            
        </div>
        <?php
    } else if ($requisicao == "javascript") {
        ?> 
        <script>
            
            var motores = new ((function () {

                function ListaMotores(planoPrincipal, pulsante) {
                    this.lista = [];
                    this.iniciado = false;
                    this.planoPrincipal = planoPrincipal;
                    this.pulsante = pulsante;
                }

                ListaMotores.prototype.novoMotor = function (param, canetaSelecionada, pontoInicial) {
                    var motor;
                    if (param instanceof Motor)
                        motor = param;
                    else if (param instanceof Plano)
                        motor = new Motor(param, param, canetaSelecionada, pontoInicial);
                    else 
                        throw "Parâmetro Ilegal: " + param;
                    this.lista.push(motor);
                    motor.ligar(this.pulsante);
                    this.iniciado = true;
                };
                
                ListaMotores.prototype.desligarTudo = function (){
                    for (var i in this.lista)
                        this.lista[i].desligar();
                    this.lista = [];
                    this.iniciado = false;
                };
                
                ListaMotores.prototype.limparPontosPlano = function () {
                    for (var i in this.lista)
                        if (this.planoPrincipal == this.lista[i].planoRoot)
                            this.lista[i].ponto.getSVG().remove();
                };
                
                ListaMotores.prototype.colocarPontosPlano = function () {
                    for (var i in this.lista)
                        if (this.planoPrincipal == this.lista[i].planoRoot)
                            this.planoPrincipal.getSVG().appendChild(this.lista[i].ponto.getSVG());
                };
                
                ListaMotores.prototype.limparPlano = function () {
                    //svg.appendChild(new Segmento (new Ponto(100,100), new Ponto(200,200)).toSVG());
                    this.planoPrincipal.getSVG().innerHTML = "";
                    if (this.iniciado) 
                        this.colocarPontosPlano();
                    else
                        caneta.set(0, ESCALA);
                };                
                
                return ListaMotores;

            })())(planoPrincipal, pulsante);
            
            $("#botaoAvancado").click(function () {
                $("#dialog-motores").dialog('open');
            });
            
            $("#dialog-motores").dialog({
                title: "Motores",
                width: 600,
                height: 400,
                autoOpen: false
            });
        </script>
        <?php
    }
}
?>