CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    plataformas JSON,
    jogos_preferidos JSON,
    usa_mods BOOLEAN DEFAULT false,
    bio TEXT,
    avatar VARCHAR(255),
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acesso TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE builds (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    jogo VARCHAR(50) NOT NULL,
    classe VARCHAR(50),
    nivel INT,
    atributos JSON,
    equipamentos JSON,
    descricao TEXT,
    autor_id INT,
    publico BOOLEAN DEFAULT true,
    curtidas INT DEFAULT 0,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (autor_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE sessoes_jogo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jogo VARCHAR(50) NOT NULL,
    plataforma VARCHAR(20) NOT NULL,
    tipo_sessao VARCHAR(30) NOT NULL,
    usa_mods BOOLEAN DEFAULT false,
    max_jogadores INT DEFAULT 2,
    senha_sessao VARCHAR(50),
    descricao TEXT,
    criador_id INT,
    status VARCHAR(20) DEFAULT 'aberta',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (criador_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE participantes_sessao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sessao_id INT,
    usuario_id INT,
    data_entrada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sessao_id) REFERENCES sessoes_jogo(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    UNIQUE KEY unique_participante (sessao_id, usuario_id)
);

CREATE TABLE topicos_forum (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    jogo VARCHAR(50),
    autor_id INT,
    conteudo TEXT NOT NULL,
    fixado BOOLEAN DEFAULT false,
    fechado BOOLEAN DEFAULT false,
    visualizacoes INT DEFAULT 0,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultima_atividade TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (autor_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE respostas_forum (
    id INT AUTO_INCREMENT PRIMARY KEY,
    topico_id INT,
    autor_id INT,
    conteudo TEXT NOT NULL,
    data_resposta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (topico_id) REFERENCES topicos_forum(id) ON DELETE CASCADE,
    FOREIGN KEY (autor_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE progresso_jogos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    jogo VARCHAR(50) NOT NULL,
    bosses_derrotados JSON,
    itens_encontrados JSON,
    conquistas JSON,
    horas_jogadas INT DEFAULT 0,
    nivel_personagem INT,
    ultima_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    UNIQUE KEY unique_progresso (usuario_id, jogo)
);

CREATE TABLE parcerias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jogador1_id INT,
    jogador2_id INT,
    jogo VARCHAR(50) NOT NULL,
    data_inicio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_fim TIMESTAMP NULL,
    ativa BOOLEAN DEFAULT true,
    FOREIGN KEY (jogador1_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (jogador2_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    UNIQUE KEY unique_parceria (jogador1_id, jogador2_id, jogo)
);

CREATE TABLE notificacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    tipo VARCHAR(50) NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    mensagem TEXT NOT NULL,
    data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    lida BOOLEAN DEFAULT false,
    dados_extras JSON,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE amizades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario1_id INT,
    usuario2_id INT,
    status VARCHAR(20) DEFAULT 'pendente',
    data_solicitacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_resposta TIMESTAMP NULL,
    FOREIGN KEY (usuario1_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario2_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    UNIQUE KEY unique_amizade (usuario1_id, usuario2_id)
);

CREATE TABLE avaliacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    avaliador_id INT,
    avaliado_id INT,
    sessao_id INT,
    nota INT CHECK (nota BETWEEN 1 AND 5),
    comentario TEXT,
    data_avaliacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (avaliador_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (avaliado_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (sessao_id) REFERENCES sessoes_jogo(id) ON DELETE CASCADE,
    UNIQUE KEY unique_avaliacao (avaliador_id, avaliado_id, sessao_id)
);
