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
                        <th>Pausar</th>
                        <th>Editar</th>
                        <th>Remover</th>
                    </tr>
                </thead>
                <tbody id="table-motores"></tbody>
            </table>
        </div>
        <div id="dialog-edit-motor">
            <form id="form-edit-motor">
                <table class="table table-bordered table-former">
                    <thead>
                        <tr>
                            <th>Motor:</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Ordem:</td>
                            <td><input name="ordem" type="number" value="" /></td>
                        </tr>
                        <tr>
                            <td>Fragmentos:</td>
                            <td><input name="fragmentos" type="number" value="" /></td>
                        </tr>
                        <tr>
                            <td>Cor:</td>
                            <td><input name="cor" type="color" /></td>
                        </tr>
                    </tbody>
                </table>
            </form>
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
                        motor = new Motor(param, param, canetaSelecionada, pontoInicial);
                    else 
                        throw "Parâmetro Ilegal: " + param;
                    motor.ligar(this.pulsante);
                    this.lista[motor.pulso.pulsoID] = motor;
                    this.iniciado = true;
                    this.printHTML();
                };
                
                ListaMotores.prototype.desligarMotor = function (id) {
                    if (!this.lista.hasOwnProperty(id))
                        throw "Motor não encontrado!";
                    this.lista[id].desligar();
                    delete(this.lista[id]);
                    if (this.lista.length == 0)
                        this.iniciado = false;
                    this.printHTML();
                };
                
                ListaMotores.prototype.pausarMotor = function (id) {
                    if (!this.lista.hasOwnProperty(id))
                        throw "Motor não encontrado!";
                    this.lista[id].pausar();
                    this.printHTML();
                };
                
                ListaMotores.prototype.desligarTudo = function (){
                    for (var i in this.lista)
                        this.lista[i].desligar();
                    this.lista = [];
                    this.iniciado = false;
                    this.printHTML();
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
                    for (var i in this.lista) {
                        var tr = this.lista[i].getHTML();
                        this.tbody.append(tr);
                        aplicarFuncoes(i, tr);
                    }
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
                            {text: "Incerir", width: 100, type:"submit", form: "form-edit-motor", click: function(){}},
                            {text: "Aplicar", width: 100, click: function () { _this.aplicar(); }}
                        ]
                    });
                    this.id = this.dialog.find("th").last();
                    this.ordem = this.dialog.find("td input[name='ordem']");
                    this.fragmentos = this.dialog.find("td input[name='fragmentos']");
                    this.cor = this.dialog.find("td input[name='cor']");
                }

                EditMotor.prototype.carregarMotor = function (id, motor) {
                    if (motor instanceof Motor) {
                        this.motor = motor;
                        this.dialog.dialog("open");
                        this.id.html(id);
                        this.ordem.val(motor.pulso.ordem());
                        this.fragmentos.val(motor.fragmentos);
                        this.cor.val(motor.cor);
                    }
                    else
                        throw "Motor inválido!";
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
                    this.motor.pulso.pulsante.novaOrdem(this.motor.pulso.pulsoID, parseInt(this.ordem.val()));
                    this.motor.fragmentos = parseInt(this.fragmentos.val());
                    this.motor.cor = this.cor.val();
                    this.motor.ponto.editSVG(null, this.motor.cor);
                    motores.printHTML();
                };
                
                EditMotor.prototype.ativarListeners = function () {
                    
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
                    var ponto;
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
                    var cor = this.getHexRGB();
                    var corInv = this.getHexRGB(this.getInvertRGB());
                    var colore = this;
                    this.pulsoID = this.pulsante.novaAcao(function () {
                        if (colore.pisco) {
                            motor.ponto.editSVG(null, cor, corInv);
                            colore.pisco = false;
                        }
                        else {
                            motor.ponto.editSVG(null, corInv, cor);
                            colore.pisco = true;
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
            
        </script>
        <?php
    }
}