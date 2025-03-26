<?php
// Debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
date_default_timezone_set('America/Sao_Paulo');

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

$response = array();
$chamado = array();
// Prepara e executa a query para inserir descrição no chamado
$query = $conn->prepare("SELECT alteracoes FROM chamado WHERE id = ?");
$query->bind_param("i", $_POST['id']);
$query->execute();
$query->store_result();
$query->bind_result($alteracoes);
$query->fetch();

$descricao = htmlspecialchars($_POST['descricao'], ENT_QUOTES, 'UTF-8');
$alteracao[] = [
    'data' => date('Y-m-d H:i:s'),
    'autor' => $_SESSION['nome'],
    'alteracao' => 'Descrição adicionada: ' . $descricao
];

// Decode existing alterations and ensure it's an array
$alteracoes_json = json_decode($alteracoes, true);
if (!is_array($alteracoes_json)) {
    $alteracoes_json = [];
}
$alteracoes_json = array_merge($alteracoes_json, $alteracao);
$alteracoes_json_encoded = json_encode($alteracoes_json);

// Update the description in the database
$query = $conn->prepare("UPDATE chamado SET alteracoes = ? WHERE id = ?");
$query->bind_param("si", $alteracoes_json_encoded, $_POST['id']);
if ($query->execute()) {
    $response = array('success' => true, 'message' => 'Descrição adicionada com sucesso', 'descricao' => $descricao);
} else {
    $response = array('success' => false, 'message' => 'Erro ao adicionar descrição: ' . $conn->error);
}

header('Content-Type: application/json');
echo json_encode($response);
?>