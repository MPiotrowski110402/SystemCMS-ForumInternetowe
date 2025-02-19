<?php
    include('session.php');
    if(isset($_SESSION['logined']) && $_SESSION['logined'] === true){
        $_SESSION['logined'] === false;
        session_destroy();
        header('Location: index.php');
        exit();
    }

?>