<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer;
$mail->IsSMTP();
// $mail->SMTPDebug = SMTP::DEBUG_SERVER;
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->SMTPAuth = true;
$mail->Username = 'manas.apptest@gmail.com';
$mail->Password = 'hhyguiop34$';
$mail->setFrom('manas.apptest@gmail.com', 'CWPH');

// $mail->addAddress('manassingh100000@gmail.com', 'Manas Singh');
// $mail->Subject = 'PHPMailer GMail SMTP test';
// $mail->Body = 'Hello';
// $mail->AltBody = 'This is a plain-text message body';
// $mail->addAttachment('images/phpmailer_mini.png');

// if (!$mail->send()) {
//     echo 'Mailer Error: '. $mail->ErrorInfo;
// } else {
//     echo 'Message sent!';
// }

?>