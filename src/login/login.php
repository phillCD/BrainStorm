<?php
// Habilita a exibição de erros para debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Starta a sessão e limpa os dados antigos
session_start();
session_unset();
session_destroy();

// Limpa e valida o email
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response = array('success' => false, 'message' => 'Email inválido');
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Limpa a senha
$password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

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

// Prepara e executa a query para buscar o usuário
$query = $conn->prepare("SELECT nome, password, verified FROM usuario WHERE email = ?");
$query->bind_param("s", $email);
$query->execute();
$query->store_result();
$query->bind_result($nome, $hashed_password, $verified);
$query->fetch();

$response = array();

// Verifica se o usuário existe
if ($query->num_rows > 0) {
    // Se o usuário não foi verificado, retorna o email para ser verificado
    if ($verified == 0) {
        session_start();
        $response['success'] = true;
        $response['verified'] = false;
        $response['email'] = $email;
        $_SESSION['verified'] = false;
        $_SESSION['nome'] = $nome;
        $_SESSION['email'] = $email;
        $query->close();
        $conn->close();
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    } else {
        // Se o usuário foi verificado, verifica a senha e inicia a sessão
        if (password_verify($password, $hashed_password)) {
            session_start();
            $_SESSION['nome'] = $nome;
            $_SESSION['verified'] = true;
            $_SESSION['email'] = $email;
            $response['success'] = true;
            $response['verified'] = true;
        } else {
            $response['success'] = false;
            $response['message'] = "Usuário ou senha inválidos";
        }
    }
} else {
    $response['success'] = false;
    $response['message'] = "Usuário ou senha inválidos";
}

$query->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>