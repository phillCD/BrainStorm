<?php
session_start();
// Código para evitar acesso direto à página pela URL
if (!isset($_SESSION['email'])) {
    header("Location: ../login/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação de Código</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../stylesheet/style.css">
    <link rel="icon" type="image/x-icon" href="../../assets/storm-icon.svg">
</head>
<body>

<div class="container">
    <div class="simple-container">
        <h3 class="text-center">Verificação de Código</h3>
        <form id="verify-form">
            <div class="mb-3">
                <label for="code" class="form-label">Código de Verificação</label>
                <input type="text" class="form-control" id="code" name="code" maxlength="6" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Verificar</button>
            <div class="text-center mt-3">
                <p id="error-msg" style="color: red;"></p>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#verify-form').submit(function (e) {
            // Evita o envio do formulário sem os dados preenchidos
            e.preventDefault();

            var code = $('#code').val();

            // Envia a requisição para o servidor verificar e validar o código do usuário
            $.ajax({
                url: 'verify.php',
                type: 'POST',
                data: { code: code , email: '<?php echo $_SESSION['email']; ?>' },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        alert(response.message);
                        window.location = '../home/index.php';
                    } else {
                        $('#error-msg').text(response.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('#error-msg').text('Erro ao processar a verificação: ' + textStatus + ' - ' + errorThrown);
                }
            });
        });
    });
</script>
</body>
</html>
