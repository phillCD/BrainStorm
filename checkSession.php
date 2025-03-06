<?php
session_start();
if (!isset($_SESSION['nome']) && $_SESSION['verified'] != true) {
    header("Location: index.php");
    exit();
}
?>