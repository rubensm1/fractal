var Ponto;

Ponto = (function () {

    function Ponto(x, y) {
        this.x = x;
        this.y = y;

        var svg = document.createElementNS("http://www.w3.org/2000/svg", 'circle');
        this.getSVG = function () {
            return svg;
        }
        this.gerarSVG();
    }

    Ponto.prototype.set = function (x, y) {
        var svg = this.getSVG();
        if (x != null || x === 0)
            this.x = x;
        if (y != null || y === 0)
            this.y = y;

        svg.setAttribute("cx", this.x);
        svg.setAttribute("cy", this.y);
    }

    Ponto.prototype.gerarSVG = function (x, y, raio, cor) {
        var svg = this.getSVG();
        if (x != null || x === 0)
            this.x = x;
        if (y != null || y === 0)
            this.y = y;
        if (raio == null)
            raio = svg.hasAttribute('r') ? svg.getAttribute('r') : 4;
        if (cor == null)
            cor = svg.hasAttribute('fill') ? svg.getAttribute('fill') : "red";
        svg.setAttribute("cx", this.x);
        svg.setAttribute("cy", this.y);
        svg.setAttribute("r", raio);
        svg.setAttribute("fill", cor);
    }

    Ponto.prototype.toSVG = function (raio, cor) {
        if (raio == null)
            raio = 4;
        if (cor == null)
            cor = "red";

        var circle = document.createElementNS("http://www.w3.org/2000/svg", 'circle');
        circle.setAttribute("cx", this.x);
        circle.setAttribute("cy", this.y);
        circle.setAttribute("r", raio);
        circle.setAttribute("fill", cor);
        //circle.setAttribute("style","stroke:rgb(0,0,0);stroke-width:1");

        //circle.setAttribute("transform", "matrix(1,0,0,1,100,100)")
        return circle;
    }

    return Ponto;

})();