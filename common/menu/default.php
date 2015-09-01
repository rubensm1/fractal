<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		  <!--a class="navbar-brand" href="./" style="background-image: url('common/images/aaa.png'); background-repeat: no-repeat; background-size: contain; color: transparent;">----------------</a-->
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li><a href="./">Home</a></li>
			</ul>
			<form class="navbar-form navbar-right" action="index.php?view=home&action=index&method=true" method="POST">
				<div class="form-group">
					<label>Dimensão:</label>
					<input id="input-dimensao-x" name="width" type="number" class="form-control" min="100" max="5000" title="Largura da Área de Desenho em px" required>
					<label>X</label>
					<input id="input-dimensao-y" name="height" type="number" class="form-control" min="100" max="5000" title="Altura da Área de Desenho em px" required>
					<label> </label>
				</div>
				<label> </label>
				<button type="submit" class="btn btn-default">OK</button>
			</form>
		</div><!--/.nav-collapse -->
	</div>
</nav>