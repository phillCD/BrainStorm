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
    <title>Home Screen</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Content */
        .content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
            z-index: 2;
        }

        .content h1 {
            font-size: 3rem;
            font-weight: bold;
        }

        .content h2{
            font-weight: bold;
            margin-top: 20px;
        }

        .content p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .overlay {
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgb(0, 0, 0, 0.5);
            z-index: 1;
        }
    </style>
</head>
<body>
<div class="">
    <div class="overlay"></div>
    <div class="content">
        <h1>Bem-vindo(a) ao BrainStorm!</h1>
        <p>Um sistema para abrir e gerenciar chamados de TI.</p>
        <a href="signup/index.php" class="btn btn-primary">Cadastrar-se</a>
        <h2>Já possui cadastro?</h2>
        <a href="login/index.php" class="btn btn-primary">Entrar</a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
