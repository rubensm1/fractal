var Pulsante;

Pulsante = (function () {
	
	function Pulsante (intervalo) {
		this.intervalo = intervalo;
		this.timeoutID = null;
		this.acoes = new Object();
		this.ligado = false;
		this.contAcoes = 0;
	}
	
	Pulsante.prototype.run = function() {
		if (this.timeoutID != null) {
			console.error("Pulsante Explodiu!!!");
			return;
		}
		
		var pulsante = this;
		this.timeoutID = setTimeout(function(){
			pulsante.acao();
		}, this.intervalo);
	}
	
	Pulsante.prototype.iniciar = function() {
		if (this.ligado) {
			console.warn("Já está ligado!")
			return;
		}
		this.ligado = true;
		this.run();
	}
	
	Pulsante.prototype.parar = function() {
		clearTimeout(this.timeoutID);
		this.timeoutID = null;
		for (var i in this.acoes) 
			delete (this.acoes[i]);
		this.ligado = false;
		this.contAcoes = 0;
	}
	
	Pulsante.prototype.pausar = function() {
		clearTimeout(this.timeoutID);
		this.timeoutID = null;
		this.ligado = false;
	}
	
	Pulsante.prototype.novaAcao = function (funcao, deltha) {
		if (typeof funcao == "function" && typeof deltha == "number") {
			this.contAcoes++;
			this.acoes[this.contAcoes] = {funcao: funcao, deltha: deltha, i: 0};
			return this.contAcoes;
		}
		else
			return 0;
	}
	
	Pulsante.prototype.delAcao = function (acaoId) {
		if (this.acoes.hasOwnProperty(acaoId))
			delete (this.acoes[acaoId]);
		else
			return false;
		return true;
	}
	
	Pulsante.prototype.acao = function() {
		for (var i in this.acoes) 
			if (this.acoes[i].deltha == this.acoes[i].i) {
				this.acoes[i].funcao();
				this.acoes[i].i = 0;
			}
			else
				this.acoes[i].i++;
		this.timeoutID = null;
		if (this.ligado)
			this.run();
	}
	
	return Pulsante;
	
})();