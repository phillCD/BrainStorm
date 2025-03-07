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
    <title>Signup Page</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <link rel="stylesheet" href="../../stylesheet/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" type="image/x-icon" href="../../assets/storm-icon.svg">
</head>
<body>

<div class="container">
    <div class="signup-container">
        <h3 class="text-center">Signup</h3>
        <form id="signup-form">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="passwordConfirm" class="form-label">Confirmar Senha</label>
                <input type="password" class="form-control" id="passwordConfirm" name="passwordConfirm" required>
            </div>
            <div class="mb-3">
                <label for="dt_nascimento" class="form-label">Data de Nascimento</label>
                <input type="date" class="form-control" id="dt_nascimento" name="dt_nascimento" required>
            </div>
            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="tel" class="form-control" id="telefone" name="telefone" required>
            </div>
            <div class="mb-3">
                <label for="whatsapp" class="form-label">WhatsApp</label>
                <input type="tel" class="form-control" id="whatsapp" name="whatsapp" required>
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-control" id="estado" name="estado" required>
                    <option value="">Selecione o Estado</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="cidade" class="form-label">Cidade</label>
                <select class="form-control" id="cidade" name="cidade" required>
                    <option value=""></option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Registrar</button>
            <div class="text-center mt-3">
                <p id="error-msg" style="color: red;"></p>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Máscaras para os campos de telefone e whatsapp
        $('#telefone').mask('(00) 0000-0000');
        $('#whatsapp').mask('(00) 00000-0000');

        // Função para carregar a lista de estados
        $.ajax({
            url: 'https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                var estadoSelect = $('#estado');
                $.each(data, function (index, estado) {
                    estadoSelect.append($('<option>', {
                        value: estado.id,
                        text: estado.nome
                    }));
                });
            }
        });

        // Função para carregar as cidades de acordo com o estado selecionado
        $('#estado').change(function () {
            var estado = $(this).val();
            if (estado) {
                $.ajax({
                    url: 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/' + estado + '/municipios?orderBy=nome',
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        var cidadeSelect = $('#cidade');
                        cidadeSelect.empty();
                        cidadeSelect.append($('<option>', {
                            value: '',
                            text: 'Selecione a Cidade'
                        }));
                        $.each(data, function (index, cidade) {
                            cidadeSelect.append($('<option>', {
                                value: cidade.nome,
                                text: cidade.nome
                            }));
                        });
                    }
                });
            } else {
                $('#cidade').empty();
                $('#cidade').append($('<option>', {
                    value: '',
                    text: 'Selecione a Cidade'
                }));
            }
        });

        $('#signup-form').submit(function (e) {
            // Evita o envio do formulário sem os dados preenchidos
            e.preventDefault();

            var formData = {
                nome: $('#nome').val(),
                email: $('#email').val(),
                password: $('#password').val(),
                dt_nascimento: $('#dt_nascimento').val(),
                funcao: 'USUARIO',
                telefone: $('#telefone').val(),
                whatsapp: $('#whatsapp').val(),
                cidade: $('#cidade').val(),
                estado: $('#estado').val()
            };

            // Verifica se as senhas são iguais
            if (formData.password != $('#passwordConfirm').val()) {
                $('#error-msg').text('As senhas não conferem');
                return;
            }

            // Função para verificação de maioridade do usuário
            function calculateAge (birthDate, otherDate) {
                birthDate = new Date(birthDate);
                otherDate = new Date(otherDate);

                var years = (otherDate.getFullYear() - birthDate.getFullYear());

                if (otherDate.getMonth() < birthDate.getMonth() || 
                    otherDate.getMonth() == birthDate.getMonth() && otherDate.getDate() < birthDate.getDate()) {
                    years--;
                }

                return years;
            }
            var age = calculateAge(formData.dt_nascimento, new Date());
            if (age < 18) {
                $('#error-msg').text('Você deve ter mais de 18 anos para se cadastrar');
                return;
            }

            // Requisição para registrar o usuário
            $.ajax({
                url: 'signup.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        // Requisição para enviar email de forma assíncrona
                        // sem travar a página esperando a resposta da função de enviar o email, que é uma operação mais lenta
                        $.ajax({
                            url: '../core/sendMail.php',
                            type: 'POST',
                            data:{email: response.sendEmail.email, code: response.sendEmail.verification_code},
                            dataType: 'json',
                            success: function (response) {
                                console.log(response);
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                console.log('Erro ao enviar email: ' + textStatus + ' - ' + errorThrown);
                            }
                        })
                        alert(response.message);
                        window.location = '../index.php';
                    } else {
                        $('#error-msg').text(response.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('#error-msg').text('Erro ao processar o registro: ' + textStatus + ' - ' + errorThrown);
                }
            });
        });
});
</script>
</body>
</html>