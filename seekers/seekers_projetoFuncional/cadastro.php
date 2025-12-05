<?php
session_start();
require_once 'config/database.php';

$mensagem = "";
$sucesso = false;

if(isset($_POST["username"])){
    $username = $_POST["username"];
    $email = $_POST["email"];
    $senha = $_POST["password"];
    $confirmarSenha = $_POST["confirm_password"];

    // Validação backend
    if(empty($username) || empty($email) || empty($senha)){
        $mensagem = "Todos os campos são obrigatórios";
    } elseif($senha !== $confirmarSenha){
        $mensagem = "As senhas não coincidem";
    } elseif(strlen($senha) < 6){
        $mensagem = "A senha deve ter pelo menos 6 caracteres";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $mensagem = "Email inválido";
    } else {
        $conexao = conexao();
        
        // Verificar se username já existe
        $sql = "SELECT id FROM usuarios WHERE username = :username";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        if($stmt->fetch()){
            $mensagem = "Username já existe";
        } else {
            // Inserir usuário
            $sql = "INSERT INTO usuarios (username, email, senha, plataformas, jogos_preferidos, usa_mods, bio) 
                    VALUES (:username, :email, :senha, '[]', '[]', 0, '')";
            $stmt = $conexao->prepare($sql);
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senha_hash);

            if($stmt->execute()){
                $sucesso = true;
                $mensagem = "Cadastro realizado com sucesso! Faça login.";
            } else {
                $mensagem = "Erro ao cadastrar usuário";
            }
        }
    }
}

$titulo = "Cadastro";
include 'includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center text-white mb-0">Cadastro</h4>
                </div>
                <div class="card-body">
                    <?php if(!empty($mensagem)): ?>
                        <div class="alert <?= $sucesso ? 'alert-success' : 'alert-danger' ?>">
                            <?= $mensagem ?>
                        </div>
                    <?php endif; ?>

                    <?php if(!$sucesso): ?>
                    <form action="cadastro.php" method="post" id="cadastroForm">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Senha:</label>
                            <input type="password" class="form-control" id="password" name="password" required minlength="6">
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirmar Senha:</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="aceitar_termos" name="aceitar_termos" required>
                                <label class="form-check-label" for="aceitar_termos">
                                    Eu li e aceito os <a href="#" data-bs-toggle="modal" data-bs-target="#termosModal" class="text-warning">Termos de Serviço</a>
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
                    </form>
                    <?php endif; ?>

                    <div class="text-center mt-3">
                        <p>Já tem uma conta? <a href="login.php" class="text-warning">Faça login aqui</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal dos Termos de Serviço -->
<div class="modal fade" id="termosModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title text-warning">Termos de Serviço</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                <h6 class="text-warning">1. Aceitação dos Termos</h6>
                <p>Ao acessar e usar a plataforma Seekers, você concorda em cumprir e estar vinculado aos seguintes termos e condições de uso.</p>

                <h6 class="text-warning">2. Descrição do Serviço</h6>
                <p>Seekers é uma plataforma online que conecta jogadores de jogos souls-like, permitindo o compartilhamento de builds, organização de sessões cooperativas e interação da comunidade.</p>

                <h6 class="text-warning">3. Registro de Conta</h6>
                <p>Para usar certas funcionalidades da plataforma, você deve criar uma conta fornecendo informações precisas e atualizadas. Você é responsável por manter a confidencialidade de sua senha.</p>

                <h6 class="text-warning">4. Conduta do Usuário</h6>
                <p>Você concorda em não usar a plataforma para:</p>
                <ul>
                    <li>Publicar conteúdo ofensivo, difamatório ou inadequado</li>
                    <li>Assediar ou intimidar outros usuários</li>
                    <li>Violar direitos autorais ou propriedade intelectual</li>
                    <li>Distribuir malware ou conteúdo malicioso</li>
                    <li>Usar a plataforma para atividades ilegais</li>
                </ul>

                <h6 class="text-warning">5. Conteúdo do Usuário</h6>
                <p>Você mantém os direitos sobre o conteúdo que publica, mas concede à Seekers uma licença para usar, modificar e distribuir esse conteúdo na plataforma.</p>

                <h6 class="text-warning">6. Privacidade</h6>
                <p>Sua privacidade é importante para nós. Coletamos apenas as informações necessárias para o funcionamento da plataforma e não compartilhamos seus dados com terceiros.</p>

                <h6 class="text-warning">7. Modificações dos Termos</h6>
                <p>Reservamos o direito de modificar estes termos a qualquer momento. As alterações entrarão em vigor imediatamente após a publicação.</p>

                <h6 class="text-warning">8. Limitação de Responsabilidade</h6>
                <p>A Seekers não se responsabiliza por danos diretos, indiretos, incidentais ou consequenciais decorrentes do uso da plataforma.</p>

                <h6 class="text-warning">9. Rescisão</h6>
                <p>Podemos suspender ou encerrar sua conta a qualquer momento, por qualquer motivo, incluindo violação destes termos.</p>

                <h6 class="text-warning">10. Contato</h6>
                <p>Para questões sobre estes termos, entre em contato conosco através da página de contato.</p>

                <hr>
                <p class="text-muted"><small>Última atualização: Janeiro de 2025</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('cadastroForm')?.addEventListener('submit', function(e) {
    const senha = document.getElementById('password').value;
    const confirmarSenha = document.getElementById('confirm_password').value;
    
    if (!validarFormulario('cadastroForm')) {
        e.preventDefault();
        alert('Por favor, preencha todos os campos obrigatórios.');
        return;
    }
    
    if (!document.getElementById('aceitar_termos').checked) {
        e.preventDefault();
        alert('Você deve aceitar os Termos de Serviço para se cadastrar.');
        return;
    }
    
    if (senha !== confirmarSenha) {
        e.preventDefault();
        alert('As senhas não coincidem.');
        return;
    }
    
    if (senha.length < 6) {
        e.preventDefault();
        alert('A senha deve ter pelo menos 6 caracteres.');
        return;
    }
});
</script>

<?php include 'includes/footer.php'; ?>