<?php
//Função para enviar email de forma assíncrona
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../libs/PHPMailer/src/Exception.php';
require '../libs/PHPMailer/src/PHPMailer.php';
require '../libs/PHPMailer/src/SMTP.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$email_destino = $_POST['email'];
$verification_code = $_POST['code'];
$assunto = 'Código de verificação';
$mensagem = 'Seu código de verificação é: ' . $verification_code;

function sendMail($to, $subject, $message){
    $mail = new PHPMailer(true);

    try{
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'brainstorm.authcode@gmail.com';
        $mail->Password = 'xcmb ojqu qhmw ruqp';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Config email
        $mail->CharSet = 'UTF-8';
        $mail->setFrom('brainstorm.authcode@gmail.com', 'Brainstorm');
        $mail->addAddress($to);
        $mail->Subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        $mail->Body = $message;
        $mail->isHTML(true);

        //Envia o email
        $mail->send();
        return true;
    } catch (Exception $e) {
        return('Erro ao enviar email: ' . $mail->ErrorInfo);
    }
}

$response = sendMail($email_destino, $assunto, $mensagem);
header('Content-Type: application/json');
echo json_encode($response);
?>