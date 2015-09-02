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