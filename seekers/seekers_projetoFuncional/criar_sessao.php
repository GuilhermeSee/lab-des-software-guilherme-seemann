<?php
session_start();

if(!isset($_SESSION['usuarioLogado'])){
    header("Location: login.php");
    exit;
}

require_once 'config/database.php';

$mensagem = "";
$sucesso = false;

if(isset($_POST["jogo"])){
    $jogo = $_POST["jogo"];
    $plataforma = $_POST["plataforma"];
    $tipo_sessao = $_POST["tipo_sessao"];
    $max_participantes = $_POST["max_participantes"];
    $usa_mods = isset($_POST["usa_mods"]) ? 1 : 0;
    $descricao = $_POST["descricao"];

    if(empty($jogo) || empty($plataforma) || empty($tipo_sessao) || empty($max_participantes)){
        $mensagem = "Campos obrigatórios não preenchidos";
    } else {
        $conexao = conexao();
        $sql = "INSERT INTO sessoes_jogo (jogo, plataforma, tipo_sessao, max_participantes, usa_mods, descricao, criador_id) 
                VALUES (:jogo, :plataforma, :tipo_sessao, :max_participantes, :usa_mods, :descricao, :criador_id)";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':jogo', $jogo);
        $stmt->bindParam(':plataforma', $plataforma);
        $stmt->bindParam(':tipo_sessao', $tipo_sessao);
        $stmt->bindParam(':max_participantes', $max_participantes);
        $stmt->bindParam(':usa_mods', $usa_mods);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':criador_id', $_SESSION['usuarioLogado']);

        if($stmt->execute()){
            $sucesso = true;
            $mensagem = "Sessão criada com sucesso!";
        } else {
            $mensagem = "Erro ao criar sessão";
        }
    }
}

$titulo = "Criar Sessão";
include 'includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-white mb-0">Criar Nova Sessão de Jogo</h4>
                </div>
                <div class="card-body">
                    <?php if(!empty($mensagem)): ?>
                        <div class="alert <?= $sucesso ? 'alert-success' : 'alert-danger' ?>">
                            <?= $mensagem ?>
                            <?php if($sucesso): ?>
                                <br><a href="sessoes.php" class="text-white fw-bold">Ver todas as sessões</a> | 
                                <a href="dashboard.php" class="text-white fw-bold">Voltar ao Perfil</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if(!$sucesso): ?>
                    <form action="criar_sessao.php" method="post" id="sessaoForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jogo" class="form-label">Jogo:</label>
                                <select class="form-control" id="jogo" name="jogo" required>
                                    <option value="">Selecione o jogo</option>
                                    <option value="Dark Souls">Dark Souls</option>
                                    <option value="Dark Souls 2">Dark Souls 2</option>
                                    <option value="Dark Souls 3">Dark Souls 3</option>
                                    <option value="Elden Ring">Elden Ring</option>
                                    <option value="Bloodborne">Bloodborne</option>
                                    <option value="Sekiro">Sekiro</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="plataforma" class="form-label">Plataforma:</label>
                                <select class="form-control" id="plataforma" name="plataforma" required>
                                    <option value="">Selecione a plataforma</option>
                                    <option value="PC">PC</option>
                                    <option value="PlayStation 4">PlayStation 4</option>
                                    <option value="PlayStation 5">PlayStation 5</option>
                                    <option value="Xbox One">Xbox One</option>
                                    <option value="Xbox Series">Xbox Series X/S</option>
                                    <option value="Nintendo Switch">Nintendo Switch</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tipo_sessao" class="form-label">Tipo de Sessão:</label>
                                <select class="form-control" id="tipo_sessao" name="tipo_sessao" required>
                                    <option value="">Selecione o tipo</option>
                                    <option value="Cooperativo">Cooperativo</option>
                                    <option value="PvP">PvP</option>
                                    <option value="Boss Fight">Boss Fight</option>
                                    <option value="Exploração">Exploração</option>
                                    <option value="Speedrun">Speedrun</option>
                                    <option value="Ajuda Geral">Ajuda Geral</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="max_participantes" class="form-label">Máximo de Participantes:</label>
                                <select class="form-control" id="max_participantes" name="max_participantes" required>
                                    <option value="2">2 Jogadores</option>
                                    <option value="3">3 Jogadores</option>
                                    <option value="4">4 Jogadores</option>
                                    <option value="6">6 Jogadores</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="usa_mods" name="usa_mods">
                                <label class="form-check-label" for="usa_mods">
                                    Permitir Mods
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição:</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="4" placeholder="Descreva o objetivo da sessão, requisitos, etc..." required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Criar Sessão</button>
                        <a href="sessoes.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('sessaoForm')?.addEventListener('submit', function(e) {
    if (!validarFormulario('sessaoForm')) {
        e.preventDefault();
        alert('Por favor, preencha todos os campos obrigatórios.');
    }
});
</script>

<?php include 'includes/footer.php'; ?>