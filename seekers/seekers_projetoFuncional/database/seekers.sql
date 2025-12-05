-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 01/12/2025 às 03:15
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `seekers`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `builds`
--

CREATE TABLE `builds` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `jogo` varchar(50) NOT NULL,
  `classe` varchar(50) DEFAULT NULL,
  `nivel` int(11) DEFAULT NULL,
  `atributos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`atributos`)),
  `equipamentos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`equipamentos`)),
  `descricao` text DEFAULT NULL,
  `autor_id` int(11) DEFAULT NULL,
  `publico` tinyint(1) DEFAULT 1,
  `curtidas` int(11) DEFAULT 0,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `builds`
--

INSERT INTO `builds` (`id`, `nome`, `jogo`, `classe`, `nivel`, `atributos`, `equipamentos`, `descricao`, `autor_id`, `publico`, `curtidas`, `criado_em`) VALUES
(1, 'Quality Strength', 'Elden Ring', 'Vagabond', 150, '{\"vigor\": 60, \"forca\": 80, \"destreza\": 20, \"inteligencia\": 9, \"fe\": 15}', '{\"arma_principal\": \"Greatsword +25\", \"arma_secundaria\": \"Brass Shield\", \"armadura\": \"Radahn Set\", \"anel1\": \"Great-Jar Arsenal\", \"anel2\": \"Dragoncrest Greatshield\"}', 'Build de força pura para PvE, ideal para iniciantes. Muito tanque e dano alto.', 1, 1, 0, '2025-11-22 08:39:17');

-- --------------------------------------------------------

--
-- Estrutura para tabela `builds_favoritas`
--

CREATE TABLE `builds_favoritas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `build_id` int(11) DEFAULT NULL,
  `data_favorito` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contatos`
--

CREATE TABLE `contatos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `assunto` varchar(200) NOT NULL,
  `mensagem` text NOT NULL,
  `data_envio` timestamp NOT NULL DEFAULT current_timestamp(),
  `respondido` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `curtidas_builds`
--

CREATE TABLE `curtidas_builds` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `build_id` int(11) DEFAULT NULL,
  `data_curtida` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagens_lidas`
--

CREATE TABLE `mensagens_lidas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `sessao_id` int(11) DEFAULT NULL,
  `ultima_leitura` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagens_sessao`
--

