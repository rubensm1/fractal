<?php
if (isset($requisicao)) {


    if ($requisicao == "panel") {
        ?>
        <div class="panel-heading">Desenho</div>
        <div class="panel-body">
            <button id="botaoIn" class="bt-padrao">Iniciar</button>
            <button id="botaoSt" class="bt-padrao">Parar</button>
            <button id="botaoCl" class="bt-padrao">Limpar</button>
            <hr />
            <input id="botao-check-pontos" checked type="checkbox" class="checkbox-panel"/>
            <label for="botao-check-pontos" style="margin-right: 20px;">Mostrar Pontos</label>
            <?php echo func_include_x("view/home/includes/motores.php", array("requisicao" => "panel")); ?>
        </div>
        <?php
    } else if ($requisicao == "javascript") {
        ?> 
        <script>
            var iniciado = false;

            $("#botaoIn").click(function () {
                motores.novoMotor(planoPrincipal, new Ponto(0, ESCALA));
            });
            $("#botaoSt").click(function () {
                motores.desligarTudo();
            });
            $("#botaoCl").click(function () {
                motores.limparPlano();
            });
            
            $("#botao-check-pontos").change(function() {
                if (this.checked)
                    motores.colocarPontosPlano();
                else
                    motores.limparPontosPlano();
            });
        </script>
        <?php
    }
}
?>