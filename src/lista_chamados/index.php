<?php
include_once('../core/checkSession.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Chamados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" type="image/x-icon" href="../../assets/storm-icon.svg">
    <link rel="stylesheet" href="../../stylesheet/style.css">
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
                            <li><a class="dropdown-item" href="#">Ver Chamados</a></li>
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
                    <h1>Lista de Chamados</h1>
                </div>
                <div class="list-group">
                    <div id="lista-chamados"></div>
                </div>

        </div>
    </div>
    <script>
        $(document).ready( function() {
            $chamados = '';
            const parser = new DOMParser();
            // Pega e exibe a lista de chamados
            $.ajax({
                url: 'list_chamados.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        console.log(response);
                        response.chamados.forEach(chamado => {
                            let descricao = parser.parseFromString(chamado.descricao, 'text/html').body.textContent;
                            $chamados += `
                                <a href="../visualiza_chamado/?id=${chamado.id}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">Chamado ${chamado.id}</h5>
                                        <small>${chamado.data_abertura}</small>
                                    </div>
                                    <p class="mb-1">${descricao}</p>
                                    <div class="d-flex w-100 justify-content-between">
                                        <p class="mb-1">${chamado.tipo_incidente}</p>
                                        <small>Aberto por ${chamado.autor_chamado}</small>
                                    </div>
                                </a>
                            `;
                        });
                        $('#lista-chamados').html($chamados);
                        } else {
                            console.log(response);
                            alert(response.message);
                        }
                    },
            });
        });
    </script>
</body>
</html>