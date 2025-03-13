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
// Prepara e executa a query para buscar os 20 primeiros chamados
$query = $conn->prepare("SELECT id, descricao, tipo_incidente, data_abertura, autor_chamado FROM chamado");
$query->execute();
$query->store_result();
$query->bind_result($id, $descricao, $tipo_incidente, $data_abertura, $autor_chamado);

$chamados = array();
while ($query->fetch()) {
    $chamados[] = array(
        'id' => $id,
        'descricao' => $descricao,
        'tipo_incidente' => $tipo_incidente,
        'data_abertura' => $data_abertura,
        'autor_chamado' => $autor_chamado
    );
}

$response['chamados'] = $chamados;
$response['success'] = true;
header('Content-Type: application/json');
echo json_encode($response);
?>