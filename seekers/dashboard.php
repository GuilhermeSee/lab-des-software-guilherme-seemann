<?php
session_start();

if(!isset($_SESSION['usuarioLogado'])){
    header("Location: login.php");
    exit;
}

require_once 'includes/autoload.php';

$usuario = new Usuario();
$usuario->buscarPorUsername($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seekers - Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <h1>SEEKERS</h1>
                <p>Bem-vindo, <?= $_SESSION['username'] ?>!</p>
            </div>

            <div style="text-align: center; margin: 20px 0;">
                <p>Dashboard em desenvolvimento...</p>
                <p>Funcionalidades futuras:</p>
                <ul style="text-align: left; margin: 20px 0;">
                    <li>Sistema de Matchmaking</li>
                    <li>Criador de Builds</li>
                    <li>Fórum da Comunidade</li>
                    <li>Progresso Colaborativo</li>
                </ul>
            </div>

            <a href="logout.php" class="btn-login" style="display: block; text-align: center; text-decoration: none;">Sair</a>
        </div>
    </div>
</body>
</html>