<?php
session_start();
require_once 'config/database.php';

$titulo = "Início";
include 'includes/header.php';

// Buscar builds recentes
$conexao = conexao();
$sql = "SELECT b.*, u.username FROM builds b 
        JOIN usuarios u ON b.autor_id = u.id 
        WHERE b.publico = 1 
        ORDER BY b.criado_em DESC LIMIT 3";
$stmt = $conexao->prepare($sql);
$stmt->execute();
$builds_recentes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar sessões abertas (excluindo AI_CHAT)
$sql = "SELECT s.*, u.username FROM sessoes_jogo s 
        JOIN usuarios u ON s.criador_id = u.id 
        WHERE s.status = 'aberta' AND s.tipo_sessao != 'AI_CHAT' 
        ORDER BY s.criado_em DESC LIMIT 3";
$stmt = $conexao->prepare($sql);
$stmt->execute();
$sessoes_abertas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="hero-section">
    <div class="container">
        <h1>SEEKERS</h1>
        <p class="lead">A plataforma definitiva para jogadores de jogos souls-like</p>
        <p>Conecte-se com outros jogadores, compartilhe builds e conquiste os desafios mais difíceis juntos</p>
        <?php if(!isset($_SESSION['usuarioLogado'])): ?>
            <a href="cadastro.php" class="btn btn-primary btn-lg me-3">Junte-se à Comunidade</a>
            <a href="login.php" class="btn btn-outline-light btn-lg">Fazer Login</a>
        <?php else: ?>
            <a href="dashboard.php" class="btn btn-primary btn-lg">Acessar Perfil</a>
        <?php endif; ?>
    </div>
</div>

<div class="container my-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title text-warning">Sessões Personalizadas</h5>
                    <p class="card-text">Encontre parceiros compatíveis por plataforma, jogo e estilo de sessão</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title text-warning">Builds Personalizadas</h5>
                    <p class="card-text">Crie, compartilhe e descubra builds otimizadas para cada situação</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title text-warning">Chat com IA Especializada</h5>
                    <p class="card-text">Converse com um assistente especializado em jogos souls-like para tirar dúvidas sobre builds, estratégias e dicas</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-6">
            <h3 class="text-warning mb-4">Builds Recentes</h3>
            <?php foreach($builds_recentes as $build): ?>
                <div class="card mb-3 build-card">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($build['nome']) ?></h5>
                        <p class="card-text">
                            <small class="text-muted">
                                <?= htmlspecialchars($build['jogo']) ?> - Nível <?= $build['nivel'] ?> 
                                por <?= htmlspecialchars($build['username']) ?>
                            </small>
                        </p>
                        <p class="card-text"><?= htmlspecialchars(substr($build['descricao'], 0, 100)) ?>...</p>
                        <a href="build_detalhes.php?id=<?= $build['id'] ?>" class="btn btn-primary btn-sm">Ver Detalhes</a>
                    </div>
                </div>
            <?php endforeach; ?>
            <a href="builds.php" class="btn btn-outline-primary">Ver Todas as Builds</a>
        </div>

        <div class="col-md-6">
            <h3 class="text-warning mb-4">Sessões Abertas</h3>
            <?php foreach($sessoes_abertas as $sessao): ?>
                <div class="card mb-3 sessao-card">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($sessao['jogo']) ?></h5>
                        <p class="card-text">
                            <small class="text-muted">
                                <?= htmlspecialchars($sessao['plataforma']) ?> - <?= htmlspecialchars($sessao['tipo_sessao']) ?>
                                por <?= htmlspecialchars($sessao['username']) ?>
                            </small>
                        </p>
                        <p class="card-text"><?= htmlspecialchars($sessao['descricao']) ?></p>
                        <span class="badge bg-success status-aberta">Aberta</span>
                        <a href="sessao_detalhes.php?id=<?= $sessao['id'] ?>" class="btn btn-primary btn-sm float-end">Participar</a>
                    </div>
                </div>
            <?php endforeach; ?>
            <a href="sessoes.php" class="btn btn-outline-primary">Ver Todas as Sessões</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>