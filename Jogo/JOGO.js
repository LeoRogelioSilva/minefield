var linhas, colunas, bombas, matriz, tabela, pontuacao, dimensao, dificuldade, auxBombas, mostra;
var correctFlags = 0;
var jogoEmAndamento = false;
var trapacaTimeout;
let xhttp;

"use strict";

/*
Pegamos como base algumas das seguintes funções: https://codepen.io/larii_d/pen/eEbJyZ
*/

function iniciar_Jogo() {
    let pMinas = 0;
    jogoEmAndamento = true;
    firstPlay = true;
    correctFlags = 0;
    linhas = document.getElementById("xaxisInput").value * 1;
    colunas = document.getElementById("yaxisInput").value * 1;
    pMinas = document.getElementById("numBomba").value * 1;


    document.getElementById("gameWindow").style.width = (colunas * 29 + 20 + 50) + "px";
    document.getElementById("gameWindow").style.height = (linhas * 29 + 118+20) + "px";
    document.getElementById("tabela").style = "cursor: pointer";

    if (linhas == null || linhas <= 0 || colunas == null || colunas <= 0 || pMinas == null || pMinas <= 0) {
        if (linhas <= 0 || linhas == null) {
            alert("Digite o número de Linhas!");
        }

        if (colunas <= 0 || colunas == null) {
            alert("Digite o número de Colunas!");
        }


        if (pMinas <= 0 || pMinas == null) {
            alert("Digite o número de Minas!");
        }

    }
    else {

        setTimeOnClock(0);
        document.getElementById('bombCount').innerText = "Pontos: 000";
        tabela = document.getElementById("tabela");
        tabela.onclick = verificar;
        tabela.oncontextmenu = bandeira;
        dificuldade = document.getElementById("dificuldade");
        if (pMinas > 30)
            pMinas = 30;
        if (pMinas < 10)
            pMinas = 10;
        bombas = parseInt(linhas * colunas * pMinas / 100);
        document.getElementById('tileCount').innerHTML = "minas: " + String(bombas).padStart(3, '0');
        auxBombas = bombas;

        gerarTabela(linhas, colunas);

        if (parseInt(dificuldade.value) == 1) {
            //modo rivotril
            let tempo = calcularTempoRivotril();
            setTimeOnClock(tempo);
        }


    }
    pauseClock();
}
function calcularTempoRivotril()
{
    return ((auxBombas * auxBombas) * 4 +  auxBombas * 1730 + 20700);
}


function gerarMatriz(l, c) {
    matriz = [];
    for (var i = 0; i < l; i++) {
        matriz[i] = new Array(c).fill(0);
    }
}
function gerarTabela(l, c) {
    gerarMatriz(l, c);


    var str = "";
    for (var i = 0; i < l; i++) {
        str += "<tr>";
        for (var j = 0; j < c; j++) {
            str += "<td class='blocked'></td>";
        }
        str += "</tr>";
    }
    tabela.innerHTML = str;
}
function mostrarMatriz() {
    for (var i = 0; i < linhas; i++) {
        for (var j = 0; j < colunas; j++) {
            if (matriz[i][j] === -1) {
                tabela.rows[i].cells[j].innerHTML = "&#128163;";
            } else {
                tabela.rows[i].cells[j].innerHTML = matriz[i][j];
            }
        }
    }
}

function gerarBombas(l, c) {

    for (var i = 0; i < auxBombas;) {

        var linha = Math.floor((Math.random() * linhas));
        var coluna = Math.floor((Math.random() * colunas));

        if (matriz[linha][coluna] === 0 && !(linha === l && coluna === c)) {

            if (tabela.rows[linha].cells[coluna].className == "flag") {
                correctFlags += 1;

            }

            matriz[linha][coluna] = -1;

            i++;
        }
    }
}
function gerarNumero(l, c) {
    var count = 0;
    for (var i = l - 1; i <= l + 1; i++) {
        for (var j = c - 1; j <= c + 1; j++) {
            if (i >= 0 && i < linhas && j >= 0 && j < colunas) {
                if (matriz[i][j] === -1) {
                    count++;
                }
            }
        }
    }
    matriz[l][c] = count;
}
function gerarNumeros() {
    for (var i = 0; i < linhas; i++) {
        for (var j = 0; j < colunas; j++) {
            if (matriz[i][j] !== -1) {
                gerarNumero(i, j);
            }
            if (matriz[i][j] === 8)
                alert("Jogue na Mega-Sena, tem um 8 no seu tabuleiro");
        }
    }
}

