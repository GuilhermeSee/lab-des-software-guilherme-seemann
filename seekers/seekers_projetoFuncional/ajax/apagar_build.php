<?php
session_start();

if(!isset($_SESSION['usuarioLogado']) || !isset($_GET['id'])){
    header("Location: ../dashboard.php");
    exit;
}

require_once '../config/database.php';

$build_id = $_GET['id'];
$conexao = conexao();

// Verificar se a build pertence ao usuário
$sql = "SELECT id FROM builds WHERE id = :id AND autor_id = :autor_id";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':id', $build_id);
$stmt->bindParam(':autor_id', $_SESSION['usuarioLogado']);
$stmt->execute();

if(!$stmt->fetch()){
    header("Location: ../dashboard.php");
    exit;
}

// Apagar build
$sql = "DELETE FROM builds WHERE id = :id";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':id', $build_id);

if($stmt->execute()){
    header("Location: ../dashboard.php?msg=build_apagada");
} else {
    header("Location: ../editar_build.php?id=" . $build_id . "&erro=nao_apagada");
}
?>