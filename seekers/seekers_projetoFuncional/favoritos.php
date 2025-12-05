<?php
session_start();

if(!isset($_SESSION['usuarioLogado'])){
    header("Location: login.php");
    exit;
}

require_once 'config/database.php';

$titulo = "Meus Favoritos";
include 'includes/header.php';

$conexao = conexao();

// Buscar builds favoritas
$sql = "SELECT bf.*, b.nome, b.jogo, b.classe, b.nivel, b.curtidas, u.username, b.id as build_id 
        FROM builds_favoritas bf 
        JOIN builds b ON bf.build_id = b.id 
        JOIN usuarios u ON b.autor_id = u.id 
        WHERE bf.usuario_id = :usuario_id 
        ORDER BY bf.data_favorito DESC";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':usuario_id', $_SESSION['usuarioLogado']);
$stmt->execute();
$builds_favoritas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar sessões favoritas
$sql = "SELECT sf.*, s.jogo, s.plataforma, s.tipo_sessao, s.descricao, s.status, u.username, s.id as sessao_id 
        FROM sessoes_favoritas sf 
        JOIN sessoes_jogo s ON sf.sessao_id = s.id 
        JOIN usuarios u ON s.criador_id = u.id 
        WHERE sf.usuario_id = :usuario_id 
        ORDER BY sf.data_favorito DESC";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':usuario_id', $_SESSION['usuarioLogado']);
$stmt->execute();
$sessoes_favoritas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h2 class="text-warning mb-4">Meus Favoritos</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="text-white mb-0">Builds Favoritas</h5>
                </div>
                <div class="card-body">
                    <?php if(empty($builds_favoritas)): ?>
                        <p class="text-muted">Você ainda não tem builds favoritas.</p>
                        <a href="builds.php" class="btn btn-outline-primary">Explorar Builds</a>
                    <?php else: ?>
                        <?php foreach($builds_favoritas as $build): ?>
                            <div class="card mb-3 bg-dark border-primary">
                                <div class="card-body">
                                    <h6 class="card-title"><?= htmlspecialchars($build['nome']) ?></h6>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <?= htmlspecialchars($build['jogo']) ?> - <?= htmlspecialchars($build['classe']) ?> Nível <?= $build['nivel'] ?><br>
                                            Por: <?= htmlspecialchars($build['username']) ?> | ❤️ <?= $build['curtidas'] ?>
                                        </small>
                                    </p>
                                    <div class="d-flex justify-content-between">
                                        <a href="build_detalhes.php?id=<?= $build['build_id'] ?>" class="btn btn-primary btn-sm">Ver Build</a>
                                        <button class="btn btn-outline-danger btn-sm" onclick="removerFavorito('build', <?= $build['build_id'] ?>)">
                                            Remover
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="text-white mb-0">Sessões Favoritas</h5>
                </div>
                <div class="card-body">
                    <?php if(empty($sessoes_favoritas)): ?>
                        <p class="text-muted">Você ainda não tem sessões favoritas.</p>
                        <a href="sessoes.php" class="btn btn-outline-primary">Explorar Sessões</a>
                    <?php else: ?>
                        <?php foreach($sessoes_favoritas as $sessao): ?>
                            <div class="card mb-3 bg-dark border-primary">
                                <div class="card-body">
                                    <h6 class="card-title"><?= htmlspecialchars($sessao['jogo']) ?></h6>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <?= htmlspecialchars($sessao['plataforma']) ?> - <?= htmlspecialchars($sessao['tipo_sessao']) ?><br>
                                            Por: <?= htmlspecialchars($sessao['username']) ?>
                                        </small>
                                    </p>
                                    <p class="card-text"><?= htmlspecialchars(substr($sessao['descricao'], 0, 80)) ?>...</p>
                                    <div class="d-flex justify-content-between">
                                        <a href="sessao_detalhes.php?id=<?= $sessao['sessao_id'] ?>" class="btn btn-primary btn-sm">Ver Sessão</a>
                                        <button class="btn btn-outline-danger btn-sm" onclick="removerFavorito('sessao', <?= $sessao['sessao_id'] ?>)">
                                            Remover
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function removerFavorito(tipo, itemId) {
    if(confirm('Deseja remover este item dos favoritos?')) {
        $.ajax({
            url: 'ajax/toggle_favorito.php',
            method: 'POST',
            data: { tipo: tipo, item_id: itemId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Erro ao remover favorito');
                }
            }
        });
    }
}
</script>

<?php include 'includes/footer.php'; ?>