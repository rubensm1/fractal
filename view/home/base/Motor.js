var Motor;

Motor = (function () {

    function Motor(plano, planoRoot, caneta, pontoInicial, intervaloPulsos, fragmentos, cor, escala) {

        this.plano = plano;
        this.planoRoot = planoRoot ? planoRoot : plano;
        this.caneta = caneta;
        this.ponto = pontoInicial;

        this.pulso = null; //{pulsoID: pulsoID, pulsante: pulsante, ordemBack: 1, ligado: true}
        this.intervaloPulsos = intervaloPulsos ? intervaloPulsos : INTERVALO_QUANT_PULSOS;
        this.fragmentos = fragmentos ? fragmentos : FRAGMENTOS;
        this.numerador = 0;
        this.cor = cor ? cor : COR;
        this.escala = escala ? escala : ESCALA;
    }

    Motor.prototype.ligar = function (pulsante) {
        if (this.isLigado() || !(pulsante instanceof Pulsante))
            return;

        this.planoRoot.getSVG().appendChild(this.ponto.getSVG());

        var motor = this;
        var pulsoID = pulsante.novaAcao(function () {
            motor.mover();
            if (motor.ponto instanceof Ponto) {
                motor.desenhar();
                motor.caneta.set(motor.ponto.x, motor.ponto.y);
            }
        }, this.intervaloPulsos);
        this.pulso = {pulsoID: pulsoID, pulsante: pulsante, ordemBack: null, ligado: true, ordem: function () {var ret = pulsante.ordenador.indexOf(pulsoID); return ret < 0 ? "-" : ret;}};
        return pulsoID;
    };

    Motor.prototype.pausar = function () {
        if (this.isLigado()) {
            this.pulso.ordemBack = this.pulso.pulsante.removerOrdem(this.pulso.pulsoID);
            this.pulso.ligado = false;
        }
        else {
            this.pulso.pulsante.novaOrdem(this.pulso.pulsoID, this.pulso.ordemBack);
            this.pulso.ordemBack = null;
            this.pulso.ligado = true;
        }
        return this.pulso.ligado;
    };

    Motor.prototype.desligar = function () {
        if (this.pulso == null)
            return;
        this.ponto.getSVG().remove();
        this.pulso.pulsante.delAcao(this.pulso.pulsoID);
        this.pulso = null;
    };

    Motor.prototype.mover = function () {
        var px, py;
        px = this.escala * Math.sin(2 * Math.PI * this.numerador / this.fragmentos);
        py = this.escala * Math.cos(2 * Math.PI * this.numerador / this.fragmentos);
        this.numerador < this.fragmentos - 1 ? this.numerador++ : this.numerador = 0;
        //this.numerador > 0 ? this.numerador-- : this.numerador = this.fragmentos -1;
        this.ponto.set(px, py);
    };

    /*Motor.prototype.mover = function () {
     var px, py;
     px = this.numerador;
     py = 0.01 *(px * px) - 300;
     //py = -0.01 *(px * px) + 300;
     //py = 0.00002*(px * px *px);
     this.numerador < 220 ? this.numerador+=4 : this.numerador = -220;
     this.ponto.set(px,py);
     }*/

    Motor.prototype.desenhar = function () {
        if (this.plano == this.planoRoot) {
            var svgSegmento = new Segmento(this.caneta, this.ponto, this.cor).getSVG();
            this.plano.getSVG().appendChild(svgSegmento);
        }
        else {
            var transform = new Transform(this.plano);
            transform.pontar(this.ponto);
            var svgSegmento = new Segmento(this.caneta, this.ponto, this.cor).getSVG();
            this.planoRoot.getSVG().appendChild(svgSegmento);
        }
    };

    Motor.prototype.isLigado = function () {
        return this.pulso !== null && this.pulso.ligado;
    };
    
    Motor.prototype.getHTML = function () {
        if (this.pulso === null)
            return null;
        var tr = document.createElement("tr");
        tr.innerHTML = "<td value=\""+this.pulso.pulsoID+"\">"+this.pulso.pulsoID+"</td>" + 
                "<td>"+this.pulso.ordem()+"</td>" +
                //'<td><input type="radio" name="group1" value="Milk" /></td>' +
                '<td><button class="botao-icon botao-select-motor-pause">Iniciar/Pausar</button></td>' +
                '<td><button class="botao-icon botao-select-motor-edit">Editar</button></td>' +
                '<td><button class="botao-icon botao-select-motor-remove">Remover</button></td>';
        return tr;
    };

    return Motor;

})();

/*
 var planoG = new Plano (0,0);
 
 motores.novoMotor( new Motor(planoPrincipal,planoPrincipal,caneta,planoG) );
 motores.novoMotor( new Motor(planoG,planoPrincipal,caneta,new Ponto(0,150), INTERVALO_QUANT_PULSOS, FRAGMENTOS, COR, 150 ));
 motores.novoMotor( new Motor(planoG,planoPrincipal,caneta,new Ponto(0,150), INTERVALO_QUANT_PULSOS, FRAGMENTOS, COR, 150 ));
 
 */