<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../libs/PHPMailer/src/Exception.php';
require '../libs/PHPMailer/src/PHPMailer.php';
require '../libs/PHPMailer/src/SMTP.php';
// Habilita a exibição de erros para debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Limpa e valida os dados de entrada
$nome = htmlspecialchars($_POST['nome'], ENT_QUOTES, 'UTF-8');
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response = array('success' => false, 'message' => 'Email inválido');
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$dt_nascimento = $_POST['dt_nascimento'];
$funcao = htmlspecialchars($_POST['funcao'], ENT_QUOTES, 'UTF-8');
$telefone = htmlspecialchars($_POST['telefone'], ENT_QUOTES, 'UTF-8');
$whatsapp = htmlspecialchars($_POST['whatsapp'], ENT_QUOTES, 'UTF-8');
$cidade = htmlspecialchars($_POST['cidade'], ENT_QUOTES, 'UTF-8');
$estado = htmlspecialchars($_POST['estado'], ENT_QUOTES, 'UTF-8');

$verification_code = rand(100000, 999999);

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$db_password = "";
$dbname = "brainstorm_db";
$conn = new mysqli($servername, $username, $db_password, $dbname);
if ($conn->connect_error) {
    $response = array('success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Checa se o email já está registrado
$query = $conn->prepare("SELECT id FROM usuario WHERE email = ?");
$query->bind_param("s", $email);
$query->execute();
$query->store_result();
if ($query->num_rows > 0) {
    $response = array('success' => false, 'message' => 'Email já registrado');
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
$query->close();

// Envia o email de verificação
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

$response = array();
// Cria um novo usuário
$query = $conn->prepare("INSERT INTO usuario (nome, email, password, dt_nascimento, funcao, telefone, whatsapp, cidade, estado, auth_code, verified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
$query->bind_param("ssssssssss", $nome, $email, $password, $dt_nascimento, $funcao, $telefone, $whatsapp, $cidade, $estado, $verification_code);
if ($query->execute()) {
    $response['success'] = true;
    $response['message'] = 'Usuário registrado com sucesso';
    $response['sendMail'] = sendMail($email, 'Código de verificação', 'Seu código de verificação é: ' . $verification_code);
} else {
    $response['success'] = false;
    $response['message'] = 'Erro ao registrar usuário: ' . $conn->error;
}
$query->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>