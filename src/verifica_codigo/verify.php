<?php
session_start();

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$db_password = "";
$dbname = "brainstorm_db";
$conn = new mysqli($servername, $username, $db_password, $dbname);
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

// Recebe os dados da requisição
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$code = $_POST['code'];

// Verifica se o email e o código estão corretos, e se a conta ainda não foi verificada
$query = $conn->prepare("SELECT id FROM usuario WHERE email = ? AND auth_code = ? AND verified = 0");
$query->bind_param("ss", $email, $code);
$query->execute();
$query->store_result();

if ($query->num_rows > 0) {
    // Atualiza para marcar o usuário como verificado
    $update = $conn->prepare("UPDATE usuario SET verified = 1 WHERE email = ?");
    $update->bind_param("s", $email);
    if ($update->execute()) {
        echo json_encode(['success' => true, 'message' => 'Conta verificada com sucesso']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar verificação']);
    }
    $update->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Código inválido ou conta já verificada']);
}

$query->close();
$conn->close();
?>
