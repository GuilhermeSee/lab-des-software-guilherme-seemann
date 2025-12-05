<?php
session_start();
require_once 'config/database.php';

if(!isset($_GET['id'])){
    header("Location: builds.php");
    exit;
}

$build_id = $_GET['id'];
$conexao = conexao();

$sql = "SELECT b.*, u.username FROM builds b 
        JOIN usuarios u ON b.autor_id = u.id 
        WHERE b.id = :id AND b.publico = 1";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':id', $build_id);
$stmt->execute();
$build = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$build){
    header("Location: builds.php");
    exit;
}

$atributos = json_decode($build['atributos'], true);
$equipamentos = json_decode($build['equipamentos'], true);

$titulo = $build['nome'] . " - Build";
include 'includes/header.php';
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-white mb-0"><?= htmlspecialchars($build['nome']) ?></h4>
                    <small class="text-muted">
                        Por <?= htmlspecialchars($build['username']) ?> em <?= date('d/m/Y', strtotime($build['criado_em'])) ?>
                    </small>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-warning">Informações Gerais</h6>
                            <p><strong>Jogo:</strong> <?= htmlspecialchars($build['jogo']) ?></p>
                            <p><strong>Classe:</strong> <?= htmlspecialchars($build['classe']) ?></p>
                            <p><strong>Nível:</strong> <?= $build['nivel'] ?></p>
                            <p><strong>Curtidas:</strong> ❤️ <?= $build['curtidas'] ?></p>
                        </div>
                        <div class="col-md-6">
                            <?php if(isset($_SESSION['usuarioLogado'])): ?>
                                <?php
                                $sql_curtiu = "SELECT id FROM curtidas_builds WHERE usuario_id = :usuario_id AND build_id = :build_id";
                                $stmt_curtiu = $conexao->prepare($sql_curtiu);
                                $stmt_curtiu->bindParam(':usuario_id', $_SESSION['usuarioLogado']);
                                $stmt_curtiu->bindParam(':build_id', $build['id']);
                                $stmt_curtiu->execute();
                                $ja_curtiu = $stmt_curtiu->fetch();
                                ?>
                                <button class="btn <?= $ja_curtiu ? 'btn-success' : 'btn-outline-primary' ?> mb-2" id="btn-curtir-<?= $build['id'] ?>" onclick="curtirBuild(<?= $build['id'] ?>)">
                                    ❤️ <span id="curtidas-<?= $build['id'] ?>"><?= $build['curtidas'] ?></span> <?= $ja_curtiu ? 'Curtido' : 'Curtir' ?>
                                </button><br>
                                
                                <?php if($_SESSION['usuarioLogado'] == $build['autor_id']): ?>
                                    <a href="editar_build.php?id=<?= $build['id'] ?>" class="btn btn-warning mb-2">
                                        Editar Build
                                    </a><br>
                                <?php endif; ?>
                                
                                <?php
                                $sql_fav = "SELECT id FROM builds_favoritas WHERE usuario_id = :usuario_id AND build_id = :build_id";
                                $stmt_fav = $conexao->prepare($sql_fav);
                                $stmt_fav->bindParam(':usuario_id', $_SESSION['usuarioLogado']);
                                $stmt_fav->bindParam(':build_id', $build['id']);
                                $stmt_fav->execute();
                                $ja_favoritou = $stmt_fav->fetch();
                                ?>
                                <button class="btn <?= $ja_favoritou ? 'btn-warning' : 'btn-success' ?>" onclick="toggleFavorito('build', <?= $build['id'] ?>)">
                                    ⭐ <?= $ja_favoritou ? 'Remover dos Favoritos' : 'Adicionar aos Favoritos' ?>
                                </button>
                            <?php else: ?>
                                <p class="text-muted">Faça login para curtir e usar esta build</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <h6 class="text-warning">Descrição</h6>
                    <p><?= nl2br(htmlspecialchars($build['descricao'])) ?></p>

                    <?php if($atributos): ?>
                        <h6 class="text-warning">Atributos</h6>
                        <div class="row">
                            <?php foreach($atributos as $atributo => $valor): ?>
                                <div class="col-md-3 mb-2">
                                    <div class="card bg-secondary">
                                        <div class="card-body text-center py-2">
                                            <strong><?= ucfirst($atributo) ?></strong><br>
                                            <span class="h5"><?= $valor ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if($equipamentos): ?>
                        <h6 class="text-warning mt-4">Equipamentos</h6>
                        <div class="row">
                            <?php foreach($equipamentos as $tipo => $item): ?>
                                <div class="col-md-6 mb-2">
                                    <div class="card bg-dark">
                                        <div class="card-body py-2">
                                            <strong><?= ucfirst($tipo) ?>:</strong> <?= htmlspecialchars($item) ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="text-white mb-0">Builds Relacionadas</h6>
                </div>
                <div class="card-body">
                    <?php
                    $sql = "SELECT id, nome, jogo, curtidas FROM builds 
                            WHERE jogo = :jogo AND id != :id AND publico = 1 
                            ORDER BY curtidas DESC LIMIT 5";
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':jogo', $build['jogo']);
                    $stmt->bindParam(':id', $build_id);
                    $stmt->execute();
                    $builds_relacionadas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>

                    <?php foreach($builds_relacionadas as $build_rel): ?>
                        <div class="mb-3">
                            <a href="build_detalhes.php?id=<?= $build_rel['id'] ?>" class="text-decoration-none">
                                <div class="card build-relacionada">
                                    <div class="card-body py-2">
                                        <h6 class="mb-1 text-warning"><?= htmlspecialchars($build_rel['nome']) ?></h6>
                                        <small class="text-muted">
                                            <?= htmlspecialchars($build_rel['jogo']) ?> | ❤️ <?= $build_rel['curtidas'] ?>
                                        </small>
                                    </div>
                                </div>
                            </a>
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

function toggleFavorito(tipo, itemId) {
    $.post('ajax/toggle_favorito.php', {
        tipo: tipo,
        item_id: itemId
    }, function(data) {
        if(data.success) {
            location.reload();
        } else {
            alert('Erro ao alterar favorito');
        }
    }, 'json');
}
</script>

<?php include 'includes/footer.php'; ?>