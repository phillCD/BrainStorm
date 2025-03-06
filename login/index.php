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
    <title>Login Page</title>
    <!-- Bootstrap CSS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="login-container">
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

        $.ajax({
            url: 'login.php',
            type: 'POST',
            data: { email: email, password: password },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    window.location = '../home/index.php';
                } else {
                    $('#error-msg').text(response.message);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#error-msg').text('Erro ao processar o login: ' + textStatus + ' - ' + errorThrown);
            }
        });
    });
});
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>