<?php
session_start();
include "../verificarSessao.php";
$username = $password = $nome = $email = $data = $cpf = "";
$username_err = $password_err = "";
$status_cadastro = "";
$status_cadastro_numerico = 1; //0-ruim 1-bom

include "../banco.php";
$pdo = acessarBanco();

if (isset($_POST['acao'])) {



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

       
        $param_password = trim($_POST["password"]);
        $param_nome = trim($_POST["nome"]);
        $param_email = trim($_POST["email"]);
        

        try {
                $statement = $pdo->prepare('UPDATE users SET nome_completo = :nome_completo, email = :email, senha = :senha WHERE id= :id');
                $statement->execute([
                    'nome_completo' => $param_nome,
                    
                    'email' => $param_email,
                    'senha' => $param_password,
                   
                    
                    'id' => $_SESSION['id'],
                ]);
                $status_cadastro = "atualizado com sucesso!";
            
        } catch (Exception $e) {
            $status_cadastro_numerico=0;
            $status_cadastro="Algo deu errado :(";
            //echo $e;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="br">
    
<head>
    <title>🔒 Perfil</title>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="../estilo_basico.css">
    <link rel="stylesheet" href="profile.css">
    <link rel="shortcut icon" href="../icon.png" type="image/x-icon">
</head>

<body>
    <?php   
		$_GET['AQUI']='profile';
		include '../nav.php';
	?>


<br>
<br>
		
       
           <form id="panel" class="window" method="post" name = "fCadastro" onsubmit="return validar()">
        <div class="title-bar">
            <h2>Perfil</h2>
        </div>
        <br/>

         <img src="user.png" alt="">
        <br>
        <br>

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

        <?php 
        $pdo = acessarBanco();
        $statement = $pdo->prepare("SELECT * FROM users WHERE id=:id");

         $statement->execute([
                    'id' => $_SESSION['id'],
                ]);
        $row = $statement->fetch();
        ?>
        <label>Nome Completo:
            <br>
            <input type="text" name="nome" class="entrada" placeholder="Insira Seu Nome" value="<?php echo $row['nome_completo']; ?>"></label>
        <br><br>

        <label>E-Mail
            <br>
            <input type="email" name="email" placeholder="exemplo@email.com" value="<?php echo $row['email']; ?>"></label>

        <br><br>
        <label>Nome de usuário:
            <br>
            <input type="text" name="username" placeholder="Nome que aparecerá no placar" style="border-color: <?php echo (!empty($username_err)) ? '#dc3545' : '#ced4da'; ?>;" value="<?php echo $row['username']; ?>" disabled></label>
        <br><br>

        <label>Senha:
            <br>
            <input type="password" name="password" placeholder="8 Caracteres" style="border-color: <?php echo (!empty($password_err)) ? '#dc3545' : '#ced4da'; ?>;" ></label>
        <br><br>

        <label>Data de Nascimento
            <br>
            <input type="date" name="data" value="<?php echo $row['data_de_nascimento']; ?>" disabled></label>
        <br><br>

        <label>CPF:
            <br>
            <input type="text" name="cpf" id="cpf" placeholder="000.000.000-00" value="<?php echo $row['cpf']; ?>" disabled></label>
        <br><br><br>

        <input type="submit" value="Alterar perfil" name="acao">
    </form>
</body>

</html>
