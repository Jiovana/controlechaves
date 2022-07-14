<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception; 

require_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/PHPMailer-master/src/PHPMailer.php';
require_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/PHPMailer-master/src/SMTP.php';
require_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/PHPMailer-master/src/Exception.php';

date_default_timezone_set('America/Sao_Paulo');

function MailSender($recipient_mail, $recipient_name, $subject, $body, $altbody){
    // passing true in constructor enables exceptions in PHPMailer
    $mail = new PHPMailer( true );

    try {
        // Server settings // _OFF _SERVER
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        // for detailed debug output
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
        $mail->setLanguage("pt-br");

        $mail->Username = 'jwimob.mailsender@gmail.com';
        // YOUR gmail email
        $mail->Password = 'pctneanfggsgfynw';
        // YOUR gmail password

        // Sender and recipient settings
        $mail->setFrom( 'jwimob.mailsender@gmail.com', 'JW Imobiliária' );
        $mail->addAddress( $recipient_mail, $recipient_name );
        //$mail->addReplyTo( 'example@gmail.com', 'Sender Name' );
        // to set the reply to

        // Setting the email content
        $mail->IsHTML( true );
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = $altbody;

        $mail->send();
        //echo "Email message sent.";
    } catch ( Exception $e ) {
        echo "Error in sending email. Mailer Error: {$mail->ErrorInfo}";
    }
}


?>