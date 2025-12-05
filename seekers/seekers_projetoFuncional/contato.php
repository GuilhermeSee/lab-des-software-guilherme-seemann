<?php
session_start();
require_once 'config/database.php';
require_once 'config/env.php';
require_once 'lib/PHPMailer/PHPMailer.php';
require_once 'lib/PHPMailer/SMTP.php';
require_once 'lib/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Função para enviar email com PHPMailer
function enviarEmail($nome, $email, $assunto, $mensagem) {
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USER'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
        
        $mail->setFrom($_ENV['MAIL_USER'], 'Seekers Platform');
        $mail->addAddress($_ENV['MAIL_USER']);
        $mail->addReplyTo($email, $nome);
        
        $mail->Subject = "[SEEKERS] Novo Contato: " . $assunto;
        $mail->Body = "Nome: $nome\n";
        $mail->Body .= "Email: $email\n";
        $mail->Body .= "Assunto: $assunto\n\n";
        $mail->Body .= "Mensagem:\n$mensagem\n\n";
        $mail->Body .= "---\nEnviado pelo sistema Seekers";
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erro ao enviar email: " . $mail->ErrorInfo);
        return false;
    }
}

$mensagem = "";
$sucesso = false;

if(isset($_POST["nome"])){
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $assunto = $_POST["assunto"];
    $mensagem_contato = $_POST["mensagem"];

    // Validação backend
    if(empty($nome) || empty($email) || empty($assunto) || empty($mensagem_contato)){
        $mensagem = "Todos os campos são obrigatórios";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $mensagem = "Email inválido";
    } else {
        $conexao = conexao();
        $sql = "INSERT INTO contatos (nome, email, assunto, mensagem) VALUES (:nome, :email, :assunto, :mensagem)";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':assunto', $assunto);
        $stmt->bindParam(':mensagem', $mensagem_contato);

        if($stmt->execute()){
            // Tentar enviar email
            $email_enviado = enviarEmail($nome, $email, $assunto, $mensagem_contato);
            
            if($email_enviado) {
                $sucesso = true;
                $mensagem = "Mensagem enviada com sucesso! Recebemos seu contato e responderemos em breve.";
            } else {
                $sucesso = true;
                $mensagem = "Mensagem salva com sucesso! Houve um problema no envio do email, mas entraremos em contato.";
            }
        } else {
            $mensagem = "Erro ao salvar mensagem. Tente novamente.";
        }
    }
}

$titulo = "Contato";
include 'includes/header.php';
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-white mb-0">Fale Conosco</h4>
                </div>
                <div class="card-body">
                    <?php if(!empty($mensagem)): ?>
                        <div class="alert <?= $sucesso ? 'alert-success' : 'alert-danger' ?>">
                            <?= $mensagem ?>
                        </div>
                    <?php endif; ?>

                    <?php if(!$sucesso): ?>
                    <form action="contato.php" method="post" id="contatoForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nome" class="form-label">Nome:</label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="assunto" class="form-label">Assunto:</label>
                            <input type="text" class="form-control" id="assunto" name="assunto" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="mensagem" class="form-label">Mensagem:</label>
                            <textarea class="form-control" id="mensagem" name="mensagem" rows="5" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Enviar Mensagem</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="text-white mb-0">Informações de Contato</h5>
                </div>
                <div class="card-body">
                    <p><strong>Equipe Seekers:</strong> Entraremos em contato em breve.</p>
                    <p><strong>Horário:</strong> 24/7</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('contatoForm')?.addEventListener('submit', function(e) {
    if (!validarFormulario('contatoForm')) {
        e.preventDefault();
        alert('Por favor, preencha todos os campos obrigatórios.');
    }
});
</script>

<?php include 'includes/footer.php'; ?>