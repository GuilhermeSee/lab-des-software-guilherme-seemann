<?php
session_start();

if(!isset($_SESSION['usuarioLogado'])){
    header("Location: login.php");
    exit;
}

require_once 'config/database.php';

$mensagem = "";
$sucesso = false;

// Atualizar perfil
if(isset($_POST["username"])){
    $username = $_POST["username"];
    $email = $_POST["email"];
    $bio = $_POST["bio"];
    $plataformas = isset($_POST["plataformas"]) ? $_POST["plataformas"] : [];
    $jogos_preferidos = isset($_POST["jogos_preferidos"]) ? $_POST["jogos_preferidos"] : [];
    $usa_mods = isset($_POST["usa_mods"]) ? 1 : 0;

    if(empty($username) || empty($email)){
        $mensagem = "Username e email são obrigatórios";
    } else {
        $conexao = conexao();
        
        // Verificar se username já existe (exceto o próprio usuário)
        $sql = "SELECT id FROM usuarios WHERE username = :username AND id != :id";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':id', $_SESSION['usuarioLogado']);
        $stmt->execute();
        
        if($stmt->fetch()){
            $mensagem = "Username já existe";
        } else {
            $plataformas_json = json_encode($plataformas);
            $jogos_json = json_encode($jogos_preferidos);
            
            $sql = "UPDATE usuarios SET username = :username, email = :email, bio = :bio, 
                    plataformas = :plataformas, jogos_preferidos = :jogos_preferidos, usa_mods = :usa_mods 
                    WHERE id = :id";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':bio', $bio);
            $stmt->bindParam(':plataformas', $plataformas_json);
            $stmt->bindParam(':jogos_preferidos', $jogos_json);
            $stmt->bindParam(':usa_mods', $usa_mods);
            $stmt->bindParam(':id', $_SESSION['usuarioLogado']);

            if($stmt->execute()){
                $sucesso = true;
                $_SESSION['username'] = $username;
                header("Location: dashboard.php");
                exit;
            } else {
                $mensagem = "Erro ao atualizar perfil";
            }
        }
    }
}

// Buscar dados do usuário
$conexao = conexao();
$sql = "SELECT * FROM usuarios WHERE id = :id";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':id', $_SESSION['usuarioLogado']);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

$plataformas_decode = json_decode($usuario['plataformas'], true);
$plataformas_usuario = $plataformas_decode ?: [];
$jogos_decode = json_decode($usuario['jogos_preferidos'], true);
$jogos_usuario = $jogos_decode ?: [];

$titulo = "Editar Perfil";
include 'includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-white mb-0">Editar Perfil</h4>
                </div>
                <div class="card-body">
                    <?php if(!empty($mensagem)): ?>
                        <div class="alert alert-danger">
                            <?= $mensagem ?>
                        </div>
                    <?php endif; ?>

                    <form action="perfil.php" method="post" id="perfilForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username:</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($usuario['username']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio:</label>
                            <textarea class="form-control" id="bio" name="bio" rows="3"><?= htmlspecialchars($usuario['bio']) ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Plataformas:</label>
                                <div>
                                    <?php $plataformas_disponiveis = ['PC', 'PlayStation 4', 'PlayStation 5', 'Xbox One', 'Xbox Series', 'Nintendo Switch']; ?>
                                    <?php foreach($plataformas_disponiveis as $plataforma): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="plataformas[]" value="<?= $plataforma ?>" 
                                                   <?= in_array($plataforma, $plataformas_usuario) ? 'checked' : '' ?>>
                                            <label class="form-check-label"><?= $plataforma ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jogos Preferidos:</label>
                                <div>
                                    <?php $jogos_disponiveis = ['Dark Souls', 'Dark Souls 2', 'Dark Souls 3', 'Elden Ring', 'Bloodborne', 'Sekiro']; ?>
                                    <?php foreach($jogos_disponiveis as $jogo): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="jogos_preferidos[]" value="<?= $jogo ?>" 
                                                   <?= in_array($jogo, $jogos_usuario) ? 'checked' : '' ?>>
                                            <label class="form-check-label"><?= $jogo ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="usa_mods" name="usa_mods" <?= $usuario['usa_mods'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="usa_mods">
                                    Uso mods nos jogos
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>