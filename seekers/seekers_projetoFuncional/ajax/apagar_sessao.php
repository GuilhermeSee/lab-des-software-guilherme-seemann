<?php
session_start();

if(!isset($_SESSION['usuarioLogado']) || !isset($_GET['id'])){
    header("Location: ../dashboard.php");
    exit;
}

require_once '../config/database.php';

$sessao_id = $_GET['id'];
$conexao = conexao();

// Verificar se a sessão pertence ao usuário
$sql = "SELECT id FROM sessoes_jogo WHERE id = :id AND criador_id = :criador_id";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':id', $sessao_id);
$stmt->bindParam(':criador_id', $_SESSION['usuarioLogado']);
$stmt->execute();

if(!$stmt->fetch()){
    header("Location: ../dashboard.php");
    exit;
}

// Apagar sessão
$sql = "DELETE FROM sessoes_jogo WHERE id = :id";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':id', $sessao_id);

if($stmt->execute()){
    header("Location: ../dashboard.php?msg=sessao_apagada");
} else {
    header("Location: ../editar_sessao.php?id=" . $sessao_id . "&erro=nao_apagada");
}
?>