var historico;

function requisitarHistorico()
{
   
    xhttp = new XMLHttpRequest();
        if (!xhttp) {
            alert('Não foi possível criar um objeto XMLHttpRequest.');
            return false;
        }
        xhttp.onreadystatechange = atualizarHistorico;
        xhttp.open('POST', 'resultados.php', true);
        xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhttp.send('hist_usuario=1');
}

function atualizarHistorico() {
    
    if (xhttp.readyState === XMLHttpRequest.DONE) {
        
        if (xhttp.status === 200) {
            historico = JSON.parse(xhttp.responseText);
            
            desenharTabela();
        }
        else {
            alert('Um problema ocorreu.');
        }
    }

}

function desenharTabela()
{
    var hist = document.getElementById("hist");
    hist.innerHTML="";
    
    hist.innerHTML+= "<tr style=\"height:27px;\"> <th>Res</th><th style=\"width:50px;\">Modo</th> <th>tamanho</th>  <th>bombas</th> <th style=\"width:55px;\">tempo</th> <th style=\"width:95px;\">data</th> </tr>";
    
    for (let i=0;i<10;i++)
    {
        if (i<historico.length)
        {
            var res = historico[i]['vitoria']==1?'&#x2705':'&#x26D4';
            var tam = ""+historico[i]['tam_x'] +"x"+historico[i]['tam_y'];
            var bomb = ""+historico[i]['qtd_bombas'];
            var dataAtual = historico[i]['dataAtual'];
            var tempo = historico[i]['tempo'];
            if (tempo.substring(3,5)=="00")
            {
                tempo = tempo.substring(6,12)
            }
            else
            {
                tempo = tempo.substring(3,12)
            }
            
            var modalidade = historico[i]['modalidade'];
            
            if (modalidade=='N') 
            {
                modalidade="Normal"
            }
            else
            {
                modalidade = "Rivotril"
                tempo= "-"+tempo;
            }
           
            
            
            hist.innerHTML+= "<tr style=\"height:45px;\"> <td style=\"font-size:16px\">"+res+"</td><td>"+modalidade+"</td>  <td>"+tam+"</td>  <td>"+bomb+"</td> <td class=\"coluna_tempo\">"+tempo+"</td> <td>"+dataAtual+"</td> </tr>";
        }
        else{
            hist.innerHTML+= "<tr style=\"height:45px;\"> <td></td><td></td> <td></td>  <td></td> <td></td> <td></td> </tr>";
        }
    }
   
    
    


}
