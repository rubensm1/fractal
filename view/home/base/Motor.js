var Motor;

Motor = (function () {

    function Motor(plano, planoRoot, caneta, pontoInicial, intervaloPulsos, fragmentos, cor, escala) {

        this.plano = plano;
        this.planoRoot = planoRoot ? planoRoot : plano;
        this.caneta = caneta;
        this.ponto = pontoInicial;

        this.pulso = null;
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
        /*this.pulsoID = setInterval(function(){
         motor.mover();
         motor.desenhar();
         motor.caneta.set(motor.ponto.x,motor.ponto.y);
         }, this.intervaloPulsos);*/
        var pulsoID = pulsante.novaAcao(function () {
            motor.mover();
            if (motor.ponto instanceof Ponto) {
                motor.desenhar();
                motor.caneta.set(motor.ponto.x, motor.ponto.y);
            }
        }, this.intervaloPulsos);
        this.pulso = {pulsoID: pulsoID, pulsante: pulsante};
        return pulsoID;
    }

    Motor.prototype.pausar = function () {
        if (this.isLigado()) {

        }
        else {

        }
    }

    Motor.prototype.desligar = function () {
        if (!this.isLigado())
            return;
        this.ponto.getSVG().remove();
        //clearInterval(this.pulsoID);
        this.pulso.pulsante.delAcao(this.pulso.pulsoID)
        this.pulso = null;
    }

    Motor.prototype.mover = function () {
        var px, py;
        px = this.escala * Math.sin(2 * Math.PI * this.numerador / this.fragmentos);
        py = this.escala * Math.cos(2 * Math.PI * this.numerador / this.fragmentos);
        this.numerador < this.fragmentos - 1 ? this.numerador++ : this.numerador = 0;
        //this.numerador > 0 ? this.numerador-- : this.numerador = this.fragmentos -1;
        this.ponto.set(px, py);
    }

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
            //transform.aplicar(svgSegmento, this.plano);
            //transform.aplicar(svgSegmento, this.planoRoot.transform.baseVal.consolidate().matrix.inverse(),true);
        }
    }

    Motor.prototype.isLigado = function () {
        return this.pulso != null;
    }

    return Motor;

})();

/*
 var pulsante2 = new Pulsante(50);
 
 var planoG = new Plano (0,100);
 
 motores.push( new Motor(planoPrincipal,planoPrincipal,caneta,planoG) );
 motores[0].ligar(pulsante);
 
 motores.push( new Motor(planoG,planoPrincipal,caneta,new Ponto(0,150), INTERVALO_QUANT_PULSOS, FRAGMENTOS, COR, 150 ));
 motores.push( new Motor(planoG,planoPrincipal,caneta,new Ponto(0,150), INTERVALO_QUANT_PULSOS, FRAGMENTOS, COR, 150 ));
 
 motores[1].ligar(pulsante);
 motores[2].ligar(pulsante);
 iniciado = true;
 */