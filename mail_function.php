<?php

function sendOTP($email, $otp) {
    require_once('./PHPMailer/PHPMailerAutoload.php');
    require_once('vendor/autoload.php');


    $from = 'epollad01@gmail.com';
    $password = 'SandeepKumar08';
    $message_body = "One Time Password for PHP login authentication is:<br/><br/>" . $otp;
    $subject = 'Signup | Verification';

    // Create a new PHPMailer instance
    $mail = new PHPMailer();

    // SMTP configuration
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'ssl';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 465;

    // Sender's email and password
    $mail->Username = $from;
    $mail->Password = $password;

    // Email content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $message_body;

    // Recipient
    $mail->addAddress($email);

    // Send email and return the result
    if ($mail->send()) {
        return true; // Email sent successfully
    } else {
        return false; // Email sending failed
    }
}
?>
