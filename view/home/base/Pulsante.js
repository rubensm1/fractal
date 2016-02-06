var Pulsante;

Pulsante = (function () {

    function Pulsante(intervalo) {
        this.intervalo = intervalo;
        this.timeoutID = null;
        this.acoes = new Object();
        this.ligado = false;
        this.contAcoes = 0;
        this.ordenador = new Array();
    }

    Pulsante.prototype.run = function () {
        if (this.timeoutID != null) {
            console.error("Pulsante Explodiu!!!");
            return;
        }

        var pulsante = this;
        this.timeoutID = setTimeout(function () {
            pulsante.acao();
        }, this.intervalo);
    };

    Pulsante.prototype.iniciar = function () {
        if (this.ligado) {
            console.warn("Já está ligado!");
            return;
        }
        this.ligado = true;
        this.run();
    };

    Pulsante.prototype.parar = function () {
        clearTimeout(this.timeoutID);
        this.timeoutID = null;
        for (var i in this.acoes)
            delete (this.acoes[i]);
        this.ligado = false;
        this.ordenador = new Array();
        this.contAcoes = 0;
    };

    Pulsante.prototype.pausar = function () {
        clearTimeout(this.timeoutID);
        this.timeoutID = null;
        this.ligado = false;
    };

    Pulsante.prototype.novaAcao = function (funcao, deltha, startAt) {
        if (typeof funcao === "function" && typeof deltha === "number") {
            this.acoes[this.contAcoes] = {funcao: funcao, deltha: deltha, i: 0, startAt: startAt};
            this.ordenador.push(this.contAcoes);
            return this.contAcoes++;
        }
        else
            return 0;
    };

    Pulsante.prototype.delAcao = function (acaoId) {
        if (this.acoes.hasOwnProperty(acaoId)) {
            this.removerOrdem(acaoId);
            delete (this.acoes[acaoId]);
        }
        else
            return false;
        return true;
    };

    Pulsante.prototype.acao = function () {
        for (var i in this.ordenador) {
            if(this.acoes[this.ordenador[i]].startAt) {
                this.acoes[this.ordenador[i]].startAt--;
            }
            else if (this.acoes[this.ordenador[i]].deltha === this.acoes[this.ordenador[i]].i) {
                this.acoes[this.ordenador[i]].funcao();
                this.acoes[this.ordenador[i]].i = 0;
            }
            else
                this.acoes[this.ordenador[i]].i++;
        }
        
        this.timeoutID = null;
        if (this.ligado)
            this.run();
    };
    
    Pulsante.prototype.novaOrdem = function (acaoId, ordem) {
        if (!this.acoes.hasOwnProperty(acaoId) || ordem === undefined || ordem === null || ordem > this.ordenador.length)
            return false;
        
        var indexOld = this.ordenador.indexOf(acaoId);
        if (indexOld >= 0)
            this.ordenador.splice(indexOld, 1);
        this.ordenador.splice(ordem, 0, acaoId);
        
        return true;
    };
    
    Pulsante.prototype.removerOrdem = function (acaoId) {
        var indexOld = this.ordenador.indexOf(acaoId);
        if (indexOld >= 0)
            this.ordenador.splice(indexOld, 1);
        return indexOld;
    };
    
    return Pulsante;

})();