var Segmento;

Segmento = (function () {

    function Segmento(p1, p2, corLinha, larguraLinha) {
        this.p1 = p1;
        this.p2 = p2;

        var svg = document.createElementNS("http://www.w3.org/2000/svg", 'line');
        this.getSVG = function () {
            return svg;
        };
        this.set();
        this.editSVG(corLinha, larguraLinha);
    }

    Segmento.prototype.set = function (p1, p2) {
        var svg = this.getSVG();
        if (p1 != null)
            this.p1 = p1;
        if (p2 != null)
            this.p2 = p2;

        svg.setAttribute("x1", this.p1.x);
        svg.setAttribute("y1", this.p1.y);
        svg.setAttribute("x2", this.p2.x);
        svg.setAttribute("y2", this.p2.y);
    };

    Segmento.prototype.editSVG = function (corLinha, larguraLinha) {
        var svg = this.getSVG();
        if (corLinha == null)
            corLinha = svg.hasAttribute('stroke') ? svg.getAttribute('stroke') : COR;
        if (larguraLinha == null)
            larguraLinha = svg.hasAttribute('stroke-width') ? svg.getAttribute('stroke-width') : LARGURA_LINHA;
        svg.setAttribute("stroke", corLinha);
        svg.setAttribute("stroke-width", larguraLinha);
    };

    return Segmento;

})();