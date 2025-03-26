<?php
// Verifica se o usuário possui uma sessão ativa e evita acesso via url
include_once('../core/checkSession.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abrir Chamado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
    <link rel="icon" type="image/x-icon" href="../../assets/storm-icon.svg">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="../home/">BrainStorm</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
    
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Chamados
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="servicesDropdown">
                            <li><a class="dropdown-item" href="#">Abrir Chamados</a></li>
                            <li><a class="dropdown-item" href="../lista_chamados/">Ver Chamados</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                        </svg>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li>
                            <a class="dropdown-item" href="#">Meu Perfil</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">Meus Chamados</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">Sair</a>
                        </li>
                    </ul>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-6 offset-md-3">
                <h1>Abrir Chamado</h1>
                <form id="signup-form">
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="summernote" name="descricao" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoria</label>
                        <select class="form-select" id="categoria" name="categoria" required>
                            <option value="Hardware">Hardware</option>
                            <option value="Software">Software</option>
                            <option value="Rede">Rede</option>
                        </select>
                    </div>
                    <div class="row align-items-start">
                        <div class="col mb-3">
                            <label for="nome-solicitante" class="form-label">Nome do Solicitante</label>
                            <input type="text" class="form-control" id="nome-solicitante" name="nome-solicitante" required>
                        </div>
                        <div class="col mb-3">
                            <label for="telefone-solicitante-1" class="form-label">Telefone do Solicitante</label>
                            <input type="tel" class="form-control" id="telefone-solicitante-1" name="telefone-solicitante-1" required>
                        </div>
                    </div>
                    <div id="additional-phones"></div>
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" class="btn btn-secondary" id="add-phone">Adicionar Telefone</button>
                    </div>
                    <div class="mb-3">
                        <label for="anexos" class="form-label">Anexos</label>
                        <input class="form-control" type="file" id="anexos" name="anexos" multiple required>
                    </div>
                    <button type="submit" class="btn btn-primary">Abrir Chamado</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#summernote').summernote();
            // Máscara para telefone
            var comportamentoMascara = function (val) {
                return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
            },
            options = {
                onKeyPress: function (val, e, field, options) {
                    field.mask(comportamentoMascara.apply({}, arguments), options);
                }
            };
            $('#telefone-solicitante-1').mask(comportamentoMascara, options);

            // Adiciona máscara para telefones adicionais
            var indexTelefone = 2;
            $('#add-phone').click(function () {
                var newPhoneField = `
                    <div class="mb-3">
                        <label for="telefone-solicitante-${indexTelefone}" class="form-label">Telefone do Solicitante ${indexTelefone}</label>
                        <input type="tel" class="form-control" id="telefone-solicitante-${indexTelefone}" name="telefone-solicitante-${indexTelefone}" required>
                    </div>
                `;
                $('#additional-phones').append(newPhoneField);
                $(`#telefone-solicitante-${indexTelefone}`).mask(comportamentoMascara, options);
                indexTelefone++;
            });

            // Envia o formulário de abertura de chamado
            $('#signup-form').submit(function (event) {
                event.preventDefault();
                var formData = new FormData(this);
                
                $('#additional-phones input[id^="telefone-solicitante-"]').each(function () {
                    console.log($(this).val());
                    formData.append('telefones-adicionais[]', $(this).val());
                });

                var anexos = $('#anexos')[0].files;
                for (var i = 0; i < anexos.length; i++) {
                    console.log(anexos[i]);
                    formData.append('anexos[]', anexos[i]);
                }
                $.ajax({
                    type: 'POST',
                    url: 'criar_chamado.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        console.log(data);
                        alert('Chamado aberto com sucesso!')
                        window.location = '../lista_chamados/';
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            });
        });
    </script>
</body>
</html>