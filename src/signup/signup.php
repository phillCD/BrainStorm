<?php
// Habilita a exibição de erros para debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Starta a sessão e limpa os dados antigos
session_start();
session_unset();
session_destroy();

// Limpa e valida os dados de entrada
$nome = htmlspecialchars($_POST['nome'], ENT_QUOTES, 'UTF-8');
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response = array('success' => false, 'message' => 'Email inválido');
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
// Usa password_hash para criptografar a senha
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$dt_nascimento = $_POST['dt_nascimento'];
$funcao = htmlspecialchars($_POST['funcao'], ENT_QUOTES, 'UTF-8');
$telefone = htmlspecialchars($_POST['telefone'], ENT_QUOTES, 'UTF-8');
$whatsapp = htmlspecialchars($_POST['whatsapp'], ENT_QUOTES, 'UTF-8');
$cidade = htmlspecialchars($_POST['cidade'], ENT_QUOTES, 'UTF-8');
$estado = htmlspecialchars($_POST['estado'], ENT_QUOTES, 'UTF-8');


// Gera um código de verificação de 6 dígitos
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


// Cria um novo usuário
$response = array();
$query = $conn->prepare("INSERT INTO usuario (nome, email, password, dt_nascimento, funcao, telefone, whatsapp, cidade, estado, auth_code, verified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
$query->bind_param("ssssssssss", $nome, $email, $password, $dt_nascimento, $funcao, $telefone, $whatsapp, $cidade, $estado, $verification_code);
if ($query->execute()) {
    $response['success'] = true;
    $response['message'] = 'Usuário registrado com sucesso';
    $response['sendEmail'] = array('email' => $email, 'verification_code' => $verification_code);
    $_SESSION['email'] = $email;
} else {
    $response['success'] = false;
    $response['message'] = 'Erro ao registrar usuário: ' . $conn->error;
}
$query->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>