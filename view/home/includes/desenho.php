<?php
if (isset($requisicao)) {


    if ($requisicao == "panel") {
        ?>
        <div class="panel-heading">Desenho</div>
        <div class="panel-body">
            <button id="botaoIn" class="bt-padrao">Iniciar</button>
            <button id="botaoSt" class="bt-padrao">Parar</button>
            <button id="botaoCl" class="bt-padrao">Limpar</button>
            <br />
            <?php echo func_include_x("view/home/includes/motores.php", array("requisicao" => "panel")); ?>
        </div>
        <?php
    } else if ($requisicao == "javascript") {
        ?> 
        <script>
            var iniciado = false;

            $("#botaoIn").click(function () {
                novoMotor(planoPrincipal, caneta, new Ponto(0, ESCALA));
                iniciado = true;
            });
            $("#botaoSt").click(function () {
                for (var i in motores)
                    motores[i].desligar();
                motores = [];
                iniciado = false;
            });
            $("#botaoCl").click(function () {
                //svg.appendChild(new Segmento (new Ponto(100,100), new Ponto(200,200)).toSVG());
                planoPrincipal.getSVG().innerHTML = "";
                if (iniciado) {
                    for (var i in motores)
                        planoPrincipal.getSVG().appendChild(motores[i].ponto.getSVG());
                }
                else
                    caneta.set(0, ESCALA);
            });
        </script>
        <?php
    }
}
?>