# Seekers - Plataforma de Conexão para Jogadores de Souls-like

## 1. Identificação do Projeto 
- **Nome do Projeto:** Seekers - Plataforma de Conexão para Jogadores de Souls-like
- **Autor:** Guilherme Seemann
- **Orientador:** Clarissa Castellã Xavier
- **Data de Aprovação:** [Data de Aprovação]

## 2. Introdução e Visão Geral 

Este projeto visa desenvolver uma plataforma web especializada que facilite a conexão entre jogadores de jogos souls-like (Dark Souls, Elden Ring, Bloodborne, Sekiro, etc.). A proposta é criar um ambiente centralizado que integre as diversas ferramentas e recursos atualmente dispersos no ecossistema da comunidade souls-like, oferecendo funcionalidades de matchmaking inteligente, compartilhamento de builds, discussões comunitárias e acompanhamento colaborativo de progresso nos jogos.

A natureza cooperativa dos jogos souls-like, onde jogadores podem se auxiliar mutuamente para superar desafios complexos, cria uma demanda natural por ferramentas especializadas que facilitem a conexão entre membros da comunidade. Atualmente, os jogadores dependem de plataformas genéricas que não atendem às particularidades desta comunidade específica.

## 3. Objetivos do Projeto

**Objetivo Geral:**
Desenvolver uma plataforma web especializada que facilite a conexão entre jogadores de jogos souls-like, oferecendo ferramentas integradas para matchmaking, compartilhamento de builds, discussões comunitárias e acompanhamento colaborativo de progresso nos jogos.

**Objetivos Específicos:**
- Implementar sistema de conexão inteligente que filtre jogadores por plataforma, jogo específico, preferências de modificação e estilo de sessão desejado
- Desenvolver ferramenta social para criação e compartilhamento de builds baseada em atributos base do personagem e equipamentos utilizados
- Criar fórum de discussões categorizadas por jogo específico e tópicos técnicos, com sistema de moderação e busca avançada
- Implementar sistema de acompanhamento de progresso colaborativo que permita compartilhamento em tempo real de conquistas, itens encontrados e bosses derrotados
- Desenvolver interface responsiva e intuitiva que funcione adequadamente em dispositivos desktop e mobile
- Integrar sistema de notificações em tempo real para atividades de parceiros e atualizações da comunidade
- Implementar medidas de segurança robustas para proteção de dados pessoais e prevenção de comportamentos inadequados
- Desenvolver sistema de perfis de usuário com histórico de atividades, estatísticas de jogos e sistema de reputação

## 4. Escopo do Projeto
O escopo detalhado do projeto está documentado no arquivo [escopo.md](escopo.md)

## 5. Tecnologias

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
- Fórum de discussões categorizadas por jogo
- Acompanhamento de progresso colaborativo entre parceiros
- Notificações em tempo real para atividades da comunidade
- Sistema de perfis de jogador e reputação

## 6. Funcionalidades Principais

### 6.1 Sistema de Matchmaking
- Filtros por plataforma (PC, PlayStation, Xbox, Nintendo Switch)
- Seleção de jogo específico (Dark Souls, Elden Ring, Bloodborne, etc.)
- Preferências de modificação (com/sem mods)
- Estilo de sessão desejado (cooperativo, PvP, boss fights)

### 6.2 Criador de Builds
- Interface intuitiva para criação de builds personalizadas
- Calculadora de atributos base (força, destreza, inteligência, fé)
- Sistema de equipamentos (armas, armaduras, anéis)
- Compartilhamento e versionamento de builds

### 6.3 Fórum Comunitário
- Discussões categorizadas por jogo
- Tópicos técnicos e estratégicos
- Sistema de moderação
- Busca avançada por conteúdo

### 6.4 Progresso Colaborativo
- Compartilhamento de conquistas em tempo real
- Registro de bosses derrotados
- Acompanhamento de itens encontrados
- Estatísticas de progresso entre parceiros

## 7. Pré-Requisitos
Para executar o projeto localmente, você precisará ter instalado:

- XAMPP (Apache, MySQL, PHP)
- PHP 8.0 ou superior
- Composer para gerenciamento de dependências PHP
- Git para controle de versão
- Navegador web moderno com suporte a JavaScript

## 8. Instalação
Instruções detalhadas de instalação serão incluídas após o desenvolvimento:

```bash
# Clonar o repositório
git clone [url-do-repositorio]

# Instalar dependências PHP
composer install

# Configurar XAMPP
# Iniciar Apache e MySQL

# Importar banco de dados
# Acessar phpMyAdmin e importar script SQL

# Configurar arquivo de conexão
# Editar config/database.php com credenciais

# Executar a aplicação
# Acessar http://localhost/seekers
```

## 9. Cronograma de Desenvolvimento

| Etapa | Fev/2025 | Mar/2025 | Abr/2025 | Mai/2025 | Jun/2025 |
|-------|----------|----------|----------|----------|----------|
| Análise de requisitos e pesquisa da comunidade | X | | | | |
| Modelagem UML e prototipação da interface | | X | | | |
| Desenvolvimento do sistema de usuários e autenticação | | | X | | |
| Implementação do matchmaking e perfis de jogador | | | | X | |
| Desenvolvimento do criador de builds e fórum | | | | X | |
| Sistema de progresso compartilhado e notificações | | | | | X |
| Testes, documentação e deploy | | | | | X |

## 10. Estrutura do Projeto

```
seekers/
├── api/
│   ├── auth/
│   ├── matchmaking/
│   ├── builds/
│   ├── forum/
│   └── progress/
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── config/
│   └── database.php
├── database/
│   └── scripts/
├── includes/
│   └── functions.php
├── pages/
│   ├── login.php
│   ├── matchmaking.php
│   ├── builds.php
│   ├── forum.php
│   └── profile.php
└── docs/
    ├── escopo.md
    └── README.md
```

## 11. Contribuição
Este é um projeto acadêmico desenvolvido como Trabalho de Conclusão de Curso. Sugestões e feedback da comunidade souls-like são bem-vindos durante a fase de desenvolvimento e testes.

## 12. Status do Projeto
🚧 **Em Desenvolvimento** - Projeto em fase inicial de desenvolvimento (Fevereiro 2025)

## 13. Contato
- **Desenvolvedor:** Guilherme Seemann
- **Orientador:** Prof.ª Clarissa Castellã Xavier
- **Instituição:** Instituto Federal de Educação, Ciência e Tecnologia do Rio Grande do Sul - Campus Canoas
- **Curso:** Tecnologia em Análise e Desenvolvimento de Sistemas

## 14. Licença
*A ser definida posteriormente.*

---

**Nota:** Este projeto é desenvolvido com foco na comunidade de jogadores souls-like, visando criar uma ferramenta especializada que atenda às necessidades específicas desta comunidade dedicada.