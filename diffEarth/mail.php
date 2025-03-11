<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';
$mail = new PHPMailer(true);
try {
//Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
//Enable verbose debug output
    $mail->isSMTP();
//Send using SMTP
    $mail->Host       = 'smtp@gmail.com';
//Set the SMTP server to send through
    $mail->SMTPAuth   = true;
//Enable SMTP authentication
    $mail->Username   = 'clientprojecttestemail@gmail.com';
//SMTP username
    $mail->Password   = 'oulrvhrgaoqbqskm';
//SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
//Enable implicit TLS encryption
    $mail->Port       = 465;
//TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('clientprojecttestemail@gmail.com', 'Mailer');
    $mail->addAddress('bog.mogridge@gmail.com', 'John smith');
//Content
    $mail->isHTML(true);
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
