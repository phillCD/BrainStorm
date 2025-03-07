<?php
//Código para evitar acesso direto à página pela URL
session_start();
if (!isset($_SESSION['nome']) && $_SESSION['verified'] != true) {
    header("Location: index.php");
    exit();
}
?>