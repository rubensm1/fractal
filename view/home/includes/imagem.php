<?php
if (isset($requisicao)) {


    if ($requisicao == "panel") {
        ?>
        <div class="panel-heading">Salvar Arquivos</div>
        <div class="panel-body">
            <button id="botao-gerar-imagem" style="width: 150px">Gerar Imagem</button>
            <!--<button id="botao-limpar-imagem" class="bt-padrao">Limpar</button>-->
        </div>
        <?php
    } else if ($requisicao == "canvas") {
        ?>
        <div id="gerar-imagem">
            <canvas id="canvas" width="<?php echo $width; ?>px" height="<?php echo $height; ?>px"></canvas> <img id="imagem" />
        </div>
        <?php
    } else if ($requisicao == "javascript") {
        ?> 
        <script>

            $("#botao-gerar-imagem").button().click(function () {
                var canvas;
                var htmlSVG = $("#palco").html().replace(/>\s+/g, ">").replace(/\s+</g, "<").replace(" xlink=", " xmlns:xlink=").replace(/\shref=/g, " xlink:href=");
                $("#gerar-imagem").html("<canvas id=\"canvas\" width=\"<?php echo $width; ?>px\" height=\"<?php echo $height; ?>px\"></canvas> <img id=\"imagem\" />");
                canvas = document.getElementById("canvas");
                canvas.getContext("2d").translate(<?php echo $width/2; ?>,<?php echo $height/2; ?>);
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