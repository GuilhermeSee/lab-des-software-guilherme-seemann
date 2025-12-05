<?php
session_start();
require_once 'config/database.php';

$mensagem = "";

if(isset($_POST["username"])){
    $username = $_POST["username"];
    $senha = $_POST["password"];

    $conexao = conexao();
    $sql = "SELECT * FROM usuarios WHERE username = :username";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($usuario && password_verify($senha, $usuario['senha'])){
        $_SESSION['usuarioLogado'] = $usuario['id'];
        $_SESSION['username'] = $usuario['username'];
        
        // Atualizar último acesso
        $sql = "UPDATE usuarios SET ultimo_acesso = NOW() WHERE id = :id";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':id', $usuario['id']);
        $stmt->execute();
        
        header("Location: dashboard.php");
        exit;
    } else {
        $mensagem = 'Username ou senha incorretos';
    }
}

$titulo = "Login";
include 'includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center text-white mb-0">Login</h4>
                </div>
                <div class="card-body">
                    <?php if(!empty($mensagem)): ?>
                        <div class="alert alert-danger">
                            <?= $mensagem ?>
                        </div>
                    <?php endif; ?>

                    <form action="login.php" method="post" id="loginForm">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Senha:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Entrar</button>
                    </form>

                    <div class="text-center mt-3">
                        <p>Não tem uma conta? <a href="cadastro.php" class="text-warning">Cadastre-se aqui</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    if (!validarFormulario('loginForm')) {
        e.preventDefault();
        alert('Por favor, preencha todos os campos obrigatórios.');
    }
});
</script>

<?php include 'includes/footer.php'; ?>