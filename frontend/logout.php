<?php

    session_start();

    if(isset($_SESSION['username'])) {
        unset($_SESSION['username']);
    }

    if(isset($_COOKIE['token'])) {
        setcookie('token', '', time() - 3600);
    }

    session_destroy();

    header('Location:login.php');
    exit();

?>