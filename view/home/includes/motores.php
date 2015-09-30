<?php
if (isset($requisicao)) {


    if ($requisicao == "panel") {
        ?>
            <button id="botaoAvancado" class="bt-padrao">Avançado</button>
        <?php
    } else if ($requisicao == "dialog") {
        ?>
        <div id="dialog-motores">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ordem</th>
                        <th>Tipo</th>
                        <th>Pausar</th>
                        <th>Editar</th>
                        <th>Remover</th>
                    </tr>
                </thead>
                <tbody id="table-motores"></tbody>
            </table>
        </div>
        <div id="dialog-edit-motor">
            <!--<form id="form-edit-motor">-->
                <table class="table table-bordered table-former">
                    <thead>
                        <tr>
                            <th>Motor:</th>
                            <th><output name="id"></output></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Tipo:</td>
                            <td><select name="tipo"><option value="Ponto">Ponto</option><option value="Plano">Plano</option></select></td>
                        </tr>
                        <tr>
                            <td>Espaço:</td>
                            <td><select name="espaco"><option value="0">Raiz</option></select></td>
                        </tr>
                        <tr>
                            <td>Ordem:</td>
                            <td><select name="ordem"><option value="">-</option></select></td>
                        </tr>
                        <tr>
                            <td>Fragmentos:</td>
                            <td style="vertical-align: middle;">
                                <table style="width: 100%;"><tr>
                                    <td style="width: 35px; text-align: left;"><output name="value-fragmentos" /></td>
                                    <td><div name="slider-fragmentos" style="width: calc(100% - 10px); margin: auto;"></div></td>
                                </tr></table>
                            </td>
                        </tr>
                        <tr>
                            <td>Cor:</td>
                            <td><input name="cor" type="color" /></td>
                        </tr>
                        <tr>
                            <td>Raio:</td>
                            <td style="vertical-align: middle;">
                                <table style="width: 100%;"><tr>
                                    <td style="width: 35px; text-align: left;"><output name="value-raio" /></td>
                                    <td><div name="slider-raio" style="width: calc(100% - 10px); margin: auto;"></div></td>
                                </tr></table>
                            </td>
                        </tr>
                        <tr>
                            <td>Largura Linha:</td>
                            <td style="vertical-align: middle;">
                                <table style="width: 100%;"><tr>
                                    <td style="width: 35px; text-align: left;"><output name="value-largura" /></td>
                                    <td><div name="slider-largura" style="width: calc(100% - 10px); margin: auto;"></div></td>
                                </tr></table>
                            </td>
                        </tr>
                        <tr>
                            <td>Sentido:</td>
                            <td style="vertical-align: middle;">
                                <div name="radio-sentidos">
                                    <input type="radio" value="true" id="radio-sentido-horario" name="radio-sentido"><label for="radio-sentido-horario">Horário</label>
                                    <input type="radio" value="false" id="radio-sentido-antihorario" name="radio-sentido"><label for="radio-sentido-antihorario">Anti-horário</label>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            <!--</form>-->
        </div>
        <?php
    } else if ($requisicao == "javascript") {
        ?> 
        <script>
            
            var motores = new ((function () {

                function ListaMotores(planoPrincipal, pulsante) {
                    var _this = this;
                    this.lista = [];
                    this.iniciado = false;
                    this.planoPrincipal = planoPrincipal;
                    this.planos = [planoPrincipal];
                    this.arvoreControle = new Arvore(0, planoPrincipal);
                    this.pulsante = pulsante;
                    this.dialog = $("#dialog-motores").dialog({
                        title: "Motores",
                        width: 600,
                        height: 400,
                        autoOpen: false,
                        close: function() {
                            _this.selecionarLinha(null);
                        }
                    });
                    this.botaoAbrir = $("#botaoAvancado").click(function () {
                        _this.dialog.dialog('open');
                    });
                    this.tbody = $("#table-motores");
                    this.linhaSelecionada = null;
                }

                ListaMotores.prototype.novoMotor = function (param, canetaSelecionada, pontoInicial) {
                    var motor;
                    if (param instanceof Motor) 
                        motor = param;
                    else if (param instanceof Plano)
                        motor = new Motor(param, this.planoPrincipal, canetaSelecionada, pontoInicial);
                    else 
                        throw "Parâmetro Ilegal: " + param;
                    motor.ligar(this.pulsante);
                    this.lista[motor.pulso.pulsoID] = motor;
                    if (motor.ponto instanceof Plano)
                        this.planos[motor.pulso.pulsoID] = motor.ponto;
                    this.arvoreControle.insert(new Arvore(motor.pulso.pulsoID, motor.ponto), this.planos.indexOf(motor.plano));
                    this.iniciado = true;
                    this.printHTML();
                };
                
                ListaMotores.prototype.desligarMotor = function (id) {
                    if (!this.lista.hasOwnProperty(id))
                        throw "Motor não encontrado!";
                        
                    if (motorEdit.motor != null && motorEdit.motor.pulso.pulsoID == id) { 
                        motorEdit.dialog.dialog("close");
                        motorEdit.atualizarListaOrdem();
                        motorEdit.atualizarListaEspacos();
                    }
                    
                    this.lista[id].desligar();
                    delete(this.lista[id]);
                    this.arvoreControle.delete(id);
                    if (this.planos.hasOwnProperty(id))
                        delete(this.planos[id]);
                    
                    if (this.lista.length == 0)
                        this.iniciado = false;
                    this.printHTML();
                };
                
                ListaMotores.prototype.pausarMotor = function (id, param) {
                    if (!this.lista.hasOwnProperty(id))
                        throw "Motor não encontrado!";
                    this.lista[id].pausar(param);
                    this.printHTML();
                };
                
                ListaMotores.prototype.desligarTudo = function (){
                    motorEdit.dialog.dialog("close");
                    for (var i in this.lista)
                        this.lista[i].desligar();
                    this.lista = [];
                    this.planos = [this.planoPrincipal];
                    this.arvoreControle = new Arvore(0, this.planoPrincipal);
                    this.iniciado = false;
                    this.printHTML();
                };
                                
                ListaMotores.prototype.limparPontosPlano = function () {
                    this.planoPrincipal.origem.getSVG().remove();
                    for (var i in this.lista)
                        if (this.planoPrincipal == this.lista[i].planoRoot)
                            this.lista[i].ponto.getSVG().remove();
                };
                
                ListaMotores.prototype.colocarPontosPlano = function () {
                    this.planoPrincipal.getSVG().appendChild(this.planoPrincipal.origem.getSVG());
                    for (var i in this.lista)
                        if (this.planoPrincipal == this.lista[i].planoRoot)
                            this.planoPrincipal.getSVG().appendChild(this.lista[i].ponto.getSVG());
                };
                
                ListaMotores.prototype.limparPlano = function () {
                    //svg.appendChild(new Segmento (new Ponto(100,100), new Ponto(200,200)).toSVG());
                    this.planoPrincipal.getSVG().innerHTML = "";
                    if (this.iniciado) 
                        this.colocarPontosPlano();
                    else {
                        caneta.set(0, ESCALA);
                        this.planoPrincipal.getSVG().appendChild(this.planoPrincipal.origem.getSVG());
                    }
                };
                
                ListaMotores.prototype.selecionarLinha = function (id) {
                    var tr = this.tbody.find("tr[value="+id+"]");
                        
                    if (this.linhaSelecionada)
                        this.tbody.find("tr[value="+this.linhaSelecionada+"]").removeClass("linha-selecionada");
                    tr.addClass("linha-selecionada");
                    this.linhaSelecionada = id;
                    if (id != null || id === 0)
                        colorante.selecionarPiscante(this.lista[id]);
                    else
                        colorante.selecionarPiscante(null);
                };
                
                ListaMotores.prototype.printHTML = function () {
                    var _this = this;
                    this.tbody.html("");
                    var aplicarFuncoes = function (id, tr) {
                        var btPause = $(tr).find("button.botao-select-motor-pause");
                        var btEdit = $(tr).find("button.botao-select-motor-edit");
                        var btRemove = $(tr).find("button.botao-select-motor-remove");
                        $(btPause).button({
                            icons: {primary: (_this.lista[id].isLigado() ? "ui-icon-pause" : "ui-icon-play")},
                            text: false
                        }).click(function () {
                            _this.linhaSelecionada = id;
                            _this.pausarMotor(id);
                            motorEdit.atualizarInterface();
                        });
                        $(btEdit).button({
                            icons: {primary: "ui-icon-circle-close"},
                            text: false
                        }).click(function () {
                            motorEdit.carregarMotor(id, _this.lista[id]);
                        });
                        $(btRemove).button({
                            icons: {primary: "ui-icon-circle-close"},
                            text: false
                        }).click(function () {
                            _this.linhaSelecionada = null;
                            _this.desligarMotor(id);
                        });
                        $(tr).click(function (){
                            _this.selecionarLinha(id);
                        });
                    };
                    var tr;
                    for (var i in this.lista) { 
                        tr = this.lista[i].getHTML();
                        this.tbody.append(tr);
                        aplicarFuncoes(i, tr);
                    }
                    tr = document.createElement("tr");
                    tr.innerHTML = "<td colspan=\"6\"><button class=\"botao-icon botao-new-motor\">Novo</button></td>";
                    this.tbody.append(tr);
                    $(tr).find("button.botao-new-motor").button({
                        icons: {primary: "ui-icon-circle-plus"},
                        text: false
                    }).click(function () {
                        _this.selecionarLinha(null);
                        motorEdit.reset();
                    });
                    $(tr).click(function (){
                        _this.selecionarLinha(null);
                    });
                    
                    this.selecionarLinha(this.linhaSelecionada);
                };
                
                return ListaMotores;

            })())(planoPrincipal, pulsante);
            
            
            
            var motorEdit = new ((function () {

                function EditMotor() {
                    var _this = this;
                    this.motor = null;
                    
                    this.dialog = $("#dialog-edit-motor").dialog({
                        title: "Editar Motor",
                        width: 600,
                        height: 400,
                        autoOpen: false,
                        buttons: [
                            //{text: "Incerir", width: 100, type:"submit", form: "form-edit-motor", click: function(){}},
                            {text: "Salvar Padrão", name: "bt-redefinir", width: 150, click: function (){
                                    _this.redefinirPadrao();
                                }},
                            {text: "Novo", name: "bt-new-clear", width: 100, click: function () {
                                    var texto = $(this).parent().find("button[name='bt-new-clear'] span");
                                    if (texto.text() == "Novo") { 
                                        _this.reset();
                                        texto.text("Criar");
                                    }
                                    else {
                                        _this.novo();
                                        texto.text("Novo");
                                    }
                                }}
                        ]
                    });
                    this.idOutput = this.dialog.find("th output[name='id']");
                    this.tipoSelect = this.dialog.find("td select[name='tipo']");
                    this.espacoSelect = this.dialog.find("td select[name='espaco']");
                    this.ordemSelect = this.dialog.find("td select[name='ordem']");
                    this.fragmentosOutput = this.dialog.find("td output[name='value-fragmentos']");
                    this.fragmentosSlider = this.dialog.find("td div[name='slider-fragmentos']").slider({
                        range: "min",
                        min: 2,
                        max: 1000
                    });
                    this.corInput = this.dialog.find("td input[name='cor']");
                    this.raioOutput = this.dialog.find("td output[name='value-raio']");
                    this.raioSlider = this.dialog.find("td div[name='slider-raio']").slider({
                        range: "min",
                        min: 1,
                        max: <?php echo ($height > $width ? $height : $width); ?>
                    });
                    this.larguraOutput = this.dialog.find("td output[name='value-largura']");
                    this.larguraSlider = this.dialog.find("td div[name='slider-largura']").slider({
                        range: "min",
                        min: 0,
                        max: 30
                    });
                    this.sentidoRadio = this.dialog.find("td div[name='radio-sentidos']").buttonset();
                    //this.sentidoRadio = this.dialog.find("td div[name='radio-sentidos'] input[type='radio']");
                    this.tipoSelect.bind("change", function (){
                        _this.setModo(this.value);
                    });
                }

                EditMotor.prototype.carregarMotor = function (id, motor) {
                    if (motor instanceof Motor) {
                        this.desativarListeners();
                        this.motor = motor;
                        this.dialog.dialog("open");
                        this.dialog.parent().find("button[name='bt-new-clear'] span").text("Novo");
                        this.idOutput.val(id);
                        this.atualizarInterface();
                        this.ativarListeners();
                    }
                    else
                        throw "Motor inválido!";
                };
                
                EditMotor.prototype.atualizarInterface = function () {
                    if (this.motor) {
                        this.atualizarListaOrdem();
                        this.atualizarListaEspacos();
                        this.tipoSelect.val(this.motor.ponto.constructor.name);
                        this.tipoSelect.prop("disabled",true);
                        this.espacoSelect.val(motores.planos.indexOf(this.motor.plano));
                        this.ordemSelect.val(this.motor.pulso.getOrdem());
                        this.fragmentosOutput.val(this.motor.fragmentos);
                        this.fragmentosSlider.slider("value", this.motor.fragmentos);
                        this.corInput.val(this.motor.cor);
                        this.raioOutput.val(this.motor.escala);
                        this.raioSlider.slider("value", this.motor.escala);
                        this.larguraOutput.val(this.motor.larguraLinha);
                        this.larguraSlider.slider("value", this.motor.larguraLinha * 10);
                        this.sentidoRadio.find("input[type='radio']").removeAttr('checked').prop('checked', false);
                        this.sentidoRadio.find("input[type='radio'][value='"+this.motor.sentido+"']").attr('checked', 'checked').prop('checked', true);
                        this.sentidoRadio.buttonset("refresh");
                        this.sentidoRadio.find("label[for='"+(this.motor.sentido ? "radio-sentido-horario" : "radio-sentido-antihorario")+"']").addClass("ui-state-active");
                        this.setModo(this.motor.ponto.constructor.name);
                    }
                    //else
                        //this.reset();
                };
                
                EditMotor.prototype.reset = function () {
                    this.desativarListeners();
                    this.motor = null;
                    this.dialog.dialog("open");
                    this.dialog.parent().find("button[name='bt-new-clear'] span").text("Criar");
                    this.idOutput.val("");
                    //this.atualizarListaOrdem();
                    this.atualizarListaEspacos();
                    this.tipoSelect.val("Ponto");
                    this.tipoSelect.prop("disabled", false);
                    this.espacoSelect.val(0);
                    this.ordemSelect.val("-");
                    this.fragmentosOutput.val(FRAGMENTOS);
                    this.fragmentosSlider.slider("value", FRAGMENTOS);
                    this.corInput.val(COR);
                    this.raioOutput.val(ESCALA);
                    this.raioSlider.slider("value", ESCALA);
                    this.larguraOutput.val(LARGURA_LINHA);
                    this.larguraSlider.slider("value", LARGURA_LINHA*10);
                    this.sentidoRadio.find("input[type='radio']").removeAttr('checked').prop('checked', false);
                    this.sentidoRadio.find("input[type='radio'][value='"+SENTIDO+"']").attr('checked', 'checked').prop('checked', true);
                    this.sentidoRadio.buttonset("refresh");
                    this.sentidoRadio.find("label[for='"+(SENTIDO ? "radio-sentido-horario" : "radio-sentido-antihorario")+"']").addClass("ui-state-active");
                    this.setModo("Ponto");
                };
                
                EditMotor.prototype.novo = function () {
                    var motor = new Motor(
                        motores.planos[parseInt(this.espacoSelect.val())],
                        planoPrincipal,
                        caneta, 
                        (this.tipoSelect.val() == "Plano" ? new Plano(0,0) : new Ponto(0, parseInt(this.raioOutput.val())) ),
                        0,
                        parseInt(this.fragmentosOutput.val()),
                        (this.tipoSelect.val() == "Plano" ? "#000000" :  this.corInput.val()),
                        parseInt(this.raioOutput.val()),
                        (this.tipoSelect.val() == "Plano" ? 1 : parseFloat(this.larguraOutput.val())),
                        JSON.parse(this.sentidoRadio.find("input[type='radio']:checked").val())
                    );
                    motores.novoMotor(motor);
                    this.idOutput.val(motor.pulso.pulsoID);
                    this.motor = motor;
                    this.atualizarInterface();
                    this.dialog.parent().find("button[name='bt-new-clear'] span").text("Novo");
                    this.ativarListeners();
                };
                
                EditMotor.prototype.aplicar = function () {
                    if (!(this.motor instanceof Motor))
                        return;
                    /*$("#duelo-form").submit ( function(ev){
                            ev.preventDefault();
                            var statForm = $("form#stat-form")
                            if (statForm[0].reportValidity()) {
                                    var id = parseInt (ajaxPadrao("duelo","incerir", $(this).serializeObject()) );
                                    $("#duelo-form input[name='id']").val( id );
                                    statForm.serializeArray().map(function (x) {
                                            ajaxPadrao("duelo","stats", {"duelo": id, "tipoRecorde": x.name, "valor": x.value});
                                    });
                            }
                    });*/
                    this.motor.pulso.pulsante.novaOrdem(this.motor.pulso.pulsoID, parseInt(this.ordemSelect.val()));
                    this.motor.fragmentos = parseInt(this.fragmentosInput.val());
                    this.motor.cor = this.corInput.val();
                    this.motor.ponto.editSVG(null, this.motor.cor);
                    motores.printHTML();
                };
                
                EditMotor.prototype.setModo = function (modo) {
                    if (modo == "Ponto") {
                        this.corInput.parent().parent().show();
                        this.larguraSlider.parent().parent().parent().parent().parent().parent().show();
                        this.dialog.parent().find("button[name='bt-redefinir']").show();
                    }
                    else if (modo == "Plano") {
                        this.corInput.parent().parent().hide();
                        this.larguraSlider.parent().parent().parent().parent().parent().parent().hide();
                        this.dialog.parent().find("button[name='bt-redefinir']").hide();
                    }
                };
                
                EditMotor.prototype.redefinirPadrao = function () {
                    FRAGMENTOS = parseInt(this.fragmentosOutput.val());
                    COR = this.corInput.val();
                    ESCALA = parseInt(this.raioOutput.val());
                    LARGURA_LINHA = parseFloat(this.larguraOutput.val());
                    SENTIDO = JSON.parse(this.sentidoRadio.find("input[type='radio']:checked").val());
                };
                
                EditMotor.prototype.atualizarListaOrdem = function (n) {
                    var oldVal = this.ordemSelect.val();
                    if(!n)
                        n = this.motor.pulso.getTotal();
                    this.ordemSelect.html("");
                    var html = "<option value=\"-\">-</option>";
                    for (var i = 1; i <= n; i++)
                        html += "<option value=\""+i+"\">"+i+"</option>";
                    this.ordemSelect.html(html);
                    this.ordemSelect.val(oldVal);
                };
                
                EditMotor.prototype.atualizarListaEspacos = function () {
                    var oldVal = this.espacoSelect.val();
                    this.espacoSelect.html("");
                    var html = "<option value=\"0\">Raiz</option>";
                    if (this.motor) {
                        var myId = parseInt(this.idOutput.val());
                        for (var i in motores.planos) {
                            if (i != 0) {
                                if (this.motor.ponto instanceof Ponto || (myId != i && !motores.arvoreControle.isAncestral(myId, i)) )
                                    html += "<option value=\""+i+"\">"+i+"</option>";
                            }
                            else 
                                continue;
                        }
                    }
                    else {
                        for (var i in motores.planos) {
                            if (i != 0)
                                html += "<option value=\""+i+"\">"+i+"</option>";
                            else 
                                continue;
                        }
                    }
                    this.espacoSelect.html(html);
                    this.espacoSelect.val(oldVal);
                };
                
                EditMotor.prototype.ativarListeners = function () {
                    var _this = this;
                    this.espacoSelect.bind("change", function (){
                        var valor = parseInt(this.value);
                        _this.motor.plano = motores.planos[valor];
                    });
                    this.ordemSelect.bind("change", function (){
                        var valor = parseInt(this.value);
                        _this.motor.mudarOrdem(valor);
                        _this.atualizarListaOrdem();
                        motores.printHTML();
                    });
                    this.fragmentosSlider.slider({
                        slide: function (event, ui) {
                            _this.fragmentosOutput.val(ui.value);
                            _this.motor.fragmentos = parseInt(ui.value);
                        }
                    });
                    this.corInput.bind("change", function (){
                        _this.motor.cor = this.value;
                        _this.motor.ponto.editSVG(null, this.value);
                    });
                    this.raioSlider.slider({
                        slide: function (event, ui) {
                            _this.raioOutput.val(ui.value);
                            _this.motor.escala = parseInt(ui.value);
                        }
                    });
                    this.larguraSlider.slider({
                        slide: function (event, ui) {
                            _this.larguraOutput.val(parseInt(ui.value)/10);
                            _this.motor.larguraLinha = parseInt(ui.value)/10;
                        }
                    });
                    this.sentidoRadio.find("input[type='radio']").bind("click", function () {
                        _this.motor.sentido = JSON.parse(this.value);
                    });
                };
                
                EditMotor.prototype.desativarListeners = function () {
                    var _this = this;
                    this.espacoSelect.unbind("change");
                    this.ordemSelect.unbind("change");
                    this.fragmentosSlider.slider({
                        slide: function (event, ui) {
                            _this.fragmentosOutput.val(ui.value);
                        }
                    });
                    this.corInput.unbind("change");
                    this.raioSlider.slider({
                        slide: function (event, ui) {
                            _this.raioOutput.val(ui.value);
                        }
                    });
                    this.larguraSlider.slider({
                        slide: function (event, ui) {
                            _this.larguraOutput.val(parseInt(ui.value)/10);
                        }
                    });
                    this.sentidoRadio.find("input[type='radio']").unbind("click");
                };
                
                return EditMotor;

            })())(0);
            
            
            var colorante = new ((function () {

                function Colorante() {
                    this.pulsante = new Pulsante(500);
                    this.pisco = false;
                    this.cor = null;
                    this.motor = null;
                    this.pulsoID = null;
                }
                
                Colorante.prototype.selecionarPiscante = function (motor) {
                    var _this = this;
                    if (this.pulsoID != null || this.pulsoID === 0) {
                        this.pulsante.delAcao(this.pulsoID);
                        if (this.motor)
                            this.motor.ponto.editSVG(null, this.getHexRGB());
                        this.motor = null;
                        this.pisco = false;
                        this.cor = null;
                        this.pulsoID == null;
                        this.pulsante.parar();
                    }
                    if (motor == null)
                        return;
                    this.motor = motor;
                    this.cor = this.toGenericRGB(motor.cor);
                    this.pulsoID = this.pulsante.novaAcao(function () {
                        _this.cor = _this.toGenericRGB(motor.cor);
                        if (_this.pisco) {
                            motor.ponto.editSVG(null, _this.getHexRGB(), _this.getHexRGB(_this.getInvertRGB()));
                            _this.pisco = false;
                        }
                        else {
                            motor.ponto.editSVG(null, _this.getHexRGB(_this.getInvertRGB()), _this.getHexRGB());
                            _this.pisco = true;
                        } 
                    }, 0);
                    this.pulsante.iniciar();
                };
                
                Colorante.prototype.getHexRGB = function (cor) {
                    if (cor == null && this.cor == null)
                        return null;
                    if (cor == null)
                        cor = this.cor;
                    return "#" + (cor[0] < 16 ? "0" + cor[0].toString(16) : cor[0].toString(16)) + (cor[1] < 16 ? "0" + cor[1].toString(16) : cor[1].toString(16)) + (cor[2] < 16 ? "0" + cor[2].toString(16) : cor[2].toString(16));
                };
                
                Colorante.prototype.getDecRGB = function (cor) {
                    if (cor == null && this.cor == null)
                        return null;
                    if (cor == null)
                        cor = this.cor;
                    return "rgb(" + cor[0] + "," + cor[1] + "," + cor[2] + ")";
                };
                
                Colorante.prototype.toGenericRGB = function (rgb) {
                    var cores = null;
                    rgb.replace(/\s/g,"");
                    if (/^#?[0-9A-Fa-f]{6}$/.test(rgb)) {
                        rgb = rgb.replace(/#/g,"");
                        cores = [parseInt(rgb.slice(0,2),16) , parseInt(rgb.slice(2,4),16) , parseInt(rgb.slice(4,6),16)];
                    }
                    else if (/rgb\(\d{1,3}\,\d{1,3}\,\d{1,3}\)/.test(rgb) || /rgba\(\d{1,3}\,\d{1,3}\,\d{1,3},(1|0|0.[0-9]+)\)/.test(rgb)) {
                        //rgb = [].slice.call(arguments).join(",").replace(/rgb\(|\)|rgba\(|\)|\s/gi, '').split(',');
                        var cores = rgb.replace(/rgb\(|\)|rgba\(|\)|\s/gi, '').split(',');
                        for (var i = 0; i < 3; i++) {
                            cores[i] = parseInt(cores[i]);
                            if (!(cores[i] >= 0 && cores[i] < 256)) 
                                return null;
                        }
                    }
                    else 
                        return null;
                    return cores;
                };
                
                Colorante.prototype.getInvertRGB = function (cor) {
                    if (cor == null && this.cor == null)
                        return null;
                    if (cor == null)
                        cor = this.cor;
                    var corInv = [];
                    for (var i = 0; i < 3; i++) 
                        corInv[i] = 255 - cor[i];
                    return corInv;
                };
                
                return Colorante;

            })())();
            
            motores.printHTML();
        </script>
        <?php
    }
}