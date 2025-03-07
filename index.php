<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="stylesheet/style.css">
    <link rel="icon" type="image/x-icon" href="assets/storm-icon.ico">
</head>
<body>
<div class="">
    <div class="overlay"></div>
    <div class="content">
        <h1>Bem-vindo(a) ao BrainStorm!</h1>
        <p>Um sistema para abrir e gerenciar chamados de TI.</p>
        <a href="src/signup" class="btn btn-primary">Cadastrar-se</a>
        <h2>Já possui cadastro?</h2>
        <a href="src/login" class="btn btn-primary">Entrar</a>
    </div>
</div>
</body>
</html>
