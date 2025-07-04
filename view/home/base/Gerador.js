var Gerador;

Gerador = (function() {

    function Gerador() {

    }

    Gerador.prototype.mmc = function(num1, num2) {
        var resto, a, b;
        a = num1;
        b = num2;
        do {
            resto = a % b;
            a = b;
            b = resto;
        } while (resto != 0);
        return (num1 * num2) / a;
    }

    Gerador.prototype.geraEscalas = function() {
        //return 250;
        return 1 + Math.floor(Math.random() * (ESCALA - 1));
    }

    Gerador.prototype.teste = function() {
        var planoG = new Plano(0, 0);
        var partEscala = this.geraEscalas();
        var fragPlan = (20 + Math.floor(Math.random() * 80)) * 5;
        var fragPonts = (20 + Math.floor(Math.random() * 80)) * 5;
        motores.novoMotor(new Motor(planoPrincipal, planoPrincipal, motores.getPincel(), planoG, INTERVALO_QUANT_PULSOS, fragPlan, COR, partEscala));
        motores.novoMotor(new Motor(planoG, planoPrincipal, motores.getPincel(), new Ponto(0, 150), INTERVALO_QUANT_PULSOS, fragPonts, COR, ESCALA - partEscala, 0.2, !SENTIDO), null, 0);
        motores.novoMotor(new Motor(planoG, planoPrincipal, motores.getPincel(), new Ponto(0, 150), INTERVALO_QUANT_PULSOS, fragPonts, COR, ESCALA - partEscala, 0.2, !SENTIDO), null, 10);
        //motores.novoMotor(new Motor(planoG, planoPrincipal, motores.getPincel(), new Ponto(0, 150), INTERVALO_QUANT_PULSOS, fragPonts, COR, ESCALA - partEscala, 0.2, !SENTIDO), null, Math.floor(fragPonts / 2));

        var pulsoID = pulsante.novaAcao(function() {
            motores.desligarTudo();
            pulsante.delAcao(pulsoID);
        }, this.mmc(fragPlan, fragPonts));

        console.log("Escalas: " + partEscala + ", " + (ESCALA - partEscala));
        console.log("Fragmentos: " + fragPlan + ", " + fragPonts);
        console.log("Ações total: " + this.mmc(fragPlan, fragPonts));

    }

    return Gerador;
})();