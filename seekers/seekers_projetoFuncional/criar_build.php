<?php
session_start();

if(!isset($_SESSION['usuarioLogado'])){
    header("Location: login.php");
    exit;
}

require_once 'config/database.php';

$mensagem = "";
$sucesso = false;

if(isset($_POST["nome"])){
    $nome = $_POST["nome"];
    $jogo = $_POST["jogo"];
    $classe = $_POST["classe"];
    $descricao = $_POST["descricao"];
    
    // Atributos
    $atributos = [
        'vigor' => $_POST['vigor'],
        'forca' => $_POST['forca'],
        'destreza' => $_POST['destreza'],
        'inteligencia' => $_POST['inteligencia'],
        'fe' => $_POST['fe']
    ];
    
    // Calcular nível automaticamente
    $nivel_calculado = 0;
    foreach($atributos as $valor) {
        if($valor > 10) {
            $nivel_calculado += ($valor - 10);
        } else {
            $nivel_calculado -= (10 - $valor);
        }
    }
    $nivel = max(1, $nivel_calculado + 50); // Nível base + ajustes
    
    // Equipamentos
    $equipamentos = [
        'arma_principal' => $_POST['arma_principal'],
        'arma_secundaria' => $_POST['arma_secundaria'],
        'armadura' => $_POST['armadura'],
        'anel1' => $_POST['anel1'],
        'anel2' => $_POST['anel2']
    ];

    if(empty($nome) || empty($jogo) || empty($classe)){
        $mensagem = "Campos obrigatórios não preenchidos";
    } else {
        $conexao = conexao();
        $atributos_json = json_encode($atributos);
        $equipamentos_json = json_encode($equipamentos);
        
        $sql = "INSERT INTO builds (nome, jogo, classe, nivel, atributos, equipamentos, descricao, autor_id) 
                VALUES (:nome, :jogo, :classe, :nivel, :atributos, :equipamentos, :descricao, :autor_id)";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':jogo', $jogo);
        $stmt->bindParam(':classe', $classe);
        $stmt->bindParam(':nivel', $nivel);
        $stmt->bindParam(':atributos', $atributos_json);
        $stmt->bindParam(':equipamentos', $equipamentos_json);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':autor_id', $_SESSION['usuarioLogado']);

        if($stmt->execute()){
            $sucesso = true;
            $mensagem = "Build criada com sucesso!";
        } else {
            $mensagem = "Erro ao criar build";
        }
    }
}

$titulo = "Criar Build";
include 'includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-white mb-0">Criar Nova Build</h4>
                </div>
                <div class="card-body">
                    <?php if(!empty($mensagem)): ?>
                        <div class="alert <?= $sucesso ? 'alert-success' : 'alert-danger' ?>">
                            <?= $mensagem ?>
                            <?php if($sucesso): ?>
                                <br><a href="builds.php" class="text-white fw-bold">Ver todas as builds</a> | 
                                <a href="dashboard.php" class="text-white fw-bold">Voltar ao Perfil</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if(!$sucesso): ?>
                    <form action="criar_build.php" method="post" id="buildForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nome" class="form-label">Nome da Build:</label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>
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
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="classe" class="form-label">Classe Inicial:</label>
                                <input type="text" class="form-control" id="classe" name="classe" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nível:</label>
                                <p class="form-control-static text-warning" id="nivel-calculado">Calculado automaticamente</p>
                                <small class="text-muted">O nível é calculado com base nos atributos</small>
                            </div>
                        </div>

                        <h6 class="text-warning mt-4">Atributos</h6>
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <label for="vigor" class="form-label">Vigor:</label>
                                <input type="number" class="form-control" id="vigor" name="vigor" min="1" max="99" value="10">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="forca" class="form-label">Força:</label>
                                <input type="number" class="form-control" id="forca" name="forca" min="1" max="99" value="10">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="destreza" class="form-label">Destreza:</label>
                                <input type="number" class="form-control" id="destreza" name="destreza" min="1" max="99" value="10">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="inteligencia" class="form-label">Inteligência:</label>
                                <input type="number" class="form-control" id="inteligencia" name="inteligencia" min="1" max="99" value="10">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="fe" class="form-label">Fé:</label>
                                <input type="number" class="form-control" id="fe" name="fe" min="1" max="99" value="10">
                            </div>
                        </div>

                        <h6 class="text-warning mt-4">Equipamentos</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="arma_principal" class="form-label">Arma Principal:</label>
                                <input type="text" class="form-control" id="arma_principal" name="arma_principal">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="arma_secundaria" class="form-label">Arma Secundária:</label>
                                <input type="text" class="form-control" id="arma_secundaria" name="arma_secundaria">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="armadura" class="form-label">Armadura:</label>
                                <input type="text" class="form-control" id="armadura" name="armadura">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="anel1" class="form-label">Anel 1:</label>
                                <input type="text" class="form-control" id="anel1" name="anel1">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="anel2" class="form-label">Anel 2:</label>
                                <input type="text" class="form-control" id="anel2" name="anel2">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição:</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="4" placeholder="Descreva sua build, estratégias, pontos fortes..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Criar Build</button>
                        <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('buildForm')?.addEventListener('submit', function(e) {
    if (!validarFormulario('buildForm')) {
        e.preventDefault();
        alert('Por favor, preencha todos os campos obrigatórios.');
    }
});
</script>

<?php include 'includes/footer.php'; ?>