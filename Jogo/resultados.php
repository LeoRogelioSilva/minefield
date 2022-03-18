<?php
session_start();
include "../banco.php";

$bd = acessarBanco();

if (isset($_POST['hist_usuario'])) 
{
    $sql ='SELECT * FROM resultados WHERE id_jogador= :id_Jogador ORDER BY id DESC LIMIT 10';
    $query = $bd->prepare($sql);
    $query->bindValue(':id_Jogador',$_SESSION['id'],PDO::PARAM_STR);
    $query->execute();
    $num = $query->rowCount();
    
    echo json_encode($query->fetchAll());
    
}
elseif(isset($_POST['hist_ranking']))
{
    $sql ='SELECT * FROM resultados WHERE id_jogador= :id_Jogador ORDER BY id DESC LIMIT 10';
}
else // apenas inserir resultado
{
    $id_Jogador = $_SESSION['id'];
    $tam_x = $_POST['tam_x'];
    $tam_y = $_POST['tam_y'];
    $qtd_bombas = $_POST['qtd_bombas'];
    $tempo=$_POST['tempo'];
    $modalidade = $_POST['modalidade'];
    $vitoria = $_POST['vitoria'];
    date_default_timezone_set('America/Cuiaba');
    $dataAtual = date("d-m-Y H:i:s"); 
   

    $sql = 'INSERT INTO resultados(id_jogador, tam_x, tam_y, qtd_bombas, tempo, modalidade, vitoria, dataAtual)
    VALUES (:id_jogador, :tam_x, :tam_y, :qtd_bombas, :tempo,:modalidade, :vitoria, :dataAtual)';

    try {
        $query = $bd->prepare($sql);
        $query->bindValue(':id_jogador', $id_Jogador, PDO::PARAM_STR);
        $query->bindValue(':tam_x', $tam_x, PDO::PARAM_STR);
        $query->bindValue(':tam_y', $tam_y, PDO::PARAM_STR);
        $query->bindValue(':qtd_bombas', $qtd_bombas, PDO::PARAM_STR);
        $query->bindValue(':tempo', $tempo, PDO::PARAM_STR);
        $query->bindValue(':modalidade', $modalidade, PDO::PARAM_STR);
        $query->bindValue(':vitoria', $vitoria, PDO::PARAM_STR);
        $query->bindValue(':dataAtual', $dataAtual, PDO::PARAM_STR);
        


        $result = "";
        if ($query->execute()) {
            $result = "sucesso";
        } else {

            $result = "fracasso";
        }
        $array = ['result' => $result, 'sizex' => $tam_x, 'sizey' => $tam_y, 'bombas' => $qtd_bombas, 'vitoria' => $vitoria,'modalidade'=>$modalidade, 'tempo' => $tempo, 'dataAtual' => $dataAtual];
        echo json_encode($array);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

?>