function updateBombCounter() {
    if (bombas >= 0) {
        document.getElementById('tileCount').innerHTML = "minas: " + String(bombas).padStart(3, '0');
    }
    else {
        document.getElementById('tileCount').innerHTML = "minas: -" + String(Math.abs(bombas)).padStart(2, '0');
    }
}

function bandeira(event) {

    if (!jogoEmAndamento) return;
    var cell = event.target;
    var linha = cell.parentNode.rowIndex;
    var coluna = cell.cellIndex;
    if (cell.className === "blocked") {
        cell.className = "flag";
        cell.innerHTML = "&#128681;";
        bombas -= 1;
        if (matriz[linha][coluna] == -1) {
            correctFlags += 1;
        }
    }
    else if (cell.className === "flag") {
        cell.className = "blocked";
        cell.innerHTML = "";
        if (bombas <= auxBombas) {
            bombas += 1;
        }
        if (matriz[linha][coluna] == -1) {
            correctFlags -= 1;
        }
    }

    updateBombCounter();



    return false;
}

function limparCelulas(l, c) {
    for (var i = l - 1; i <= l + 1; i++) {
        for (var j = c - 1; j <= c + 1; j++) {
            if (i >= 0 && i < linhas && j >= 0 && j < colunas) {
                var cell = tabela.rows[i].cells[j];
                if (cell.className !== "blank") {
                    switch (matriz[i][j]) {
                        case -1:
                            break;
                        case 0:
                            if (cell.className === "flag") {
                                if (bombas <= auxBombas) {
                                    bombas += 1;
                                }
                                updateBombCounter();
                            }
                            cell.innerHTML = "0";
                            cell.className = "blank";
                            limparCelulas(i, j);
                            break;
                        default:
                            if (cell.className === "flag") {
                                if (bombas <= auxBombas) {
                                    bombas += 1;
                                }
                                updateBombCounter();
                            }
                            cell.innerHTML = matriz[i][j];
                            cell.className = "n" + matriz[i][j];
                            cell.classList.add("revelado");
                    }
                }
            }
        }
    }
}

function mostrarBombas() {
    mostra = true;
    for (var i = 0; i < linhas; i++) {
        for (var j = 0; j < colunas; j++) {
            if (matriz[i][j] === -1) {
                var cell = tabela.rows[i].cells[j];
                //cell.innerHTML = "&#128163;"
                cell.innerHTML = "&#128165;"
            }
        }
    }
}

function mostrarBombasTrapaca() {
    mostra = true;
    for (var i = 0; i < linhas; i++) {
        for (var j = 0; j < colunas; j++) {
            if (matriz[i][j] === -1) {
                var cell = tabela.rows[i].cells[j];
                cell.innerHTML = "&#128163;"
            }
        }
    }
}

function trapacear() {
    if (jogoEmAndamento && !firstPlay) {
        mostrarBombasTrapaca();
        trapacaTimeout = setTimeout(unshowBombs, 5000);
    }
}

function unshowBombs() {

    for (var i = 0; i < linhas; i++) {
        for (var j = 0; j < colunas; j++) {
            if (matriz[i][j] === -1) {
                var cell = tabela.rows[i].cells[j];
                cell.innerHTML = ' ';
                if (cell.className == "flag") {
                    cell.innerHTML = "&#128681;";
                }
            }
        }
    }

}

var firstPlay = false;

function verificar(event) {

    if (!jogoEmAndamento) return;

    var cell = event.target;
    if (cell.className !== "flag") {
        var linha = cell.parentNode.rowIndex;
        var coluna = cell.cellIndex;

        if (firstPlay) {
            firstPlay = false;
            gerarBombas(linha, coluna);
            gerarNumeros();

            if (parseInt(dificuldade.value) == 0) {
                setTimeOnClock(0);
                resumeClock();
            }
            else {
                let tempo = calcularTempoRivotril();
                setTimeOnClock(tempo);
                resumeClockBackwards();
            }
        }


        switch (matriz[linha][coluna]) {
            case -1:
                
                derrota(cell);
                break;
            case 0:
                cell.innerHTML = matriz[linha][coluna];
                cell.className = "n" + matriz[linha][coluna];
                cell.classList.add("blank");
                limparCelulas(linha, coluna);
                break;
            case 8:
                alert("Parabéns, você achou o 8!");
            default:
                cell.innerHTML = matriz[linha][coluna];
                cell.className = "n" + matriz[linha][coluna];
                cell.classList.add("revelado");
        }

        fimDeJogo();
    }
}

