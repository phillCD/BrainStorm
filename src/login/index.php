<?php
// Evita que o usuário tenha dados de uma sessão antiga
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../stylesheet/style.css">
    <link rel="icon" type="image/x-icon" href="../../assets/storm-icon.svg">
</head>
<body>

<div class="container">
    <div class="simple-container">
        <h3 class="text-center">Login</h3>
        <form id="login-form">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
            <div class="text-center mt-3">
                <p id="error-msg" style="color: red;"></p>
            </div>
        </form>
        <div class="signup-link text-center mt-3">
            <p>Não possui uma conta?</p>
            <a href="../signup/index.php">Criar uma conta</a>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
    $('#login-form').submit(function (e) {
        e.preventDefault();

        var email = $('#email').val();
        var password = $('#password').val();

        // Envia os dados do formulário para o login do usuário
        $.ajax({
            url: 'login.php',
            type: 'POST',
            data: { email: email, password: password },
            dataType: 'json',
            success: function (response) {
                // Se o login for bem sucedido e a conta for verificada, redireciona para a página inicial
                if (response.success && response.verified) {
                    window.location = '../home/index.php';
                }
                // Se o login for bem sucedido e a conta não for verificada, redireciona para a página de verificação
                else if (response.success && !response.verified) {
                    console.log(response.email);
                    alert('Conta não verificada');
                    window.location = '../verifica_codigo/index.php';
                } else {
                    $('#error-msg').text('Email ou senha inválidos');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#error-msg').text('Erro ao processar o login: ' + textStatus + ' - ' + errorThrown);
            }
        });
    });
});
</script>
</body>
</html>