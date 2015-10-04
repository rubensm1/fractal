<?php
if (isset($requisicao)) {

    if ($requisicao == "panel") {
        ?>
        <div class="panel-heading">Dimensionamento</div>
        <div class="panel-body">
            <button id="botaoZoomReset" class="botao-icon">Voltar ao Padrão</button>
            <label for="amount-zoom">Zoom:</label><input type="text" id="amount-zoom" style="width: 60px; text-align: center" value="1x" readonly />
            <input id="range-zoom" type="range" style="width: calc(100% - 10px); margin: auto;" />

            <button id="botaoScaleReset" class="botao-icon">Voltar ao Padrão</button>
            <label for="amount-scale">Scale:</label><input type="text" id="amount-scale" style="width: 60px; text-align: center" value="1x" readonly />
            <input id="range-scale" type="range" style="width: calc(100% - 10px); margin: auto;" />
            
            <button id="botaoTranslateResetX" class="botao-icon">Voltar ao Padrão</button>
            <label for="amount-translate-x">Translate X:</label><input type="text" id="amount-translate-x" style="width: 60px; text-align: center" value="0" readonly/>
            <input id="range-translate-x" type="range" style="width: calc(100% - 10px); margin: auto;" />
            
            <button id="botaoTranslateResetY" class="botao-icon">Voltar ao Padrão</button>
            <label for="amount-translate-y">Translate Y:</label><input type="text" id="amount-translate-y" style="width: 60px; text-align: center" value="0" readonly/>
            <input id="range-translate-y" type="range" style="width: calc(100% - 10px); margin: auto;" />
        </div>
        <?php
    } else if ($requisicao == "javascript") {
        ?> 
        <script>

            var zoom = 1;
            var scale = 1;

            function setZoom(z, fromSlider) {
                var ranz = document.getElementById("range-zoom");
                if (fromSlider) {
                    if (z > 50) {
                        if (z < 60)
                            zoom = (z - 50) / 10 + 1;
                        else
                            zoom = (z - 50) / 5;
                    }
                    else {
                        if (z >= 10)
                            zoom = z / 50;
                        else
                            zoom = (z + 9) / 100;
                    }   
                }
                else {
                    zoom = z;
                    if (z > 1) {
                        if (z < 2)
                            z = (z - 1) * 10 + 50;
                        else
                            z = z * 5 + 50;
                    }
                    else
                        z = z * 50;
                    ranz.value = z;
                }
                $("#amount-zoom").val(zoom + "x");
                $("#container-svg").css("zoom", zoom);
            }

            function setScale(s, fromSlider) {
                var rans = document.getElementById("range-scale");
                if (fromSlider) {
                    if (s > 50) {
                        if (s < 60)
                            scale = (s - 50) / 10 + 1;
                        else
                            scale = (s - 50) / 5;
                    }
                    else {
                        if (s >= 10)
                            scale = s / 50;
                        else
                            scale = (s + 9) / 100;
                    }
                }
                else {
                    scale = s;
                    if (s > 1) {
                        if (s < 2)
                            s = (s - 1) * 10 + 50;
                        else
                            s = s * 5 + 50;
                    }
                    else
                        s = s * 50;
                    rans.value = s;
                }
                $("#amount-scale").val(scale + "x");
                planoPrincipal.scale(scale);
                with (document.getElementById("range-translate-x")) {
                    setAttribute("min", - (<?php echo $width; ?> * scale/2 + <?php echo $width/2; ?>) );
                    setAttribute("max", (<?php echo $width; ?> * scale/2  + <?php echo $width/2; ?>) );
                }
                with (document.getElementById("range-translate-y")) {
                    setAttribute("min", - (<?php echo $height; ?> * scale/2 + <?php echo $height/2; ?>) );
                    setAttribute("max", (<?php echo $height; ?> * scale/2  + <?php echo $height/2; ?>) );
                }
            }

            function setTranslate (x, y, fromSlider) {
                if (!fromSlider) {
                    document.getElementById("range-translate-x").value = x;
                    document.getElementById("range-translate-y").value = y;
                }
                $("#amount-translate-x").val(x);
                $("#amount-translate-y").val(y);
                planoPrincipal.translate(x,y,false);
            }
            
            $("button#botaoZoomReset").button({
                icons: {primary: "ui-icon-circle-close"},
                text: false,
            }).click(function () {
                setZoom(1);
            });
            
            with (document.getElementById("range-zoom")) {
                setAttribute("min", 1);
                setAttribute("max", 100);
                setAttribute("value", 50);
                oninput = function () {
                    setZoom(parseInt(this.value), true);
                };
            }

            $("button#botaoScaleReset").button({
                icons: {primary: "ui-icon-circle-close"},
                text: false
            }).click(function () {
                setScale(1);
            });
            
            with (document.getElementById("range-scale")) {
                setAttribute("min", 1);
                setAttribute("max", 100);
                setAttribute("value", 50);
                oninput = function () {
                    setScale(parseInt(this.value), true);
                };
            }
            
            $("button#botaoTranslateResetX").button({
                icons: {primary: "ui-icon-circle-close"},
                text: false
            }).click(function () {
                setTranslate(0, parseInt(document.getElementById("range-translate-y").value));
            });
            
            with (document.getElementById("range-translate-x")) {
                setAttribute("min", -<?php echo $width; ?>);
                setAttribute("max", <?php echo $width; ?>);
                setAttribute("value", 0);
                oninput = function () {
                    setTranslate(parseInt(this.value), parseInt(document.getElementById("range-translate-y").value), true);
                };
            }
            
            $("button#botaoTranslateResetY").button({
                icons: {primary: "ui-icon-circle-close"},
                text: false
            }).click(function () {
                setTranslate(parseInt(document.getElementById("range-translate-x").value), 0);
            });
            
            with (document.getElementById("range-translate-y")) {
                setAttribute("min", -<?php echo $height; ?>);
                setAttribute("max", <?php echo $height; ?>);
                setAttribute("value", 0);
                oninput = function () {
                    setTranslate(parseInt(document.getElementById("range-translate-x").value), parseInt(this.value), true);
                };
            }
        </script>
        <?php
    }
}
?>