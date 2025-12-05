<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="plagiarism" content="Este projeto foi desenvolvido por Guilherme Seemann para a disciplina de Laboratório de Desenvolvimento de Software - IFRS Campus Canoas">
    <title><?= isset($titulo) ? $titulo : 'Seekers' ?> - Plataforma Souls-like</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand text-warning fw-bold" href="index.php">SEEKERS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="builds.php">Builds</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sessoes.php">Sessões</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contato.php">Contato</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php if(isset($_SESSION['usuarioLogado'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">Perfil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="favoritos.php">Favoritos</a>
                        </li>
                        <li class="nav-item">
                            <?php
                            if(isset($_SESSION['usuarioLogado'])) {
                                require_once 'config/database.php';
                                $conexao_header = conexao();
                                $sql_notif = "SELECT COUNT(*) as total FROM notificacoes WHERE usuario_id = :id AND lida = 0";
                                $stmt_notif = $conexao_header->prepare($sql_notif);
                                $stmt_notif->bindParam(':id', $_SESSION['usuarioLogado']);
                                $stmt_notif->execute();
                                $notif_count = $stmt_notif->fetch()['total'];
                            }
                            ?>
                            <a class="nav-link" href="notificacoes.php">
                                Notificações <?php if(isset($notif_count) && $notif_count > 0): ?><span class="badge bg-danger"><?= $notif_count ?></span><?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Sair</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cadastro.php">Cadastro</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>