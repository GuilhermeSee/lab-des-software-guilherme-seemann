<?php
class Usuario {
    use DataAccess;

    private $id;
    private $username;
    private $email;
    private $senha;
    private $plataformas;
    private $jogosPreferidos;
    private $usaMods;
    private $bio;
    private $avatar;
    private $dataCriacao;
    private $ultimoAcesso;

    public function inserir(){
        $conexao = conexao();
        $sql = "INSERT INTO usuarios (
                    username, email, senha, plataformas,
                    jogos_preferidos, usa_mods, bio
                    )
                 VALUES (
                    :username, :email, :senha, :plataformas, 
                    :jogos_preferidos, :usa_mods, :bio)";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':senha', $this->senha);
        $stmt->bindParam(':plataformas', $this->plataformas);
        $stmt->bindParam(':jogos_preferidos', $this->jogosPreferidos);
        $stmt->bindParam(':usa_mods', $this->usaMods);
        $stmt->bindParam(':bio', $this->bio);

        return $stmt->execute();    
    }

    public function buscarPorUsername($username){
        $conexao = conexao();
        $sql = "SELECT * FROM usuarios WHERE username = :username";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        if($resultado){
            $this->id = $resultado['id'];
            $this->username = $resultado['username'];
            $this->email = $resultado['email'];
            $this->senha = $resultado['senha'];
            $this->plataformas = $resultado['plataformas'];
            $this->jogosPreferidos = $resultado['jogos_preferidos'];
            $this->usaMods = $resultado['usa_mods'];
            $this->bio = $resultado['bio'];
            $this->avatar = $resultado['avatar'];
            $this->dataCriacao = $resultado['data_criacao'];
            $this->ultimoAcesso = $resultado['ultimo_acesso'];
            return true;
        }
        return false;
    }

    public function verificarSenha($senha){
        return password_verify($senha, $this->senha);
    }

    public function atualizarUltimoAcesso(){
        $conexao = conexao();
        $sql = "UPDATE usuarios SET ultimo_acesso = NOW() WHERE id = :id";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
}