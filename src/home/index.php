<?php
include_once('../core/checkSession.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrainStorm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../stylesheet/style.css">
    <link rel="icon" type="image/x-icon" href="../../assets/storm-icon.svg">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">BrainStorm</a>
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
                            <li><a class="dropdown-item" href="../criar_chamado/index.html">Abrir Chamados</a></li>
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
        <h1 class="text-center mt-5">Bem-vindo ao BrainStorm</h1>
        <div class="conteudo">
            <div class="row align-items-start p-2">
                <div class="box-container">
                    <div class="col text-center">
                        <h3>Abrir Chamado</h3>
                        <div class="p-3">
                            <p>
                                Para abrir um chamado, você precisará de algumas informações primeiro. Será necessário a descrição do problema,
                                o tipo de incidente que se refere o chamado, contato da pessoa que solicitou o chamado (Nome e Telefone, 
                                podendo ter 1 ou mais telefone para contato), anexos e observações adicionais. Assim que tiver todas essas
                                informações, poderá prosseguir com a abertura do chamado.
                            </p>
                        </div>
                        <a href="../criar_chamado/">
                            <button type="button" class="btn btn-primary">Abrir</button>
                        </a>
                    </div>
                </div>
                <div class="box-container">
                    <div class="col text-center">
                        <h3>Ver Chamados</h3>
                        <div class="p-3">
                            <p>
                                Aqui você poderá visualizar os chamados abertos para a sua equipe de TI, onde poderá verificar o status de
                                cada chamado e as alterações feitas pelos técnicos em formato de Timeline. Poderá também adicionar novos anexos e
                                novas descrições ao chamado.
                            </p>
                        </div>
                        <button type="button" class="btn btn-primary">Ver</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
