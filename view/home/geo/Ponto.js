var Ponto;

Ponto = (function () {

    function Ponto(x, y) {
        this.x = x;
        this.y = y;

        var svg = document.createElementNS("http://www.w3.org/2000/svg", 'circle');
        this.getSVG = function () {
            return svg;
        };
        this.set();
        this.editSVG();
    }

    Ponto.prototype.set = function (x, y) {
        var svg = this.getSVG();
        if (x != null || x === 0)
            this.x = x;
        if (y != null || y === 0)
            this.y = y;

        svg.setAttribute("cx", this.x);
        svg.setAttribute("cy", this.y);
    };

    Ponto.prototype.editSVG = function (raio, cor, corLinha) {
        var svg = this.getSVG();
        if (raio == null)
            raio = svg.hasAttribute('r') ? svg.getAttribute('r') : RAIO_PONTOS;
        if (cor == null)
            cor = svg.hasAttribute('fill') ? svg.getAttribute('fill') : COR_PONTOS;
        svg.setAttribute("r", raio);
        svg.setAttribute("fill", cor);
        if (corLinha)
            svg.setAttribute("stroke", corLinha);
        else if(svg.hasAttribute("stroke"))
            svg.removeAttribute('stroke');
    };
    
    Ponto.prototype.getPropSVG = function (prop) {
        var svg = this.getSVG();
        var valor = null;
        switch (prop) {
            case 'r':
                valor = svg.hasAttribute('r') ? svg.getAttribute('r') : RAIO_PONTOS;
                break;
            case 'fill':
                valor = svg.hasAttribute('fill') ? svg.getAttribute('fill') : COR_PONTOS;
                break;
            case 'stroke': 
                valor = svg.hasAttribute('stroke') ? svg.getAttribute('stroke') : null;
                break;
        }
        return valor;
    };

    return Ponto;

})();