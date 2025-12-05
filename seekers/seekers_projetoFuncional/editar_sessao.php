<?php
session_start();

if(!isset($_SESSION['usuarioLogado'])){
    header("Location: login.php");
    exit;
}

require_once 'config/database.php';

if(!isset($_GET['id'])){
    header("Location: dashboard.php");
    exit;
}

$sessao_id = $_GET['id'];
$conexao = conexao();

// Verificar se o usuário é o criador da sessão
$sql = "SELECT * FROM sessoes_jogo WHERE id = :id AND criador_id = :criador_id";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':id', $sessao_id);
$stmt->bindParam(':criador_id', $_SESSION['usuarioLogado']);
$stmt->execute();
$sessao = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$sessao){
    header("Location: dashboard.php");
    exit;
}

// Processar edição
if(isset($_POST['editar'])){
    $jogo = $_POST['jogo'];
    $tipo_sessao = $_POST['tipo_sessao'];
    $plataforma = $_POST['plataforma'];
    $max_participantes = $_POST['max_participantes'];
    $descricao = $_POST['descricao'];
    
    $sql = "UPDATE sessoes_jogo SET jogo = :jogo, tipo_sessao = :tipo_sessao, plataforma = :plataforma, 
            max_participantes = :max_participantes, descricao = :descricao WHERE id = :id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':jogo', $jogo);
    $stmt->bindParam(':tipo_sessao', $tipo_sessao);
    $stmt->bindParam(':plataforma', $plataforma);
    $stmt->bindParam(':max_participantes', $max_participantes);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':id', $sessao_id);
    
    if($stmt->execute()){
        header("Location: sessao_detalhes.php?id=" . $sessao_id);
        exit;
    }
}

// Buscar participantes
$sql = "SELECT u.id, u.username FROM participantes_sessao p 
        JOIN usuarios u ON p.usuario_id = u.id 
        WHERE p.sessao_id = :sessao_id";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':sessao_id', $sessao_id);
$stmt->execute();
$participantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$titulo = "Editar Sessão";
include 'includes/header.php';
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-white mb-0">Editar Sessão</h4>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Jogo</label>
                            <input type="text" class="form-control" name="jogo" value="<?= htmlspecialchars($sessao['jogo']) ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Tipo de Sessão</label>
                            <select class="form-control" name="tipo_sessao" required>
                                <option value="Cooperativo" <?= $sessao['tipo_sessao'] == 'Cooperativo' ? 'selected' : '' ?>>Cooperativo</option>
                                <option value="PvP" <?= $sessao['tipo_sessao'] == 'PvP' ? 'selected' : '' ?>>PvP</option>
                                <option value="Raid" <?= $sessao['tipo_sessao'] == 'Raid' ? 'selected' : '' ?>>Raid</option>
                                <option value="Casual" <?= $sessao['tipo_sessao'] == 'Casual' ? 'selected' : '' ?>>Casual</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Plataforma</label>
                            <select class="form-control" name="plataforma" required>
                                <option value="PC" <?= $sessao['plataforma'] == 'PC' ? 'selected' : '' ?>>PC</option>
                                <option value="PlayStation" <?= $sessao['plataforma'] == 'PlayStation' ? 'selected' : '' ?>>PlayStation</option>
                                <option value="Xbox" <?= $sessao['plataforma'] == 'Xbox' ? 'selected' : '' ?>>Xbox</option>
                                <option value="Nintendo Switch" <?= $sessao['plataforma'] == 'Nintendo Switch' ? 'selected' : '' ?>>Nintendo Switch</option>
                            </select>
                        </div>
                        

                        
                        <div class="mb-3">
                            <label class="form-label">Máximo de Participantes</label>
                            <input type="number" class="form-control" name="max_participantes" min="2" max="10" value="<?= $sessao['max_participantes'] ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Descrição</label>
                            <textarea class="form-control" name="descricao" rows="4"><?= htmlspecialchars($sessao['descricao']) ?></textarea>
                        </div>
                        
                        <button type="submit" name="editar" class="btn btn-primary">Salvar Alterações</button>
                        <button type="button" class="btn btn-danger" onclick="apagarSessao()">Apagar Sessão</button>
                        <a href="sessao_detalhes.php?id=<?= $sessao_id ?>" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
            
            <?php if(!empty($participantes)): ?>
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="text-white mb-0">Gerenciar Participantes</h5>
                </div>
                <div class="card-body">
                    <?php foreach($participantes as $participante): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-dark rounded">
                            <span><?= htmlspecialchars($participante['username']) ?></span>
                            <button class="btn btn-sm btn-danger" onclick="removerParticipante(<?= $participante['id'] ?>)">
                                Remover
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function removerParticipante(usuarioId) {
    if(confirm('Tem certeza que deseja remover este participante?')) {
        $.post('ajax/remover_participante.php', {
            sessao_id: <?= $sessao_id ?>,
            usuario_id: usuarioId
        }, function(data) {
            if(data.success) {
                location.reload();
            } else {
                alert('Erro ao remover participante');
            }
        }, 'json');
    }
}


function apagarSessao() {
    if(confirm('Tem certeza que deseja apagar esta sessão? Esta ação não pode ser desfeita.')) {
        if(confirm('CONFIRMAÇÃO FINAL: Apagar sessão permanentemente?')) {
            window.location.href = 'ajax/apagar_sessao.php?id=<?= $sessao_id ?>';
        }
    }
}
</script>

<?php include 'includes/footer.php'; ?>