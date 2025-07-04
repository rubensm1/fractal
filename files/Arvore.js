var Arvore;

Arvore = (function () {

    function Arvore(id, dado) {
        this.id = id;
        this.dado = dado;
        this.pai =  null;
        this.filhos = [];
    }
    
    Arvore.prototype.find = function (id) {
        if (this.id == id || id == null)
            return this;
        for (var i in this.filhos) {
            var buscado = this.filhos[i].find(id);
            if(buscado)
                return buscado;
        }
        return null;
    };
    
    Arvore.prototype.insert = function (filho, paiId) {
        if (!(filho instanceof Arvore))
            return false;
        
        var pai = this.find(paiId);
        if (pai) {
            pai.filhos[filho.id] = filho;
            filho.pai = pai;
        }
        else {
            this.filhos[filho.id] = filho;
            filho.pai = this;
        } 
    };
    
    Arvore.prototype.delete = function (id) {
        var nodo = this.find(id);
        if (nodo) {
            if (nodo.pai == null)
                return null;
            //for (var i in nodo.filhos)
                //nodo.filhos[i].delete();
            nodo.upper();
            delete(nodo.pai.filhos[id]);
        }
        return nodo;
    };
    
    Arvore.prototype.upper = function () {
        if (this.pai){
            for (var i in this.filhos) {
                this.filhos[i].pai = this.pai;
                this.pai.filhos[i] = this.filhos[i];
            }
            this.filhos = [];
        }
        else
            return false;
        return true;
    };
    
    Arvore.prototype.change = function (id, novoPaiId) {
        var nodo = this.delete(id);
        this.insert(nodo, novoPaiId);
    };
    
    Arvore.prototype.isAncestral = function (id, descendenteId) {
        var nodo = this.find(id);
        if (nodo.find(descendenteId)) 
            return true;
        else 
            return false;
    };

    return Arvore;

})();
