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
                motores.novoMotor(planoPrincipal, caneta, new Ponto(0, ESCALA));
            });
            $("#botaoSt").click(function () {
                motores.desligarTudo();
            });
            $("#botaoCl").click(function () {
                motores.limparPlano();
            });
        </script>
        <?php
    }
}
?>