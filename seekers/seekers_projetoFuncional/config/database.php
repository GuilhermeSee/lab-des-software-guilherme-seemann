<?php
function conexao(){
    $dsn = "seekers.mysql.dbaas.com.br";
    $database = "seekers";
    $usuario = "seekers";
    $senha = "Guilherme12!12";
    $porta = 3306;

    try{
        $conexao = new PDO("mysql:host=$dsn;dbname=$database;port=$porta;charset=utf8", $usuario, $senha);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexao;    
    }catch(Exception $e){
        echo "Erro: " . $e->getMessage();
        exit;
    }
}