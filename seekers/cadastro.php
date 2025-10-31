<?php
require_once 'includes/autoload.php';

$mensagem = "";
$sucesso = false;

if(isset($_POST["username"])){
    $username = $_POST["username"];
    $email = $_POST["email"];
    $senha = $_POST["password"];
    $confirmarSenha = $_POST["confirm_password"];

    if($senha !== $confirmarSenha){
        $mensagem = "As senhas não coincidem";
    } else {
        $usuario = new Usuario();
        
        if($usuario->buscarPorUsername($username)){
            $mensagem = "Username já existe";
        } else {
            $usuario->setUsername($username);
            $usuario->setEmail($email);
            $usuario->setSenha(password_hash($senha, PASSWORD_DEFAULT));
            $usuario->setPlataformas('[]');
            $usuario->setJogosPreferidos('[]');
            $usuario->setUsaMods(false);
            $usuario->setBio('');

            if($usuario->inserir()){
                $sucesso = true;
                $mensagem = "Cadastro realizado com sucesso! Faça login.";
            } else {
                $mensagem = "Erro ao cadastrar usuário";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seekers - Cadastro</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <h1>SEEKERS</h1>
                <p>Junte-se à comunidade Souls-like</p>
            </div>

            <?php if(!empty($mensagem)): ?>
                <div class="<?= $sucesso ? 'success-message' : 'error-message' ?>">
                    <?= $mensagem ?>
                </div>
            <?php endif; ?>

            <?php if(!$sucesso): ?>
            <form action="cadastro.php" method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Senha:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirmar Senha:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" class="btn-login">Cadastrar</button>
            </form>
            <?php endif; ?>

            <div class="register-link">
                <p>Já tem uma conta? <a href="login.php">Faça login aqui</a></p>
            </div>
        </div>
    </div>
</body>
</html>