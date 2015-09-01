var Segmento;

Segmento = (function () {
	
	function Segmento (p1,p2,corLinha,larguraLinha) {
		this.p1 = p1;
		this.p2 = p2;
		
		var svg = document.createElementNS("http://www.w3.org/2000/svg", 'line');
		this.getSVG = function () {
			return svg;
		}
		this.gerarSVG(corLinha,larguraLinha);
	}
	
	Segmento.prototype.set = function(p1,p2) {
		var svg = this.getSVG();
		if (p1 != null)
			this.p1 = p1;
		if (p2 != null)
			this.p2 = p2;
		
		svg.setAttribute("x1",this.p1.x);
		svg.setAttribute("y1",this.p1.y);
		svg.setAttribute("x2",this.p2.x);
		svg.setAttribute("y2",this.p2.y);
	}
	
	Segmento.prototype.gerarSVG = function(corLinha,larguraLinha) {
		var svg = this.getSVG();
		/*if (p1 != null)
			this.p1 = p1;
		if (p2 != null)
			this.p2 = p2;*/
		if(corLinha == null)
			corLinha = svg.hasAttribute ('stroke') ? svg.getAttribute('stroke') : COR;
		if(larguraLinha == null)
			larguraLinha = svg.hasAttribute ('stroke-width') ? svg.getAttribute('stroke-width') : LARGURA_LINHA;
		svg.setAttribute("x1",this.p1.x);
		svg.setAttribute("y1",this.p1.y);
		svg.setAttribute("x2",this.p2.x);
		svg.setAttribute("y2",this.p2.y);
		svg.setAttribute("stroke",corLinha);
		svg.setAttribute("stroke-width",larguraLinha);
	}
	
	
	Segmento.prototype.toSVG = function(corLinha,larguraLinha) {
		if (corLinha == null)
			corLinha = "rgb(0,0,0)";
		if (larguraLinha == null)
			larguraLinha == 1;
		
		var line = document.createElementNS("http://www.w3.org/2000/svg", 'line');
		line.setAttribute("x1",this.p1.x);
		line.setAttribute("y1",this.p1.y);
		line.setAttribute("x2",this.p2.x);
		line.setAttribute("y2",this.p2.y);
		line.setAttribute("stroke",corLinha);
		/*switch (color) {
			case 0:
				line.setAttribute("stroke","rgb(255,255,0)"); color++;
				break;
			case 1:
				line.setAttribute("stroke","rgb(255,0,0)"); color++;
				break;
			case 2:
				line.setAttribute("stroke","rgb(0,0,255)"); color++;
				break;
			case 3:
				line.setAttribute("stroke","rgb(0,255,0)"); color = 0;
				break;
		}*/
		line.setAttribute("stroke-width",larguraLinha);
		//line.setAttribute("transform", "matrix(1,0,0,1,100,100)")
		return line;
	}
	
	return Segmento;
	
})();