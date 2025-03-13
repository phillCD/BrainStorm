<?php
// Habilita a exibição de erros para debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
date_default_timezone_set('America/Sao_Paulo');

// Limpa e valida os dados de entrada
$descricao = htmlspecialchars($_POST['descricao'], ENT_QUOTES, 'UTF-8');
$categoria = htmlspecialchars($_POST['categoria'], ENT_QUOTES, 'UTF-8');
$nome_solicitante = htmlspecialchars($_POST['nome-solicitante'], ENT_QUOTES, 'UTF-8');
$telefone_solicitante = htmlspecialchars($_POST['telefone-solicitante-1'], ENT_QUOTES, 'UTF-8');
if (isset($_POST['telefones-adicionais'])) {
    $telefones_adicionais = htmlspecialchars($_POST['telefones-adicionais'], ENT_QUOTES, 'UTF-8');
    $telefones = array($telefone_solicitante, $telefones_adicionais);
} else {
    $telefones = array($telefone_solicitante);
}
$anexos = $_FILES;
$anexos_base64 = [];
$data_abertura = date('Y-m-d H:i:s');
$autor = $_SESSION['nome'];
$alteracao_inicial[] = [
    'data' => $data_abertura,
    'autor' => $autor,
    'alteracao' => 'Chamado aberto'
];

foreach ($anexos['anexos']['tmp_name'] as $key => $tmp_name) {
    $file_content = file_get_contents($tmp_name);
    $base64 = base64_encode($file_content);
    $anexos_base64[] = [
        'name' => $anexos['anexos']['name'][$key],
        'type' => $anexos['anexos']['type'][$key],
        'base64' => $base64
    ];
}

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

// Insere o chamado no banco de dados
$telefones_json = json_encode($telefones);
$anexos_json = json_encode($anexos_base64);
$alteracao_inicial_json = json_encode($alteracao_inicial);
$query = $conn->prepare("INSERT INTO chamado (id_autor, descricao, tipo_incidente, nome_solicitante, telefone_solicitante, data_abertura, autor_chamado, alteracoes, anexos) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$query->bind_param("sssssssss", $_SESSION['id'], $descricao, $categoria, $nome_solicitante, $telefones_json, $data_abertura, $autor, $alteracao_inicial_json, $anexos_json);
if ($query->execute()) {
    $response = array('success' => true, 'message' => 'Chamado aberto com sucesso');
} else {
    $response = array('success' => false, 'message' => 'Erro ao abrir chamado: ' . $query->error);
}
$query->close();
$conn->close();
header('Content-Type: application/json');
echo json_encode($response);
?>