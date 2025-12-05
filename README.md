# Seekers - Plataforma de Conexão para Jogadores de Souls-like

# 1. Identificação do Projeto 
- **Nome do Projeto:** Seekers - Plataforma de Conexão para Jogadores de Souls-like
- **Autor:** Guilherme Seemann
- **Orientador:** Clarissa Castellã Xavier
- **Data de Aprovação:** Dezembro 2025

# 2. Introdução e Visão Geral 

Este projeto visa desenvolver uma plataforma web especializada que facilite a conexão entre jogadores de jogos souls-like (Dark Souls, Elden Ring, Bloodborne, Sekiro, etc.). A proposta é criar um ambiente centralizado que integre as diversas ferramentas e recursos atualmente dispersos no ecossistema da comunidade souls-like, oferecendo funcionalidades de sessões entre jogadores, compartilhamento de builds, chat com integração com IA para tirar dúvidas e chat colaborativo entre jogadores.

A natureza cooperativa dos jogos souls-like, onde jogadores podem se auxiliar mutuamente para superar desafios complexos, cria uma demanda natural por ferramentas especializadas que facilitem a conexão entre membros da comunidade. Atualmente, os jogadores dependem de plataformas genéricas que não atendem às particularidades desta comunidade específica.

# 3. Objetivos do Projeto

**Objetivo Geral:**
Desenvolver uma plataforma web especializada que facilite a conexão entre jogadores de jogos souls-like, oferecendo funcionalidades de sessões entre jogadores, compartilhamento de builds, chat com integração com IA para tirar dúvidas e chat colaborativo entre jogadores.

**Objetivos Específicos:**
- Implementar sistema de conexão inteligente que filtre jogadores por plataforma, jogo específico, preferências de modificação e estilo de sessão desejado
- Desenvolver ferramenta social para criação e compartilhamento de builds baseada em atributos base do personagem e equipamentos utilizados
- Desenvolver interface responsiva e intuitiva que funcione adequadamente em dispositivos desktop e mobile
- Integrar sistema de notificações em tempo real para atividades de parceiros e atualizações da comunidade
- Implementar medidas de segurança robustas para proteção de dados pessoais e prevenção de comportamentos inadequados
- Desenvolver sistema de perfis de usuário com histórico de atividades, estatísticas de jogos e sistema de reputação

# 4. Escopo do Projeto
O escopo detalhado do projeto está documentado no arquivo [escopo.md](seekers/escopo.md)

# 5. Tecnologias

🛠 **Tecnologias Utilizadas**

As seguintes ferramentas foram utilizadas na construção do projeto:

**Frontend:**
- HTML5, CSS3, JavaScript
- Bootstrap para interface responsiva
- jQuery para interações dinâmicas

**Backend:**
- PHP 8.0+
- APIs RESTful
- Sistema de autenticação com sessões PHP

**Banco de Dados:**
- MySQL para armazenamento de dados
- PDO PHP para conexão segura
- Scripts SQL para criação e manutenção do banco

**Ferramentas de Desenvolvimento:**
- Visual Studio Code
- XAMPP para ambiente local
- Git para controle de versão
- phpMyAdmin para gerenciamento do banco

**Utilitários:**
- Composer para gerenciamento de dependências PHP
- PHPMailer para envio de notificações por email
- Chart.js para geração de relatórios visuais
- Password_hash() PHP para criptografia de senhas

**Funcionalidades Principais:**
- Sistema de matchmaking inteligente para jogadores souls-like
- Criador e compartilhador de builds personalizadas
- Chat com IA especializada usando Google Gemini
- Sistema de sessões de jogo com chat em tempo real
- Notificações em tempo real para atividades da comunidade
- Sistema de perfis de jogador com favoritos e estatísticas
- Sistema de curtidas e favoritos para builds e sessões

## Funcionalidades Principais

## Sistema de Matchmaking
- Filtros por plataforma (PC, PlayStation, Xbox, Nintendo Switch)
- Seleção de jogo específico (Dark Souls, Elden Ring, Bloodborne, etc.)
- Preferências de modificação (com/sem mods)
- Estilo de sessão desejado (cooperativo, PvP, boss fights)

