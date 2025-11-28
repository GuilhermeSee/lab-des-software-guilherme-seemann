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
    data_sessao DATE,
    horario TIME,
    max_participantes INT DEFAULT 4,
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

CREATE TABLE contatos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    assunto VARCHAR(200) NOT NULL,
    mensagem TEXT NOT NULL,
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    respondido BOOLEAN DEFAULT false
);

CREATE TABLE curtidas_builds (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    build_id INT,
    data_curtida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (build_id) REFERENCES builds(id) ON DELETE CASCADE,
    UNIQUE KEY unique_curtida (usuario_id, build_id)
);

CREATE TABLE builds_favoritas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    build_id INT,
    data_favorito TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (build_id) REFERENCES builds(id) ON DELETE CASCADE,
    UNIQUE KEY unique_favorito_build (usuario_id, build_id)
);

CREATE TABLE sessoes_favoritas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    sessao_id INT,
    data_favorito TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (sessao_id) REFERENCES sessoes_jogo(id) ON DELETE CASCADE,
    UNIQUE KEY unique_favorito_sessao (usuario_id, sessao_id)
);

CREATE TABLE notificacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    tipo VARCHAR(50) NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    mensagem TEXT NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    lida BOOLEAN DEFAULT false,
    dados_extras JSON,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE solicitacoes_participacao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sessao_id INT,
    solicitante_id INT,
    status VARCHAR(20) DEFAULT 'pendente',
    data_solicitacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_resposta TIMESTAMP NULL,
    FOREIGN KEY (sessao_id) REFERENCES sessoes_jogo(id) ON DELETE CASCADE,
    FOREIGN KEY (solicitante_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    UNIQUE KEY unique_solicitacao (sessao_id, solicitante_id)
);

CREATE TABLE mensagens_sessao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sessao_id INT,
    usuario_id INT,
    mensagem TEXT NOT NULL,
    tipo VARCHAR(20) DEFAULT 'mensagem',
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sessao_id) REFERENCES sessoes_jogo(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE mensagens_lidas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    sessao_id INT,
    ultima_leitura TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (sessao_id) REFERENCES sessoes_jogo(id) ON DELETE CASCADE,
    UNIQUE KEY unique_leitura (usuario_id, sessao_id)
);
