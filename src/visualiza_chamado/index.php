<?php
include_once('../core/checkSession.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Chamado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../stylesheet/style.css">
    <link rel="icon" type="image/x-icon" href="../../assets/storm-icon.svg">
    <link rel="stylesheet" href="../../stylesheet/style.css">
    <style>
        label {
            cursor: pointer;
        }
    </style>
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
                            <li><a class="dropdown-item" href="../criar_chamado/">Abrir Chamados</a></li>
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
            <div class="col-md-8 offset-md-2">
                <div class="text-center">
                    <h1>Detalhes do Chamado</h1>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div id="chamado-details"></div>
                    </div>
                </div>
                <a href="../lista_chamados/" class="btn btn-danger mt-3">Voltar</a>
            </div>
        </div>
    </div>
    <script>
        $(document).ready( function() {
            // Cria um parser para converter a descrição do chamado de string pra HTML
            const parser = new DOMParser();
            const urlParams = new URLSearchParams(window.location.search);
            console.log(urlParams);
            const $id = urlParams.get('id');
            console.log($id);
            // Pega as informações do chamado
            $.ajax({
                url: 'get_chamado.php',
                type: 'POST',
                data: { id: $id },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        const chamado = response.chamado;
                        const descricao = parser.parseFromString(chamado.descricao, 'text/html').body.textContent;
                        let telefones = {};
                        console.log(chamado);
                        $('#chamado-details').html(`
                            <h3 class="card-title">Chamado ${chamado.id}</h5>
                            <p class="card-text" id="descricao">${descricao}</p>
                            <h5 class="card-title">Solicitante</h5>
                            <p class="card-text">Nome: ${chamado.nome_solicitante}</p>
                            <div id="telefones" class="row align-items-start"></div>
                            <h5 class="card-title">Anexos</h5>
                            <div id="anexos"></div>
                            <input type="file" id="upload" name="upload" hidden multiple>
                            <label for="upload" class="form-label text-danger p-2">Adicionar Anexos</label>
                            <h5 class="card-title">Novas Descrições</h5>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3" required></textarea>
                            <button id="addDescricao" class="btn btn-primary mt-3">Adicionar Descrição</button>
                            <h5 class="card-title">Histórico</h5>
                        `);

                        
                        // Pra cada telefone, cria um parágrafo com o número
                        for (let i = 0; i < chamado.telefone_solicitante.length; i++) {
                            telefones[`telefone_${i}`] = chamado.telefone_solicitante[i];
                            $('#telefones').append(`<p class="col card-text">Telefone ${i + 1}: ${chamado.telefone_solicitante[i]}</p>`);
                        }

                        // Pra cada anexo, cria um botão com o nome do arquivo
                        for (let i = 0; i < chamado.anexos.length; i++) {
                            let anexo = chamado.anexos[i];
                            let decodedData = atob(anexo.base64);
                            let byteArray = new Uint8Array(decodedData.length);
                            for (let j = 0; j < decodedData.length; j++) {
                                byteArray[j] = decodedData.charCodeAt(j);
                            }
                            let blob = new Blob([byteArray], { type: anexo.type });
                            let url = URL.createObjectURL(blob);
                            $('#anexos').append(`<button class="btn link-primary" onclick="window.open('${url}', '_blank')">${anexo.name}</button>`);
                        }
                        $.each(chamado.alteracoes.reverse(), function(index, alteracao) {
                            $('#chamado-details').append(`
                                <p class="card-text"><small class="text-muted">${alteracao.alteracao} por ${alteracao.autor} em ${alteracao.data}</small></p>
                            `);
                        });
                        // Adiciona descrição
                        $('#addDescricao').click(function() {
                            let descricao = $('#descricao').val();
                            $.ajax({
                                url: '../core/addDescricao.php',
                                type: 'POST',
                                data: { id: $id, descricao: descricao },
                                dataType: 'json',
                                success: function(response) {
                                    console.log(response);
                                    if (response.success) {
                                        alert("Descrição adicionada com sucesso");
                                        window.location.reload();
                                    } else {
                                        console.log(response);
                                        alert("Erro ao adicionar descrição");
                                    }
                                },
                                error: function(response) {
                                    alert("Erro ao adicionar descrição");
                                    console.log(response.responseText);
                                }
                            });
                        });
                        // Adiciona anexos
                        $('#upload').change(function() {
                            const data = new FormData();
                            data.append('id', $id);
                            const files = $("#upload")[0].files;
                            console.log(files);
                            for (let i = 0; i < files.length; i++) {
                                data.append('anexos[]', files[i]);
                            }
            
                            $.ajax({
                                url: '../core/addAnexo.php',
                                type: 'POST',
                                data: data,
                                contentType: false,
                                processData: false,
                                dataType: 'json',
                                success: function(response) {
                                    console.log(response);
                                    console.log(response.success);
                                    if (response.success) {
                                        alert("Arquivo anexado com sucesso");
                                        window.location.reload();
                                    } else {
                                        console.log(response);
                                        alert("Erro ao anexar arquivo");
                                    }
                                },
                                error: function(response) {
                                    alert("Erro ao anexar arquivo");
                                    console.log(response.responseText);
                                }
                            });
                        });
                    } else{
                        console.log(response);
                    }
                }
            });
        });
    </script>
</body>
</html>