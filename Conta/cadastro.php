<?php

$username = $password = $nome = $email = $data = $cpf = "";
$username_err = $password_err = "";
$status_cadastro = "";
$status_cadastro_numerico = 1; //0-ruim 1-bom

include "../banco.php";
$pdo = acessarBanco();

if (isset($_POST['acao'])) {


    if (empty(trim($_POST["username"]))) {
        $status_cadastro_numerico = 0;
        $status_cadastro = $username_err = "Digite o usuário";
    }


    if (empty(trim($_POST["password"]))) {
        $status_cadastro_numerico = 0;
        $status_cadastro = $password_err = "Digite a senha";
    } elseif (strlen(trim($_POST["password"])) < 8) {
        $status_cadastro_numerico = 0;
        $status_cadastro = $password_err = "A senha deve ter 8 caracteres ou mais";
    } else {
        $password = trim($_POST["password"]);
    }


    if ($status_cadastro_numerico == 1) {

        $param_username = trim($_POST["username"]);
        //$param_password = password_hash($password, PASSWORD_DEFAULT); 
        $param_password = trim($_POST["password"]);;
        $param_nome = trim($_POST["nome"]);
        $param_email = trim($_POST["email"]);
        $param_data_de_nascimento = trim($_POST["data"]);
        $param_cpf = trim($_POST["cpf"]);


        try {
            $sql = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $sql->execute([$param_email]);
            
            if ($sql->rowCount() == 0) {
                
                $sql2 = $pdo->prepare("SELECT * FROM users WHERE username = ?");
                $sql2->execute([$param_username]);
                
                if ($sql2->rowCount() == 0) {
                    $statement = $pdo->prepare('INSERT INTO users (nome_completo, email, username, senha ,data_de_nascimento, cpf ) 
                                            VALUES (:nome_completo, :email, :username, :senha, :data_de_nascimento, :cpf)');
                    $statement->execute([
                        'nome_completo' => $param_nome,
                        'email' => $param_email,
                        'username' => $param_username,
                        'senha' => $param_password,
                        'data_de_nascimento' => $param_data_de_nascimento,
                        'cpf' => $param_cpf,
                    ]);
                    $status_cadastro = "cadastrado com sucesso;";
                }
                else
                {
                    $status_cadastro_numerico = 0;
                    $status_cadastro = "esse nome de usuário já está em uso";
                }
            } else {
                $status_cadastro_numerico = 0;
                $status_cadastro = "esse email já está em uso";
            }
        } catch (Exception $e) {
            $status_cadastro_numerico = 0;
            $status_cadastro = "algo deu errado";
            echo "<p> errooo </p>";
            echo $e;
        }
    }

    //header("Location: ../Conta/login.php");
}
?>


<!DOCTYPE html>
<html lang="br">

<head>
    <title>📋 Cadastro</title>
    <meta charset="utf-8" />
    <link rel="shortcut icon" href="../icon.png" type="image/x-icon">
    <link rel="stylesheet" href="../estilo_basico.css">
    <script src="cadastro.js"></script>
    <!--  Link externo para a mascara do CPF !-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>
    <script>
    $(document).ready(function () { 
        var $cpf = $("#cpf");
        $cpf.mask('000.000.000-00', {reverse: true});
    });
    </script>
</head>

<body style="background-color: #E9E7D8;">

    <form id="panel" class="window" method="post" name="fCadastro" onsubmit="return validar()">
        <div class="title-bar">
            <h2>Cadastro</h2>
        </div>

        <?php
        if (!empty($status_cadastro)) {
            $css_class = "";
            if ($status_cadastro_numerico == 1) {
                $css_class = "alerta_bom";
            } else {
                $css_class = "alert";
            }

            echo '<div class="' . $css_class . '">' . $status_cadastro . '</div>';
        }
        ?>
        <br>
        <label>Nome Completo:
            <br>
            <input type="text" name="nome" class="entrada" placeholder="Insira Seu Nome"></label>
        <br><br>

        <label>E-Mail
            <br>
            <input type="email" name="email" placeholder="exemplo@email.com"></label>

        <br><br>
        <label>Nome de usuário:
            <br>
            <input type="text" name="username" placeholder="Nome que aparecerá no placar" style="border-color: <?php echo (!empty($username_err)) ? '#dc3545' : '#ced4da'; ?>;" value="<?php echo $username; ?>"></label>
        <br><br>

        <label>Senha:
            <br>
            <input type="password" name="password" placeholder="8 Caracteres" style="border-color: <?php echo (!empty($password_err)) ? '#dc3545' : '#ced4da'; ?>;"></label>
        <br><br>

        <label>Data de Nascimento
            <br>
            <input type="date" name="data"></label>
        <br><br>

        <label>CPF:
            <br>
            <input type="text" name="cpf" id="cpf" placeholder="000.000.000-00"></label>
        <br><br><br>

        <input type="submit" value="Cadastrar" name="acao">
        <a href="../Conta/login.php" style="float: right;">Retornar ao Login</a>
    </form>
</body>

</html>
