<?php

$msg="";
if (isset($_POST['criar']) || isset($_POST['save'])) {
    $ini_write = array("host" => $_POST['host'], "username" => $_POST['username'], "password" => $_POST['password'], "dbName" => $_POST['dbName']);
    write_php_ini($ini_write, 'database.ini');
    $msg= 'O jogo vai usar essas configurações de banco de dados!!<br/><br/>';
}

try {
    $a = parse_ini_file('database.ini');
} catch (Exception $e) {
}



?>

<!DOCTYPE html>
<html lang="br">

<head>
    <title>minefield_setup</title>
    <meta charset="utf-8" />
</head>

<body>

    <h1>Menu de criação do banco de dados para o campo minado</h1>

    <form method="POST">
        <label for="host">Localhost</label> <br />
        <input type="text" name="host" id="host" value="<?= isset($a['host']) ? $a['host'] : 'localhost'; ?>">

        <br />

        <label for="username">username</label> <br />
        <input type="text" name="username" id="username"  value="<?= isset($a['username']) ? $a['username'] : 'root'; ?>">

        <br />

        <label for="password">password</label> <br />
        <input type="password" name="password" id="password"  value="<?= isset($a['password']) ? $a['password'] : ''; ?>">

        <br />

        <label for="dbName">database Name</label> <br />
        <input type="text" name="dbName" id="dbName" value="<?= isset($a['dbName']) ? $a['dbName'] : 'campominado'; ?>">

        <br /><br />

        <input type="submit" name="save" value="usar esse banco"><br /> <br />
        <input type="submit" name="criar" value="criar base de dados">
        <input type="submit" name="apagar" value="apagar base de dados">
        <input type="submit" name="limpar" value="limpar base de dados">

    </form>
    <br /><br />

    <?php
    function criar()
    {
        $sname = $_POST['host'];
        $uname = $_POST['username'];
        $pwd = trim($_POST['password']);
        $dbname = $_POST['dbName'];

        try {
            $conn = new PDO("mysql:host=$sname;", $uname, $pwd);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Acesso ao mysql negado<br/><br/>';
            echo 'Mensagem de erro:<br/>';
            echo $e;
            exit;
        }

        try {

            $conn->exec("CREATE DATABASE $dbname;");
            $conn->exec("USE $dbname;");
        } catch (PDOException $e) {
            echo 'esse banco de dados já existe<br/><br/>';
            echo 'Mensagem de erro:<br/>';
            echo $e;
            exit;
        }

        try {
            $conn->exec('CREATE TABLE IF NOT EXISTS users ( 
                id int unsigned primary key AUTO_INCREMENT, 
                nome_completo varchar (30) not null, 
                username varchar (30) default null,
                email varchar (40) not null,
                senha varchar (30) not null,
                data_de_nascimento DATE not null, 
                cpf varchar(14) not null);');
            $conn->exec('CREATE TABLE IF NOT EXISTS resultados
            (
                id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                id_jogador INT UNSIGNED NOT NULL,
                CONSTRAINT  fk_resultado_user FOREIGN KEY (id_jogador) REFERENCES users(id),
                tam_x TINYINT UNSIGNED NOT NULL,
                tam_y TINYINT UNSIGNED NOT NULL,
                qtd_bombas SMALLINT NOT NULL,
                tempo TIME(3) NOT NULL DEFAULT \'00:00:00.000\',
                modalidade VARCHAR(1) NOT NULL DEFAULT \'N\', 
                vitoria TINYINT NOT NULL,
                dataAtual VARCHAR(50) NOT NULL
            );');
        } catch (PDOException $e) {
            echo 'algo deu errado<br/><br/>';
            echo 'Mensagem de erro:<br/>';
            echo $e;
            exit;
        }
    }

    function apagar()
    {
        $sname = $_POST['host'];
        $uname = $_POST['username'];
        $pwd = trim($_POST['password']);
        $dbname = $_POST['dbName'];

        try {
            $conn = new PDO("mysql:host=$sname;", $uname, $pwd);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Acesso ao mysql negado<br/><br/>';
            echo 'Mensagem de erro:<br/>';
            echo $e;
            exit;
        }

        try {
            $conn->exec("USE $dbname;");
            $conn->exec("drop database $dbname;");
        } catch (PDOException $e) {
            echo 'esse banco de dados não existe<br/><br/>';
            echo 'Mensagem de erro:<br/>';
            echo $e;
            exit;
        }
    }

    //as duas seguintes funções foram copiadas de https://stackoverflow.com/a/5695202
    function write_php_ini($array, $file)
    {
        $res = array();
        foreach ($array as $key => $val) {
            if (is_array($val)) {
                $res[] = "[$key]";
                foreach ($val as $skey => $sval) $res[] = "$skey = " . (is_numeric($sval) ? $sval : '"' . $sval . '"');
            } else $res[] = "$key = " . (is_numeric($val) ? $val : '"' . $val . '"');
        }
        safefilerewrite($file, implode("\r\n", $res));
    }

    function safefilerewrite($fileName, $dataToSave)
    {
        if ($fp = fopen($fileName, 'w')) {
            $startTime = microtime(TRUE);
            do {
                $canWrite = flock($fp, LOCK_EX);
                // If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
                if (!$canWrite) usleep(round(rand(0, 100) * 1000));
            } while ((!$canWrite) and ((microtime(TRUE) - $startTime) < 5));

            //file was locked so now we can store information
            if ($canWrite) {
                fwrite($fp, $dataToSave);
                flock($fp, LOCK_UN);
            }
            fclose($fp);
        }
    }



    if (isset($_POST['criar'])) {
        criar();
        echo 'Banco de dados criado com sucesso!!<br/><br/>';
    } elseif (isset($_POST['apagar'])) {
        apagar();
        echo 'Banco de dados apagado com sucesso!!<br/><br/>';
    } elseif (isset($_POST['limpar'])) {
        apagar();
        criar();
        echo 'Banco de dados limpado com sucesso!!<br/><br/>';
    }

    if (!empty($msg))
    {
        echo $msg;
    }
    ?>
    

</body>


</html>