function fimDeJogo() {
    var celulas
    celulas = document.querySelectorAll(".blank, .revelado");

    if (celulas.length === linhas * colunas - auxBombas) {
        jogoEmAndamento = false;
        pauseClock();
        alert("Você venceu!\nTempo: " + formatTime(gameTime,true));
        xhttp = new XMLHttpRequest();
        if (!xhttp) {
            alert('Não foi possível criar um objeto XMLHttpRequest.');
            return false;
        }
        xhttp.onreadystatechange = atualizarTabela;
        xhttp.open('POST', 'resultados.php', true);
        xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhttp.send('tam_x=' + encodeURIComponent(linhas)
            + '&tam_y=' + encodeURIComponent(colunas)
            + '&qtd_bombas=' + encodeURIComponent(auxBombas)
            + '&tempo=' + encodeURIComponent("00:" + formatTime(gameTime,true))
            + '&modalidade='+encodeURIComponent((parseInt(dificuldade.value)==0?'N':'R'))
            + '&vitoria=1');



        return true;
    }
    return false;
}
function derrota(cell = null) {
    mostrarBombas();
    tabela.onclick = undefined;
    tabela.oncontextmenu = undefined;
    pauseClock();
    clearTimeout(trapacaTimeout);
    jogoEmAndamento = false;
    //alert("Você perdeu!\nTempo: " + segundos + ":" + milisegundos);
    document.getElementById('bombCount').innerText = "Pontos: 010";
    try {
        cell.style.backgroundColor = "red";
    }
    catch (e) {
        //do nothing;
    }
    
    xhttp = new XMLHttpRequest();
    if (!xhttp) {
        alert('Não foi possível criar um objeto XMLHttpRequest.');
        return false;
    }
    xhttp.onreadystatechange = atualizarTabela;
    xhttp.open('POST', 'resultados.php', true);
    xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhttp.send('tam_x=' + encodeURIComponent(linhas)
        + '&tam_y=' + encodeURIComponent(colunas)
        + '&qtd_bombas=' + encodeURIComponent(auxBombas)
        + '&tempo=' + encodeURIComponent("00:" +formatTime(gameTime,true))
        + '&modalidade='+encodeURIComponent((parseInt(dificuldade.value)==0?'N':'R'))
        + '&vitoria=0');


}

function atualizarTabela() {
    
        if (xhttp.readyState === XMLHttpRequest.DONE) {
            
            if (xhttp.status === 200) {
                
                requisitarHistorico();
                
            }
            else {
                alert('Um problema ocorreu.');
            }
        }
    
}


function registerEvents(){
    iniciar_Jogo();
    var diff = document.getElementById("dificuldade");
    diff.onchange = iniciar_Jogo;
}

function clampInput(inp, min, max) {
    if (inp.value < min)
        inp.value = min;
    else if (inp.value > max)
        inp.value = max;
    else {
        inp.value = Math.floor(inp.value)
    }
}

//Clock functions

var cronometro, gameTime = 0;

function setTimeOnClock(tempo = 0) {
    gameTime = tempo;

    if (gameTime < 0) {
        pauseClock();
        gameTime = 0;
        document.getElementById('timer').innerText ="TIME: "+formatTime(0);

        derrota();
        return;
    }
    mili = tempo % 1000;
    seg = Math.floor(tempo / 1000);
    min = Math.floor(seg / 60);
    seg = seg % 60;

    document.getElementById('timer').innerText ="TIME: "+formatTime(tempo);
    
}

function resumeClock() {
    pauseClock();
    cronometro = setInterval(function goTimer() {
        setTimeOnClock(gameTime + 35);
    }, 34);
}

function resumeClockBackwards() {
    pauseClock();
    cronometro = setInterval(function goTimer() {
        setTimeOnClock(gameTime - 34);
    }, 34);
}

function pauseClock() {
    clearInterval(cronometro);
}

function formatTime(tempo =gameTime, considerarModo = false)
{
    
    var minus=""
    if (tempo<0)
    {
        tempo = tempo*-1;
        minus="-"
    }

    if (considerarModo && dificuldade.value==1){
       tempo = calcularTempoRivotril()-gameTime;//tempo total - tempo restante
    }

    var mili = tempo % 1000;
    var seg = Math.floor(tempo / 1000);
    var min = Math.floor(seg / 60);
    seg = seg % 60;

    
    return minus+String(min).padStart(2, '0') + ":"+String(seg).padStart(2, '0')+"."+String(mili).padStart(3, '0');

}

