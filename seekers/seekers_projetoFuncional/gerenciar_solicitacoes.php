<?php
session_start();

if(!isset($_SESSION['usuarioLogado'])){
    header("Location: login.php");
    exit;
}

require_once 'config/database.php';

if(!isset($_GET['sessao_id'])){
    header("Location: dashboard.php");
    exit;
}

$sessao_id = $_GET['sessao_id'];
$conexao = conexao();

// Verificar se a sessão pertence ao usuário
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

$mensagem = "";

// Processar ações
if(isset($_POST['acao'])){
    $solicitacao_id = $_POST['solicitacao_id'];
    $acao = $_POST['acao'];
    
    if($acao == 'aceitar'){
        // Aceitar solicitação
        $sql = "UPDATE solicitacoes_participacao SET status = 'aceita', data_resposta = NOW() WHERE id = :id";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':id', $solicitacao_id);
        $stmt->execute();
        
        // Adicionar à tabela participantes_sessao
        $sql = "SELECT solicitante_id FROM solicitacoes_participacao WHERE id = :id";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':id', $solicitacao_id);
        $stmt->execute();
        $solicitacao = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $sql = "INSERT IGNORE INTO participantes_sessao (sessao_id, usuario_id) VALUES (:sessao_id, :usuario_id)";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':sessao_id', $sessao_id);
        $stmt->bindParam(':usuario_id', $solicitacao['solicitante_id']);
        $stmt->execute();
        
        // Marcar notificação como lida
        $sql = "UPDATE notificacoes SET lida = 1 WHERE usuario_id = :usuario_id AND tipo = 'solicitacao_participacao' AND JSON_EXTRACT(dados_extras, '$.sessao_id') = :sessao_id";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':usuario_id', $_SESSION['usuarioLogado']);
        $stmt->bindParam(':sessao_id', $sessao_id);
        $stmt->execute();
        
        $mensagem = "Solicitação aceita com sucesso!";
        
    } elseif($acao == 'recusar'){
        // Recusar solicitação
        $sql = "DELETE FROM solicitacoes_participacao WHERE id = :id";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':id', $solicitacao_id);
        $stmt->execute();
        
        // Marcar notificação como lida
        $sql = "UPDATE notificacoes SET lida = 1 WHERE usuario_id = :usuario_id AND tipo = 'solicitacao_participacao' AND JSON_EXTRACT(dados_extras, '$.sessao_id') = :sessao_id";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':usuario_id', $_SESSION['usuarioLogado']);
        $stmt->bindParam(':sessao_id', $sessao_id);
        $stmt->execute();
        
        $mensagem = "Solicitação recusada.";
    }
}

// Buscar solicitações pendentes
$sql = "SELECT s.*, u.username FROM solicitacoes_participacao s 
        JOIN usuarios u ON s.solicitante_id = u.id 
        WHERE s.sessao_id = :sessao_id AND s.status = 'pendente' 
        ORDER BY s.data_solicitacao DESC";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':sessao_id', $sessao_id);
$stmt->execute();
$solicitacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$titulo = "Gerenciar Solicitações";
include 'includes/header.php';
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-white mb-0">Solicitações para: <?= htmlspecialchars($sessao['jogo']) ?></h4>
                    <small class="text-muted"><?= htmlspecialchars($sessao['tipo_sessao']) ?> - <?= htmlspecialchars($sessao['plataforma']) ?></small>
                </div>
                <div class="card-body">
                    <?php if(!empty($mensagem)): ?>
                        <div class="alert alert-success">
                            <?= $mensagem ?>
                        </div>
                    <?php endif; ?>

                    <?php if(empty($solicitacoes)): ?>
                        <div class="alert alert-info">
                            Não há solicitações pendentes para esta sessão.
                        </div>
                    <?php else: ?>
                        <?php foreach($solicitacoes as $solicitacao): ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h6 class="mb-1"><?= htmlspecialchars($solicitacao['username']) ?></h6>
                                            <small class="text-muted">
                                                Solicitou participação em <?= date('d/m/Y H:i', strtotime($solicitacao['data_solicitacao'])) ?>
                                            </small>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <form method="post" style="display: inline;">
                                                <input type="hidden" name="solicitacao_id" value="<?= $solicitacao['id'] ?>">
                                                <button type="submit" name="acao" value="aceitar" class="btn btn-success btn-sm">
                                                    Aceitar
                                                </button>
                                                <button type="submit" name="acao" value="recusar" class="btn btn-danger btn-sm">
                                                    Recusar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <div class="mt-4">
                        <a href="sessao_detalhes.php?id=<?= $sessao_id ?>" class="btn btn-primary">Ver Sessão</a>
                        <a href="dashboard.php" class="btn btn-secondary">Voltar ao Perfil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>