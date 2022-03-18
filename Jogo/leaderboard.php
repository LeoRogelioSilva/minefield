<?php
session_start();
include "../banco.php";
include "../verificarSessao.php";

function formatTime($tempo,$modo)
{

    $tempo = substr($tempo,3);
    if (substr($tempo,0,2)=="0")
    {
        $tempo = substr($tempo,3);
    }
   
   if ($modo =='R')
   {
       $tempo = "-$tempo";
   }
    return $tempo;
}

?>

<!DOCTYPE html>
<html lang="br">

<head>
    <title>📋 Ranking</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="leaderboard.css">
    <link rel="stylesheet" href="../estilo_basico.css">
    <link rel="shortcut icon" href="../icon.png" type="image/x-icon">
</head>

<body>
    <?php
    $_GET['AQUI'] = 'leaderboard';
    include '../nav.php';
    ?>

    <br>
    <br>
    <div id="body_leader" class="window">
        <div class="title-bar">
            <h2 id="ModoDeJogo">Leaderboard</h2>
        </div>
        <br>

        <table id="historico">
            <tr>

                <th>Pos</th> <th>Jogador</th><th>Modo</th><th>Tamanho</th><th>Bombas</th><th style="width:86px;">Tempo</th>

            </tr>

            <?php     
            /*$sname = 'localhost';
            $dbname = "campoMinado";
            $uname = 'root';
            $pwd = '';
            $con = mysqli_connect($sname, $uname, $pwd);
            mysqli_select_db($con, $dbname);
            $query = sprintf('SELECT U.username, R.tam_x, R.tam_y, R.qtd_bombas, R.tempo, R.modalidade 
                              FROM users as U, resultados as R 
                              where U.id = R.id_jogador and R.vitoria = 1 
                              order by (R.tam_x * R.tam_y) desc,R.qtd_bombas desc, R.tempo
                              limit 10');
            $dados = mysqli_query($con, $query) or die(mysqli_error($con));
            $linha = mysqli_fetch_assoc($dados);
            $total = mysqli_num_rows($dados);*/


            $pdo = acessarBanco();
            $sql = $pdo->query('SELECT U.username, R.tam_x, R.tam_y, R.qtd_bombas, R.tempo, R.modalidade 
                                FROM users as U, resultados as R 
                                WHERE U.id = R.id_jogador and R.vitoria = 1 
                                order by (R.tam_x * R.tam_y) desc,R.qtd_bombas desc, R.tempo
                                limit 10');
            

            if ($sql->rowCount() > 0) {
                $cont = 1;
                while($linha = $sql->fetch(PDO::FETCH_ASSOC)){
                    
            ?>  
                   
                    <tr>
                    <td><?= $cont; ?> </td>
                    <td> <?= $linha['username'] ?></td>
                    <td> <?= $linha['modalidade']=='N'?'Normal':'Rivotril' ?></td>
                    <td> <?= $linha['tam_x'] .'x'. $linha['tam_y'] ?></td>
                    <td> <?= $linha['qtd_bombas'] ?></td>
                    <td class="coluna_tempo"> <?= formatTime($linha['tempo'],$linha['modalidade']) ?></td>
                    </tr>
                <?php
                    $cont++;
                }
            }


            ?>
        </table>
    </div>
</body>

</html>