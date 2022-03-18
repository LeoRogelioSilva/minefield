
    <?php


    if (!isset($_SESSION["login"]) || !($_SESSION["login"] == true)) {
        header("location: ../Conta/login.php");
            exit;
    }
    ?>
