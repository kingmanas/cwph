<?php

include_once('utility.php');

if(isset($_SESSION['username'])) {
    header('Location: frontend/chatapp.php');
    die;
}
if(isset($_COOKIE['token'])) {
    list($username, $passhash) = explode(",", $_COOKIE['token']);
    if(matchhash($username, $passhash)) {
        $_SESSION['username'] = $username;
        header('Location: frontend/chatapp.php');
        exit();
    }
}
else {
    header("location:frontend/login.php");
    exit();
}

?>