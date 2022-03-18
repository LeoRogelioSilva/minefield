<?php 

    echo '<nav id="menu"><ul>';

    if (!isset($_GET['AQUI']) || $_GET['AQUI']!='JOGO')
    {
        echo '<li><a href="../Jogo/JOGO.php">Play</a></li>';
    }
    if (!isset($_GET['AQUI']) || $_GET['AQUI']!='profile')
    {
        echo '<li><a href="../Conta/profile.php">Profile</a></li>';
    }
    if (!isset($_GET['AQUI']) || $_GET['AQUI']!='leaderboard')
    {
        echo '<li><a href="../Jogo/leaderboard.php">Leaderboard</a></li>';
    }
        
        echo '<li><a href="../Conta/login.php">Logout</a></li>';
    
                
    echo '</ul></nav>';



?>