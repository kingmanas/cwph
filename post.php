<?php

    session_start();

    include_once('database.php');

    if(isset($_SESSION['username'])) {
        $username = $_SESSION['username']; 
        $user = mysqli_query($db, "select * from user where username like '$username'") or die('Database Error!!'); 
        $user = $user->fetch_assoc();

        $GLOBALS['user'] = $user;
    }

    if(isset($_GET['verify'])) {
        if(isset($_GET['username']) && isset($_GET['link'])) {
            $username = filter_var($_GET['username'], FILTER_SANITIZE_STRING);
            $link = filter_var($_GET['link'], FILTER_SANITIZE_STRING);
            $q = "select * from valid_queue where username like '$username' and link like '$link';";
            $res = mysqli_query($db, $q);
            if($res->num_rows > 0)  {
                echo "Email Verified Successfully!!!";
                $q = "delete from valid_queue where username like '$username' and link like '$link';";
                mysqli_query($db, $q);
                $q = "update user set verified = '1' where username like '$username';";
                mysqli_query($db, $q);
            }
            else echo "Email already verified or the link is broken.";
        }
        header('Location:login.php');
        $db->close();
        exit();
    }

    if(isset($_POST['pop_chat']) && isset($GLOBALS['user'])) {
        global $user;
        $q = "select distinct username, full_name, id from user where id in (select distinct receiver from chat where sender like ".$user['id']." union select distinct sender from chat where receiver like ".$user['id'].")";
        $res = mysqli_query($db, $q) or die;
        $val = [];
        while($row = $res->fetch_assoc()) {$val[] = $row;}
        echo json_encode($val);
    }

    if(isset($_POST['lmess']) && isset($GLOBALS['user']) && isset($_POST['user'])) {
        global $user;
        $user2 = filter_var($_POST['user'], FILTER_SANITIZE_STRING);
        $q = "select * from chat where (sender like ".$user['id']." and receiver like $user2) or (receiver like ".$user['id']." and sender like $user2) order by message_timestamp desc;";
        $res = mysqli_query($db, $q) or die;
        $val = [];
        while($row = $res->fetch_assoc()) {$val[] = $row;}
        echo json_encode($val);
    }

    if(isset($_POST['send']) && isset($_POST['username']) && isset($_POST['message'])) {
        global $user;
        $user2 = filter_var($_POST['username'], FILTER_SANITIZE_STRING);     
        $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);     
        $q = "insert into chat(sender, receiver, message_string) values (".$user['id'].", ".$user2.", '".$message."');";
        mysqli_query($db, $q) or die;
        $q = "select * from chat where id = ".mysqli_insert_id($db).";";
        $res = mysqli_query($db, $q) or die;
        echo json_encode($res->fetch_assoc());
    }

    $db->close();
?>