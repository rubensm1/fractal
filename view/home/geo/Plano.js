var Plano;

Plano = (function () {

    function Plano(x, y) {
        //ponto de origem
        this.x = x ? x : 0;
        this.y = y ? y : 0;


        var svg = document.createElementNS("http://www.w3.org/2000/svg", 'g');
        this.getSVG = function () {
            return svg;
        };

        this.transform = new Transform(this);
        if (this.x || this.y)
            this.translate(this.x, this.y, true);
    }

    Plano.prototype.set = function (x, y) {
        var svg = this.getSVG();
        if (x != null || x === 0)
            this.x = x;
        if (y != null || y === 0)
            this.y = y;

        this.translate(this.x, this.y);
    };

    Plano.prototype.translate = function (x, y, fix) {
        if (typeof fix != "boolean") //Modificação sem Salvar estado
            this.transform.aplicar(this.getSVG(), this.transform.translate(x, y));
        else if (fix) { //modificação salvando estado e aplicando modivicação anterior sem salvar
            this.transform.set(this.transform.matrix.translate(x, y));
            this.transform.aplicar(this.getSVG());
            if (this.transform.lastMatrix)
                this.transform.aplicar(this.getSVG(), this.transform.lastMatrix, true);
        }
        else { //modificação restaurando à 0,0 salvando estado e aplicando modivicação anterior sem salvar
            this.transform.set(this.transform.matrix.translate(x - this.transform.matrix.e, y - this.transform.matrix.f));
            this.transform.aplicar(this.getSVG());
            if (this.transform.lastMatrix)
                this.transform.aplicar(this.getSVG(), this.transform.lastMatrix, true);
        }
    };

    Plano.prototype.rotate = function (g, fix) {
        if (fix) {
            this.transform.set(this.transform.matrix.rotate(g));
            this.transform.aplicar(this.getSVG());
            if (this.transform.lastMatrix)
                this.transform.aplicar(this.getSVG(), this.transform.lastMatrix, true);
        }
        else
            this.transform.aplicar(this.getSVG(), this.transform.rotate(g));
    };

    Plano.prototype.scale = function (s, fix) {
        if (fix) {
            this.transform.set(this.transform.matrix.scale(x, y));
            this.transform.aplicar(this.getSVG());
            if (this.transform.lastMatrix)
                this.transform.aplicar(this.getSVG(), this.transform.lastMatrix, true);
        }
        else
            this.transform.aplicar(this.getSVG(), this.transform.scale(s));
    };

    return Plano;

})();

/*
 var gg = document.createElementNS("http://www.w3.org/2000/svg", 'animateTransform');
 gg.setAttribute('attributeName',"transform");
 gg.setAttribute('attributeType',"XML");
 gg.setAttribute('type',"scale");
 gg.setAttribute('from',"1");
 gg.setAttribute('to',"2");
 gg.setAttribute('dur',"10s");
 gg.setAttribute('repeatCount',"indefinite");
 additive="sum"
 fill="freeze"
 calcMode = "discrete | linear | paced | spline"
 by
 */