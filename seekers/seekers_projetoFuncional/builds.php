<?php
session_start();
require_once 'config/database.php';

$titulo = "Builds da Comunidade";
include 'includes/header.php';

// Buscar todas as builds públicas
$conexao = conexao();
$sql = "SELECT b.*, u.username FROM builds b 
        JOIN usuarios u ON b.autor_id = u.id 
        WHERE b.publico = 1 
        ORDER BY b.criado_em DESC";
$stmt = $conexao->prepare($sql);
$stmt->execute();
$builds = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-warning">Builds da Comunidade</h2>
                <?php if(isset($_SESSION['usuarioLogado'])): ?>
                    <a href="criar_build.php" class="btn btn-primary">Criar Nova Build</a>
                <?php endif; ?>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <input type="text" class="form-control" id="busca-builds" placeholder="Buscar builds...">
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="filtro-jogo">
                        <option value="">Todos os Jogos</option>
                        <option value="Dark Souls">Dark Souls</option>
                        <option value="Dark Souls 3">Dark Souls 3</option>
                        <option value="Elden Ring">Elden Ring</option>
                        <option value="Bloodborne">Bloodborne</option>
                        <option value="Sekiro">Sekiro</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-primary" onclick="buscarBuilds()">Buscar</button>
                </div>
            </div>

            <div id="lista-builds">
                <div class="row">
                    <?php foreach($builds as $build): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card build-card h-100 <?= isset($_SESSION['usuarioLogado']) && $build['autor_id'] == $_SESSION['usuarioLogado'] ? 'border-warning' : '' ?>">
                                <div class="card-body">
                                    <?php if(isset($_SESSION['usuarioLogado']) && $build['autor_id'] == $_SESSION['usuarioLogado']): ?>
                                        <span class="badge bg-warning text-dark mb-2">Sua Build</span>
                                    <?php endif; ?>
                                    <h5 class="card-title"><?= htmlspecialchars($build['nome']) ?></h5>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <strong><?= htmlspecialchars($build['jogo']) ?></strong><br>
                                            Classe: <?= htmlspecialchars($build['classe']) ?> | Nível: <?= $build['nivel'] ?><br>
                                            Por: <?= htmlspecialchars($build['username']) ?>
                                        </small>
                                    </p>
                                    <p class="card-text"><?= htmlspecialchars(substr($build['descricao'], 0, 100)) ?>...</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="build_detalhes.php?id=<?= $build['id'] ?>" class="btn btn-primary btn-sm">Ver Detalhes</a>
                                        <div>
                                            <?php if(isset($_SESSION['usuarioLogado'])): ?>
                                                <?php
                                                // Verificar se usuário já curtiu
                                                $sql_curtiu = "SELECT id FROM curtidas_builds WHERE usuario_id = :usuario_id AND build_id = :build_id";
                                                $stmt_curtiu = $conexao->prepare($sql_curtiu);
                                                $stmt_curtiu->bindParam(':usuario_id', $_SESSION['usuarioLogado']);
                                                $stmt_curtiu->bindParam(':build_id', $build['id']);
                                                $stmt_curtiu->execute();
                                                $ja_curtiu = $stmt_curtiu->fetch();
                                                ?>
                                                <button class="btn <?= $ja_curtiu ? 'btn-success' : 'btn-outline-primary' ?> btn-sm" id="btn-curtir-<?= $build['id'] ?>" onclick="curtirBuild(<?= $build['id'] ?>)">
                                                    ❤️ <span id="curtidas-<?= $build['id'] ?>"><?= $build['curtidas'] ?></span> <?= $ja_curtiu ? 'Curtido' : 'Curtir' ?>
                                                </button>
                                            <?php else: ?>
                                                <span class="text-muted">❤️ <?= $build['curtidas'] ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function curtirBuild(buildId) {
    $.post('ajax/toggle_curtida.php', {
        build_id: buildId
    }, function(data) {
        if(data.success) {
            const btn = $('#btn-curtir-' + buildId);
            const curtidas = $('#curtidas-' + buildId);
            
            curtidas.text(data.curtidas);
            
            if(data.liked) {
                btn.removeClass('btn-outline-primary').addClass('btn-success');
                btn.html('❤️ <span id="curtidas-' + buildId + '">' + data.curtidas + '</span> Curtido');
            } else {
                btn.removeClass('btn-success').addClass('btn-outline-primary');
                btn.html('❤️ <span id="curtidas-' + buildId + '">' + data.curtidas + '</span> Curtir');
            }
        } else {
            alert('Erro ao curtir build: ' + (data.message || 'Erro desconhecido'));
        }
    }, 'json').fail(function() {
        alert('Erro de conexão');
    });
}

// Busca com AJAX
$('#busca-builds').on('keyup', function() {
    if($(this).val().length > 2 || $(this).val().length == 0) {
        buscarBuilds();
    }
});

$('#filtro-jogo').on('change', function() {
    buscarBuilds();
});
</script>

<?php include 'includes/footer.php'; ?>