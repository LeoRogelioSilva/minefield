<?php
session_start();

$email = $password = "";
$email_err = $password_err = $login_err = "";


if (!isset($_POST["email"]) || empty(trim($_POST["email"]))) {
    $email_err = "Digite o e-mail.";
} else {
    $email = trim($_POST["email"]);
}

if (!isset($_POST["password"]) || empty(trim($_POST["password"]))) {
    $password_err = "Digite a senha.";
} else {
    $password = trim($_POST["password"]);
}



include "../banco.php";
$pdo = acessarBanco();


if (isset($_POST['acao'])) {
    $usuario = $_POST['email'];
    $senha = $_POST['password'];

    $sql = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $sql->execute([$usuario]);


    if ($sql->rowCount() == 1) {
        $info = $sql->fetch();

        if ($senha == $info['senha']) {
            $_SESSION['login'] = true; //Se for true então o usuário pode ficar logado.
            $_SESSION['id'] = $info['id']; //Recuperamos o id.
            $_SESSION['usuario'] = $info['email']; //Recuperamos o usuário.
            header("Location: ../Jogo/JOGO.php");  //Redirecionamos o usuário para uma página privada que somente usuários logados podem acessar.
            //die();
        } else {
            $login_err = "Usuário ou senha inválido";
        }
    } else {
        $login_err = "Usuário não encontrado";
    }
}
else
{
    //echo 'voce deslogou';
    $_SESSION=Array();
}

?>
<!DOCTYPE html>
<html lang="br">

<head>
    <title>🔒 Login </title>
    <meta charset="utf-8" />
    <link rel="shortcut icon" href="../icon.png" type="image/x-icon">
    <!--link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"-->
    <link rel="stylesheet" type="text/css" href="../estilo_basico.css" />
    <link rel="stylesheet" type="text/css" href="login.css" />
</head>

<body>


    <form id="panel" class="window" method="post">
        <div class="title-bar">
            <h2>Fazer Login</h2>


        </div>

        <?php
        if (!empty($login_err)) {
            echo '<div class="alert">' . $login_err . '</div>';
        }
        ?>

        <br />
        <label>
            E-mail:
            <br>
            <input type="email" name="email" placeholder="exemplo@email.com" value=" "></label>
        <br><br>

        <label>
            Senha:
            <br>
            <input type="password" placeholder="*******" name="password"></label>
        <br><br><br>
        <input type="submit" value="Login" name="acao">
        <br><br>
        <a href="../Conta/cadastro.php">Criar Conta</a>

    </form>



</body>



</html>
