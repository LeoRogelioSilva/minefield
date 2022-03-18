<?php 

function acessarBanco()
{
    $ini=parse_ini_file('database.ini');
    $sname = $ini['host'];
    $dbname = $ini['dbName'];
    $uname = $ini['username'];
    $pwd = $ini['password'];
    //echo $sname."<br>";
    //echo $dbname."<br>";
    //echo $uname."<br>";
    //echo $pwd."<br>";

    /*$sname = 'localhost';
    $dbname = 'campominado';
    $uname = 'root';
    $pwd = '';*/

    try{
    $conn = new PDO("mysql:host=$sname;", $uname, $pwd);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("USE $dbname;");
    }
    catch(PDOException $e)
    {
        //echo 'Erro ao conectar ao banco de dados<br/>';
        //echo $e;
        exit;
    }
    return $conn;
}


?>