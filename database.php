<?php
    $db = new mysqli('localhost:3306', 'cwphadmin', '123456789', 'cwph');
    if(mysqli_connect_errno()) {
        echo "Could not connect".mysqli_connect_error();
    }
?>

