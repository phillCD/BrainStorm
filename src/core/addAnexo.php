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
// Prepara e executa a query para inserir anexo no chamado
$query = $conn->prepare("SELECT anexos, alteracoes FROM chamado WHERE id = ?");
$query->bind_param("i", $_POST['id']);
$query->execute();
$query->store_result();
$query->bind_result($anexos_anteriores, $alteracoes);
$query->fetch();

// Converte os anexos para base64
$anexos = $_FILES;
$anexos_base64 = [];
foreach ($anexos['anexos']['tmp_name'] as $key => $tmp_name) {
    $file_content = file_get_contents($tmp_name);
    $base64 = base64_encode($file_content);
    $anexos_base64[] = [
        'name' => $anexos['anexos']['name'][$key],
        'type' => $anexos['anexos']['type'][$key],
        'base64' => $base64
    ];
}
$alteracao[] = [
    'data' => date('Y-m-d H:i:s'),
    'autor' => $_SESSION['nome'],
    'alteracao' => 'Anexo adicionado'
];

// Decodifica as alterações existentes e garante que seja um array
$alteracoes_json = json_decode($alteracoes, true);
if (!is_array($alteracoes_json)) {
    $alteracoes_json = [];
}
$alteracoes_json = array_merge($alteracoes_json, $alteracao);
$alteracoes_json_encoded = json_encode($alteracoes_json);

// Decodifica os anexos existentes e garante que seja um array
$anexos_json = json_decode($anexos_anteriores, true);
if (!is_array($anexos_json)) {
    $anexos_json = [];
}
$anexos_json = array_merge($anexos_json, $anexos_base64);
$anexos_json_encoded = json_encode($anexos_json);

$query = $conn->prepare("UPDATE chamado SET anexos = ?, alteracoes = ? WHERE id = ?");
$query->bind_param("ssi", $anexos_json_encoded, $alteracoes_json_encoded, $_POST['id']);
if ($query->execute()) {
    $response['success'] = true;
    $response['message'] = 'Anexo adicionado com sucesso';
} else {
    $response['success'] = false;
    $response['message'] = 'Erro ao adicionar anexo';
}

echo json_encode($response);
?>