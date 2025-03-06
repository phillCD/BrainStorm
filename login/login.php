<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Start session and clear any existing session data
session_start();
session_unset();
session_destroy();

// Sanitize and validate email
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response = array('success' => false, 'message' => 'Email inválido');
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Sanitize password
$password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

// Database connection
$servername = "localhost";
$username = "root";
$db_password = ""; // Changed variable name to avoid conflict with user password
$dbname = "brainstorm_db";
$conn = new mysqli($servername, $username, $db_password, $dbname);
if ($conn->connect_error) {
    $response = array('success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Prepare and execute query
$query = $conn->prepare("SELECT nome, password, verified FROM usuario WHERE email = ?");
$query->bind_param("s", $email);
$query->execute();
$query->store_result();
$query->bind_result($nome, $hashed_password, $verified);
$query->fetch();

$response = array();

if ($query->num_rows > 0) {
    if (!$verified) {
        $response['success'] = false;
        $response['message'] = "Sua conta ainda não foi verificada. Verifique seu email para o código de verificação.";
        $query->close();
        $conn->close();
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    } else {
        if (password_verify($password, $hashed_password)) {
            session_start();
            $_SESSION['nome'] = $nome;
            $_SESSION['verified'] = true;
            $response['success'] = true;
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