<?php
    include_once('database.php');
    include_once('email_client.php');

    function emailVerification($username) {
        global $mail, $db;
        $secret = "f32*(894njkffgjbfu23@#$4hnkdaj-=adfkj4iu";
        $link = md5($username.$secret);

        $q = "insert into valid_queue(username, link) values ('$username', '$link');";
        $res = mysql_query($db, "select email from user where username='$username';");
        $email = $res->fetch_assoc()['email'];

        if($email == null) { die("Could not find user's email address...");}

        $mail->Subject = 'CWPH Email Verification';
        $message = file_get_contents('verifier.html');
        $message = str_replace('%link%', "https://localhost/post.php?link=$link");
        $message->addAddress($email);

        if (!$mail->send()) {header('Location:login.php'); die('Mailer Error: '. $mail->ErrorInfo);}
    }
?>