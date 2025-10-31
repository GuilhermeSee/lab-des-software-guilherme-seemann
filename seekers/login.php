<?php
require_once 'includes/autoload.php';

$mensagem = "";

if(isset($_POST["username"])){
    $username = $_POST["username"];
    $senha = $_POST["password"];

    $usuario = new Usuario();
    
    if($usuario->buscarPorUsername($username)){
        if($usuario->verificarSenha($senha)){
            session_start();
            $_SESSION['usuarioLogado'] = $usuario->getId();
            $_SESSION['username'] = $usuario->getUsername();
            
            $usuario->atualizarUltimoAcesso();
            
            header("Location: dashboard.php");
            exit;
        } else {
            $mensagem = 'Senha incorreta';
        }
    } else {
        $mensagem = 'Usuário não encontrado';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seekers - Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <h1>SEEKERS</h1>
                <p>Plataforma de Conexão para Jogadores Souls-like</p>
            </div>

            <?php if(!empty($mensagem)): ?>
                <div class="error-message">
                    <?= $mensagem ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Senha:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn-login">Entrar</button>
            </form>

            <div class="register-link">
                <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se aqui</a></p>
            </div>
        </div>
    </div>
</body>
</html>