CREATE TABLE `mensagens_sessao` (
  `id` int(11) NOT NULL,
  `sessao_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `mensagem` text NOT NULL,
  `tipo` varchar(20) DEFAULT 'mensagem',
  `data_envio` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `notificacoes`
--

CREATE TABLE `notificacoes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `tipo` varchar(50) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `mensagem` text NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `lida` tinyint(1) DEFAULT 0,
  `dados_extras` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dados_extras`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `participantes_sessao`
--

CREATE TABLE `participantes_sessao` (
  `id` int(11) NOT NULL,
  `sessao_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `data_entrada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `sessoes_favoritas`
--

CREATE TABLE `sessoes_favoritas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `sessao_id` int(11) DEFAULT NULL,
  `data_favorito` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `sessoes_jogo`
--

CREATE TABLE `sessoes_jogo` (
  `id` int(11) NOT NULL,
  `jogo` varchar(50) NOT NULL,
  `plataforma` varchar(20) NOT NULL,
  `tipo_sessao` varchar(30) NOT NULL,
  `usa_mods` tinyint(1) DEFAULT 0,
  `descricao` text DEFAULT NULL,
  `criador_id` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'aberta',
  `max_participantes` int(11) DEFAULT 4,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `sessoes_jogo`
--

INSERT INTO `sessoes_jogo` (`id`, `jogo`, `plataforma`, `tipo_sessao`, `usa_mods`, `descricao`, `criador_id`, `status`, `max_participantes`, `criado_em`) VALUES
(1, 'Dark Souls 3', 'PC', 'Boss Fight', 0, 'Ajuda para derrotar o Nameless King. Preciso de 2 phantoms experientes!', 1, 'aberta', 4, '2025-11-22 08:39:17'),
(2, 'Elden Ring', 'PlayStation 5', 'Cooperativo', 0, 'Explorando Caelid juntos. Iniciantes bem-vindos!', 1, 'aberta', 4, '2025-11-22 08:39:17'),
(3, 'Bloodborne', 'PlayStation 4', 'PvP', 0, 'Duelos honorários no Nightmare Frontier. BL120 apenas.', 1, 'aberta', 4, '2025-11-22 08:39:17'),
(4, 'Souls-like Assistant', 'Web', 'AI_CHAT', 0, 'Chat com IA especializada em jogos souls-like', 1, 'aberta', 999, '2025-01-01 00:00:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `solicitacoes_participacao`
--

CREATE TABLE `solicitacoes_participacao` (
  `id` int(11) NOT NULL,
  `sessao_id` int(11) DEFAULT NULL,
  `solicitante_id` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pendente',
  `data_solicitacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_resposta` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `plataformas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`plataformas`)),
  `jogos_preferidos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`jogos_preferidos`)),
  `usa_mods` tinyint(1) DEFAULT 0,
  `bio` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `ultimo_acesso` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `email`, `senha`, `plataformas`, `jogos_preferidos`, `usa_mods`, `bio`, `avatar`, `data_criacao`, `ultimo_acesso`) VALUES
(1, 'admin', 'admin@seekers.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '[\"PC\"]', '[\"Dark Souls 3\",\"Elden Ring\"]', 0, 'Administrador da plataforma Seekers', NULL, '2025-11-22 08:39:17', '2025-11-23 23:01:26');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `builds`
--
ALTER TABLE `builds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_builds_jogo` (`jogo`),
  ADD KEY `idx_builds_autor` (`autor_id`);

--
-- Índices de tabela `builds_favoritas`
--
ALTER TABLE `builds_favoritas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_favorito_build` (`usuario_id`,`build_id`),
  ADD KEY `build_id` (`build_id`);

--
-- Índices de tabela `contatos`
--
ALTER TABLE `contatos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `curtidas_builds`
--
ALTER TABLE `curtidas_builds`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_curtida` (`usuario_id`,`build_id`),
  ADD KEY `build_id` (`build_id`);

--
-- Índices de tabela `mensagens_lidas`
--
ALTER TABLE `mensagens_lidas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_leitura` (`usuario_id`,`sessao_id`),
  ADD KEY `sessao_id` (`sessao_id`);

--
-- Índices de tabela `mensagens_sessao`
--
ALTER TABLE `mensagens_sessao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessao_id` (`sessao_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `participantes_sessao`
--
ALTER TABLE `participantes_sessao`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_participante` (`sessao_id`,`usuario_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `sessoes_favoritas`
--
ALTER TABLE `sessoes_favoritas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_favorito_sessao` (`usuario_id`,`sessao_id`),
  ADD KEY `sessao_id` (`sessao_id`);

--
-- Índices de tabela `sessoes_jogo`
--
ALTER TABLE `sessoes_jogo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `criador_id` (`criador_id`),
  ADD KEY `idx_sessoes_jogo_plataforma` (`jogo`,`plataforma`),
  ADD KEY `idx_sessoes_status` (`status`);

--
-- Índices de tabela `solicitacoes_participacao`
--
ALTER TABLE `solicitacoes_participacao`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_solicitacao` (`sessao_id`,`solicitante_id`),
  ADD KEY `solicitante_id` (`solicitante_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `builds`
--
ALTER TABLE `builds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `builds_favoritas`
--
ALTER TABLE `builds_favoritas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contatos`
--
ALTER TABLE `contatos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `curtidas_builds`
--
ALTER TABLE `curtidas_builds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `mensagens_lidas`
--
ALTER TABLE `mensagens_lidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `mensagens_sessao`
--
ALTER TABLE `mensagens_sessao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `participantes_sessao`
--
ALTER TABLE `participantes_sessao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `sessoes_favoritas`
--
ALTER TABLE `sessoes_favoritas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `sessoes_jogo`
--
ALTER TABLE `sessoes_jogo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `solicitacoes_participacao`
--
ALTER TABLE `solicitacoes_participacao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `builds`
--
ALTER TABLE `builds`
  ADD CONSTRAINT `builds_ibfk_1` FOREIGN KEY (`autor_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `builds_favoritas`
--
ALTER TABLE `builds_favoritas`
  ADD CONSTRAINT `builds_favoritas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `builds_favoritas_ibfk_2` FOREIGN KEY (`build_id`) REFERENCES `builds` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `curtidas_builds`
--
ALTER TABLE `curtidas_builds`
  ADD CONSTRAINT `curtidas_builds_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `curtidas_builds_ibfk_2` FOREIGN KEY (`build_id`) REFERENCES `builds` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `mensagens_lidas`
--
ALTER TABLE `mensagens_lidas`
  ADD CONSTRAINT `mensagens_lidas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mensagens_lidas_ibfk_2` FOREIGN KEY (`sessao_id`) REFERENCES `sessoes_jogo` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `mensagens_sessao`
--
ALTER TABLE `mensagens_sessao`
  ADD CONSTRAINT `mensagens_sessao_ibfk_1` FOREIGN KEY (`sessao_id`) REFERENCES `sessoes_jogo` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mensagens_sessao_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD CONSTRAINT `notificacoes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `participantes_sessao`
--
ALTER TABLE `participantes_sessao`
  ADD CONSTRAINT `participantes_sessao_ibfk_1` FOREIGN KEY (`sessao_id`) REFERENCES `sessoes_jogo` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `participantes_sessao_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `sessoes_favoritas`
--
ALTER TABLE `sessoes_favoritas`
  ADD CONSTRAINT `sessoes_favoritas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sessoes_favoritas_ibfk_2` FOREIGN KEY (`sessao_id`) REFERENCES `sessoes_jogo` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `sessoes_jogo`
--
ALTER TABLE `sessoes_jogo`
  ADD CONSTRAINT `sessoes_jogo_ibfk_1` FOREIGN KEY (`criador_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `solicitacoes_participacao`
--
ALTER TABLE `solicitacoes_participacao`
  ADD CONSTRAINT `solicitacoes_participacao_ibfk_1` FOREIGN KEY (`sessao_id`) REFERENCES `sessoes_jogo` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `solicitacoes_participacao_ibfk_2` FOREIGN KEY (`solicitante_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;