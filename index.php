<?php

session_start();

include_once('database.php');
$conn = createDatabase();

if(isset($_COOKIE['token'])) {
    list($username, $passhash) = explode(",", $_COOKIE['token']);
}
else {
    header("location:frontend/login.php");
    die;
}

?>