## Criador de Builds
- Interface intuitiva para criação de builds personalizadas
- Calculadora de atributos base (força, destreza, inteligência, fé)
- Sistema de equipamentos (armas, armaduras, anéis)
- Compartilhamento e versionamento de builds

## Chat com IA Especializada
- Assistente Arauto Esmeralda especializado em jogos souls-like
- Integração com Google Gemini AI
- Respostas personalizadas sobre builds, estratégias e dicas
- Disponível 24/7 para todos os usuários

## Sistema de Sessões
- Criação de sessões cooperativas por jogo e plataforma
- Chat em tempo real entre participantes
- Sistema de solicitações de participação
- Notificações de atividades nas sessões

# 6. Pré-Requisitos
Para executar o projeto localmente, você precisará ter instalado:

- XAMPP (Apache, MySQL, PHP)
- PHP 8.0 ou superior
- Composer para gerenciamento de dependências PHP
- Git para controle de versão
- Navegador web moderno com suporte a JavaScript

# 7. Instalação

### Pré-requisitos:
- XAMPP (Apache + MySQL + PHP 8.0+)
- Navegador web moderno

### Passo a passo:

1. **Configurar XAMPP:**
   - Iniciar Apache e MySQL no XAMPP Control Panel

2. **Criar Banco de Dados:**
   - Acessar http://localhost/phpmyadmin
   - Importar o arquivo: `database/seekers.sql`

3. **Instalar Arquivos:**
   - Copiar pasta `seekers_projetoFuncional` para: `C:\xampp\htdocs\seekers_projetoFuncional`

4. **Acessar Sistema:**
   - Abrir navegador em: http://localhost/seekers_projetoFuncional

### Usuário de Teste:
- **Username:** admin2
- **Senha:** password

## Funcionalidades e Demonstração da Aplicação

### Sistema de Autenticação
- Cadastro de usuários com validação completa (HTML5 + JavaScript + PHP)
- Login seguro com senhas criptografadas (password_hash)
- Controle de sessões PHP
- Perfis personalizados com plataformas e jogos preferidos

### Sistema de Builds
- Criação de builds personalizadas com atributos (vigor, força, destreza, inteligência, fé)
- Equipamentos detalhados (armas, armaduras, anéis)
- Cálculo automático de nível baseado nos atributos
- Sistema de curtidas com AJAX
- Busca dinâmica por nome e jogo
- Sistema de favoritos

### Chat com IA Especializada
- Integração com Google Gemini AI para respostas inteligentes
- Disponível 24/7 para dúvidas sobre builds, estratégias e dicas
- Interface de chat em tempo real

### Sistema de Sessões de Jogo
- Criação de sessões cooperativas por jogo e plataforma
- Tipos de sessão: Boss Fight, Cooperativo, PvP
- Sistema de participação com solicitações
- Chat em tempo real entre participantes
- Notificações de atividades

### Interface e Experiência
- Design responsivo inspirado em Elden Ring Nightreign
- Tema dark com paleta azul/roxo/prateado
- Dashboard personalizado com estatísticas
- Sistema de notificações em tempo real
- Páginas de favoritos e gerenciamento de perfil

# 8. Acesso ao projeto

**PROJETO FUNCIONAL**

- **Ambiente Local:** http://localhost/seekers_projetoFuncional (com XAMPP)
- **Hospedagem Online:** Configurado para produção
- **Banco de Dados:** MySQL hospedado em seekers.mysql.dbaas.com.br
- **Status:** Sistema operacional e funcional

# 9. Licença

Este projeto foi desenvolvido para fins acadêmicos como parte da disciplina de Laboratório de Desenvolvimento de Software do IFRS Campus Canoas.

**Uso Acadêmico:** Permitido 
**Uso Comercial:** Permitido
**Modificações:** Permitidas 

# 10. Agradecimentos
(Acknowledgements em inglês) é um espaço opcional para dar crédito a pessoas, projetos, ou bibliotecas que ajudaram no desenvolvimento do seu projeto, como inspiração, recursos, ou colaboração. É uma forma de reconhecer a ajuda recebida e pode ser incluída para melhorar a usabilidade do projeto e a transparência.

*A ser incluído posteriormente.*
