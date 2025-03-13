<?php
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
// Prepara e executa a query para buscar o chamado
$query = $conn->prepare("SELECT * FROM chamado WHERE id = ?");
$query->bind_param("i", $_POST['id']);
$query->execute();
$query->store_result();
$query->bind_result($id, $descricao, $tipo_incidente, $data_abertura, $id_autor, $autor_chamado, $anexos, $alteracoes, $nome_solicitante, $telefone_solicitante);
$query->fetch();

$chamado['id'] = $id;
$chamado['descricao'] = $descricao;
$chamado['tipo_incidente'] = $tipo_incidente;
$chamado['nome_solicitante'] = $nome_solicitante;
$chamado['telefone_solicitante'] = json_decode($telefone_solicitante);
$chamado['data_abertura'] = $data_abertura;
$chamado['autor_chamado'] = $autor_chamado;
$chamado['alteracoes'] = json_decode($alteracoes);
$chamado['anexos'] = json_decode($anexos);
$response['chamado'] = $chamado;
$response['success'] = true;

header('Content-Type: application/json');
echo json_encode($response);
?>