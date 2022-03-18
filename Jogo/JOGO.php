<?php
session_start();
include "../verificarSessao.php"

?>



<!DOCTYPE html>
<html lang="pt">

<head>
	<meta charset="utf-8" />
	<title>💣 Play</title>
	<script src="JOGO.js"></script>
	<script src="JOGO_tabela.js"></script>
	<link rel="shortcut icon" href="../icon.png" type="image/x-icon">
	<link rel="stylesheet" href="../estilo_basico.css" type="text/css">
	<link rel="stylesheet" href="JOGO.css" type="text/css">
	

</head>

<body onload="{registerEvents();requisitarHistorico();}">

	<?php
	$_GET['AQUI'] = 'JOGO';
	include '../nav.php';
	?>


	<div id="WrapperConfigGame">

		<div id="configSide" class="side">

			<div id="configWindow" class="window">
				<div class="title-bar">
					<h2 id="configHead">Configurações do Jogo</h2>
				</div>


				<br />
				Dimensões
				<br /><br />
				<label id="xaxis">Linhas: </label>
				<input id="xaxisInput" class="dim" placeholder="10" type="number" min="10" max="50" value="10" onchange="clampInput(this,10,50)" required />
				<br /><br />
				<label id="yaxis">Colunas: </label>
				<input id="yaxisInput" class="dim" placeholder="10" type="number" min="10" max="50" value="10" onchange="clampInput(this,10,50)" required />
				<br /><br />

				<label id="bombNum">Minas (%): </label>
				<input class="dim" placeholder="10" id="numBomba" type="number" min="10" max="30" value="10" onchange="clampInput(this,10,30)" required />

				<br /><br />
				<label for="dificuldade">Dificuldade: </label>
				<select name="dificuldade" id="dificuldade">
					<option value="0">Normal</option>
					<option value="1">Rivotril</option>
				</select>
				<br />
				<br />
				
				<button onclick="registerEvents();" id="botaolink">Começar</button>


				<br />
			</div>

			<div id="historico" class="window">
				<div class="title-bar">
					<h2>Histórico de partidas</h2>
				</div>
				<table id="hist">
					<tr>
						<th>RESULTADO</th>
						<th>TAMANHO</th>
						<th>TEMPO</th>
					</tr>
					<tr>
						<td>Vitoria</td>
						<td>25x25</td>
						<td>1:05</td>
					</tr>
					<tr>
						<td>Vitoria</td>
						<td>25x25</td>
						<td>1:05</td>
					</tr>
					<tr>
						<td>Vitoria</td>
						<td>25x25</td>
						<td>1:05</td>
					</tr>
					<tr>
						<td>Vitoria</td>
						<td>25x25</td>
						<td>1:05</td>
					</tr>
					<tr>
						<td>Vitoria</td>
						<td>25x25</td>
						<td>1:05</td>
					</tr>
					<tr>
						<td>Vitoria</td>
						<td>25x25</td>
						<td>1:05</td>
					</tr>
					<tr>
						<td>Vitoria</td>
						<td>25x25</td>
						<td>1:05</td>
					</tr>
					<tr>
						<td>Vitoria</td>
						<td>25x25</td>
						<td>1:05</td>
					</tr>
					<tr>
						<td>Vitoria</td>
						<td>25x25</td>
						<td>1:05</td>
					</tr>
					<tr>
						<td>Vitoria</td>
						<td>25x25</td>
						<td>1:05</td>
					</tr>
				</table>

			</div>



		</div>

		<div id="gameSide" class="side">
			<div id="gameWindow" class="window">
				<div class="title-bar">
					<h2 id="ModoDeJogo">Campo Minado</h2>
				</div>
				<div style="width: auto;">
					<form name="form_main">
						<label id="timer">Time: 
						</label>
						<label id="tileCount" class="counter">Minas: 000</label>
						<label id="bombCount" class="counter" style="width: 120px;display: none;">Pontos: 000</label>



					</form>
					<button type="button" id="pauseButton" onclick="pause()" style="display: none;">pausar</button>
					<button type="button" id="resetButton" onclick="registerEvents()">Reset</button>

				</div>
				<div style="clear: both;" id="OJogo">
					<table id="tabela"></table>
				</div>
				<label style="font-size:85%;" >*use o botão direito do mouse para marcar bombas</label>
				<button onclick="trapacear()" id="trapacaBotao">Trapacear</button>
				<br>
				<br>
			</div>
		</div>

	</div>



</body>


</html>
