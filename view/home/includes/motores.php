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
            <table>
                <thead>
                    <tr>
                        <th>Motor:</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
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
                    var tr = this.tbody.find("tr").has("td:first-child[value="+id+"]");
                        
                    if (this.linhaSelecionada)
                        this.tbody.find("tr").has("td:first-child[value="+this.linhaSelecionada+"]").removeClass("linha-selecionada");
                    tr.addClass("linha-selecionada");
                    this.linhaSelecionada = id;
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
                    this.motor = null;
                    this.dialog = $("#dialog-edit-motor").dialog({
                        title: "Editar Motor",
                        width: 600,
                        height: 400,
                        autoOpen: false
                    });
                    this.id = this.dialog.find("th").last();
                }

                EditMotor.prototype.carregarMotor = function (id, motor) {
                    if (motor instanceof Motor) {
                        this.motor = motor;
                        this.dialog.dialog("open");
                        this.id.html(id);
                    }
                    else
                        throw "Motor inválido!";
                };
                
                EditMotor.prototype.aplicar = function () {
                    if (!this.motor instanceof Motor)
                        return;
                };
                
                return EditMotor;

            })())(0);
            
        </script>
        <?php
    }
}