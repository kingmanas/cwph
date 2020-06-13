<?php
    include_once('database.php');
    include_once('email_client.php');

    function emailVerification($username) {
        global $mail, $db;
        $secret = "f32*(894njkffgjbfu23@#$4hnkdaj-=adfkj4iu";
        $link = md5($username.$secret);

        $q = "insert into valid_queue(username, link) values ('$username', '$link');";
        mysqli_query($db, $q);
        $res = mysqli_query($db, "select email from user where username='$username';");
        $email = $res->fetch_assoc()['email'];

        if($email == null) { die("Could not find user's email address...");}

        $mail->Subject = 'CWPH: Please verify your email';
        $message = file_get_contents(__DIR__.'/verifier.html');
        $my_current_ip=exec("ifconfig | grep -Eo 'inet (addr:)?([0-9]*\.){3}[0-9]*' | grep -Eo '([0-9]*\.){3}[0-9]*' | grep -v '127.0.0.1'");
        $message = str_replace('%link%', "https://$my_current_ip/post.php?verify=1&username=$username&link=$link", $message);
        $message = str_replace('%username%', "$username", $message);
        $mail->addAddress($email);
        $mail->Subject = 'CWPH Email Verification';
        $mail->Body = $message;
        $mail->IsHTML(true);

        if (!$mail->send()) {header('Location:login.php'); die('Mailer Error: '. $mail->ErrorInfo);}
        return;
    }
?>