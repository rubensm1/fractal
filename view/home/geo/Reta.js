var Reta;

Reta = (function () {

    function Reta(p1, p2) {
		if (typeof p1 == "number" && typeof p2 == "number") {
			this.a = p1;
			this.b = p2;
		}
        else if (p1 instanceof Ponto && p2 instanceof Ponto) {
			this.a = (p2.y - p1.y) / (p2.x - p1.x);
			this.b = (p1.x * p2.y - p2.x * p1.y) / (p1.x - p1.x)
		}
		else {
			this.a = 0;
			this.b = 0;
		}
    }

    return Reta;

})();