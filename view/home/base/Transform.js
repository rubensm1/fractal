var Transform;

Transform = (function () {


    function Transform(matrix) {

        if (matrix == null)
            this.matrix = document.createElementNS("http://www.w3.org/2000/svg", 'circle').getCTM(document.createElementNS("http://www.w3.org/2000/svg", 'circle'));
        else if (matrix instanceof SVGMatrix)
            this.matrix = matrix;
        else if (matrix instanceof Plano)
            //this.matrix = matrix.getSVG().transform.baseVal.consolidate().matrix
			this.matrix = matrix.getSVG().transform.baseVal.length > 0 ? matrix.getSVG().transform.baseVal.consolidate().matrix : matrix.getSVG().getCTM(document.createElementNS("http://www.w3.org/2000/svg", 'circle'));
        else
            this.matrix = document.createElementNS("http://www.w3.org/2000/svg", 'circle').getCTM(document.createElementNS("http://www.w3.org/2000/svg", 'circle'));

        this.lastMatrix = null;
    }

    Transform.MATRIX_IDENTIDADE = document.createElementNS("http://www.w3.org/2000/svg", 'circle').getCTM(document.createElementNS("http://www.w3.org/2000/svg", 'circle'));

    Transform.prototype.set = function (matrix) {
        if (matrix instanceof SVGMatrix)
            this.matrix = matrix;
    };

    Transform.prototype.translate = function (x, y) {
        this.lastMatrix = Transform.MATRIX_IDENTIDADE.translate(x, y);
        return this.matrix.translate(x, y);
    };

    Transform.prototype.rotate = function (g) {
        this.lastMatrix = Transform.MATRIX_IDENTIDADE.rotate(g);
        return this.matrix.rotate(g);
    };

    Transform.prototype.scale = function (s) {
        this.lastMatrix = Transform.MATRIX_IDENTIDADE.scale(s);
        return this.matrix.scale(s);
    };

    Transform.prototype.aplicar = function (elemento, referencia, appendar) {
        // matrix de transformação
        var transformMatrix;
        if (referencia == null) {
            transformMatrix = this.matrix;
        }
        else if (referencia instanceof SVGMatrix) {
            transformMatrix = referencia;
        }
        else if (referencia instanceof SVGElement) {
            transformMatrix = referencia.getCTM(elemento);
        }
        else
            return;

        // obter SVGTransform e carregar a matrix de transformação 
        var svgTransform = elemento.transform.baseVal.createSVGTransformFromMatrix(transformMatrix);

        // aplicar transformação
        if (appendar)
            elemento.transform.baseVal.appendItem(svgTransform);
        else
            elemento.transform.baseVal.initialize(svgTransform);
    };

    Transform.prototype.pontar = function (ponto) {
        var x = ponto.x * this.matrix.a + ponto.x * this.matrix.c + this.matrix.e;
        var y = ponto.y * this.matrix.b + ponto.y * this.matrix.d + this.matrix.f;

        ponto.set(x, y);
    };

    return Transform;

})();