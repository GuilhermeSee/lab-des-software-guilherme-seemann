## Identificação do Projeto 
**Nome do Projeto:** Seekers - Plataforma de Conexão para Jogadores de Souls-like  
**Autor:** Guilherme Seemann  
**Orientador:** Clarissa Castellã Xavier  
**Data:** [Data de Aprovação]  

## Introdução e Visão Geral 
 
Desenvolvimento de uma plataforma web especializada para a comunidade de jogadores de jogos souls-like (Dark Souls, Elden Ring, Bloodborne, Sekiro, etc.). A proposta é criar um ambiente centralizado que facilite a conexão entre jogadores para sessões cooperativas, compartilhamento de builds personalizadas, discussões comunitárias e acompanhamento colaborativo de progresso nos jogos. O sistema integrará funcionalidades atualmente dispersas em diferentes plataformas, oferecendo uma experiência unificada e especializada para a comunidade.

## Objetivos do Projeto
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
  
## Escopo do Projeto
**Entregáveis:**
- Plataforma web Seekers com sistema de matchmaking, compartilhamento de builds, fórum de discussões e acompanhamento de progresso
- Documentação técnica da plataforma
- Manual do usuário para utilização
- Código-fonte documentado e versionado

**Requisitos:**  

**Funcionais:**
- Sistema de cadastro e autenticação de usuários com perfis personalizados
- Matchmaking inteligente para encontrar parceiros compatíveis por critérios específicos
- Criador de builds com calculadora de atributos e sistema de compartilhamento
- Fórum de discussões com categorias por jogo e sistema de moderação
- Sistema de compartilhamento de progresso em tempo real entre parceiros
- Notificações em tempo real para atividades relevantes da comunidade
- Sistema de amizades e grupos de jogo
- Busca e filtros avançados por jogo, plataforma e tipo de sessão
- Sistema de avaliação e feedback entre jogadores
- Versionamento de builds para atualizações dos jogos
 
**Não Funcionais:** 
- Performance otimizada para múltiplos usuários simultâneos
- Interface responsiva para desktop e mobile seguindo princípios de usabilidade
- Segurança robusta para proteção de dados pessoais
- Disponibilidade com tempo de resposta otimizado
- Escalabilidade para crescimento da base de usuários
 
**Exclusões:**
- Integração direta com APIs oficiais dos jogos (limitações das desenvolvedoras)
- Sistema de chat de voz integrado
- Streaming de gameplay
- Marketplace para itens do jogo
- Sincronização automática com saves dos jogos
- Aplicativo mobile nativo
- Suporte oficial das desenvolvedoras dos jogos
 
## Recursos Necessários
**Pessoas:** Desenvolvedor full-stack (1), orientador acadêmico (1)
 
**Tecnologia:** 
- Frontend: HTML5, CSS3, JavaScript, Bootstrap, jQuery
- Backend: PHP 8.0+, APIs RESTful
- Banco de dados: MySQL, PDO PHP
- Utilitários: Composer, PHPMailer, Chart.js, Password_hash()
- Hospedagem: Servidor web com suporte PHP/MySQL
- Ferramentas: Git, Visual Studio Code, XAMPP, phpMyAdmin
 
## Cronograma 
**Fase 1:** Pesquisa e Planejamento (Fev/2025)
- Análise de requisitos e pesquisa da comunidade souls-like
- Definição de funcionalidades prioritárias

**Fase 2:** Modelagem e Prototipação (Mar/2025)
- Modelagem UML e prototipação da interface
- Definição da arquitetura do sistema

**Fase 3:** Desenvolvimento Core (Abr/2025)
- Sistema de usuários e autenticação
- Estrutura básica da plataforma

**Fase 4:** Funcionalidades Principais (Mai/2025)
- Implementação do matchmaking e perfis de jogador
- Desenvolvimento do criador de builds e fórum

**Fase 5:** Funcionalidades Avançadas (Jun/2025)
- Sistema de progresso compartilhado e notificações
- Testes, documentação e deploy

## Premissas 
- Disponibilidade do desenvolvedor e suporte do orientador acadêmico
- Acesso a ferramentas de desenvolvimento web e hospedagem
- Participação da comunidade souls-like para testes e feedback
- Conhecimento técnico dos jogos do gênero para implementar funcionalidades específicas

## Restrições 
- Prazo final para conclusão do projeto acadêmico (Jun/2025)
- Limitações de orçamento para hospedagem e ferramentas premium
- Impossibilidade de integração oficial com APIs dos jogos
- Desenvolvimento individual (um desenvolvedor)

## Riscos e Planos de Contingência

**Risco 1:** Baixa adesão inicial da comunidade  
**Mitigação:** Criar conteúdo inicial atrativo, parcerias com comunidades existentes, sistema de gamificação para incentivar participação.

**Risco 2:** Problemas de performance com múltiplos usuários  
**Mitigação:** Implementar cache, otimização de queries, testes de carga regulares, arquitetura escalável.

**Risco 3:** Dificuldades técnicas no sistema de progresso compartilhado  
**Mitigação:** Simplificar funcionalidade inicial, implementar versão manual antes da automática, consultar comunidade técnica.

**Risco 4:** Complexidade do sistema de builds  
**Mitigação:** Começar com builds básicas, expandir gradualmente, usar dados da comunidade existente como referência.

**Risco 5:** Atrasos no cronograma de desenvolvimento  
**Mitigação:** Priorizar funcionalidades essenciais, desenvolvimento incremental, reduzir escopo se necessário.

**Risco 6:** Mudanças nos jogos que afetem as builds  
**Mitigação:** Sistema flexível de versionamento de builds, atualizações regulares baseadas em patches dos jogos.

## Critérios de Aceitação

- Sistema permite cadastro e login de usuários com perfis completos
- Matchmaking funciona corretamente filtrando por plataforma, jogo e preferências
- Criador de builds permite criar, editar e compartilhar builds funcionais
- Fórum permite criar tópicos, responder e moderar discussões
- Sistema de progresso compartilhado registra e exibe atividades dos parceiros
- Notificações funcionam em tempo real para atividades relevantes
- Interface é responsiva e funciona em diferentes dispositivos
- Sistema suporta múltiplos usuários simultâneos sem degradação significativa
- Todas as funcionalidades principais foram testadas e validadas
- Documentação técnica e manual do usuário estão completos e claros
- Código-fonte está documentado e versionado adequadamente