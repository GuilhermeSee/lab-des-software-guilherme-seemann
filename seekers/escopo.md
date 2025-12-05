## Identificação do Projeto 
**Nome do Projeto:** Seekers - Plataforma de Conexão para Jogadores de Souls-like  
**Autor:** Guilherme Seemann  
**Orientador:** Clarissa Castellã Xavier  
**Data:** Dezembro 2025  

## Introdução e Visão Geral 
 
Desenvolvimento de uma plataforma web especializada para a comunidade de jogadores de jogos souls-like (Dark Souls, Elden Ring, Bloodborne, Sekiro, etc.). A proposta é criar um ambiente centralizado que facilite a conexão entre jogadores para sessões cooperativas, compartilhamento de builds personalizadas, sessões entre jogadores com chat integrado e caht com integração da API do gemini. O sistema integrará funcionalidades atualmente dispersas em diferentes plataformas, oferecendo uma experiência unificada e especializada para a comunidade.

## Objetivos do Projeto
**Objetivo Geral:**
Desenvolver uma plataforma web especializada que facilite a conexão entre jogadores de jogos souls-like, compartilhamento de builds personalizadas, sessões entre jogadores com chat integrado e chat com integração da API do gemini.

**Objetivos Específicos:**
- Implementar sistema de conexão inteligente que filtre jogadores por plataforma, jogo específico, preferências de modificação e estilo de sessão desejado
- Desenvolver ferramenta social para criação e compartilhamento de builds baseada em atributos base do personagem e equipamentos utilizados
- Desenvolver interface responsiva e intuitiva que funcione adequadamente em dispositivos desktop e mobile
- Desenvolver sistema de chat com API do Gemini
- Integrar sistema de notificações em tempo real para atividades de parceiros e atualizações da comunidade
- Implementar medidas de segurança robustas para proteção de dados pessoais e prevenção de comportamentos inadequados
- Desenvolver sistema de perfis de usuário com histórico de atividades, estatísticas de jogos e sistema de reputação
  
## Escopo do Projeto
**Entregáveis:**
- Plataforma web Seekers com sistema de matchmaking, compartilhamento de builds e chat entre jogadores e integração com IA.
- Documentação técnica da plataforma
- Código-fonte documentado e versionado

**Requisitos:**  

**Funcionais (Implementados):**
- ✅ Sistema de cadastro e autenticação de usuários com perfis personalizados
- ✅ Sistema de matchmaking por plataforma, jogo e tipo de sessão
- ✅ Criador de builds com calculadora de atributos e sistema de compartilhamento
- ✅ Sistema de sessões de jogo com chat em tempo real
- ✅ Sistema de curtidas e favoritos para builds e sessões
- ✅ Notificações em tempo real para atividades da comunidade
- ✅ Sistema de participação em sessões com solicitações
- ✅ Busca dinâmica por builds com filtros por jogo
- ✅ Dashboard personalizado com estatísticas do usuário
- ✅ Sistema de mensagens em tempo real nas sessões
- ✅ Gerenciamento de perfil com plataformas e jogos preferidos
- ✅ Sistema de favoritos para builds e sessões
- ✅ Chat com IA especializada (Arauto Esmeralda) usando Google Gemini
- ✅ Sistema de contato para suporte
- ✅ Cálculo automático de nível baseado em atributos das builds

**Funcionais (Não Implementados):**
- ❌ Fórum de discussões com categorias por jogo (substituído por chat em sessões)
- ❌ Sistema de amizades e grupos de jogo (substituído por sistema de favoritos)
- ❌ Sistema de avaliação e feedback entre jogadores (substituído por curtidas)
- ❌ Sistema de progresso colaborativo detalhado
 
**Não Funcionais:** 
- Interface responsiva para desktop e mobile seguindo princípios de usabilidade

**Exclusões (Confirmadas):**
- Integração direta com APIs oficiais dos jogos (limitações das desenvolvedoras)
- Sistema de chat de voz integrado
- Sincronização automática com saves dos jogos
- Aplicativo mobile nativo
- Fórum de discussões (substituído por chat de sessões)
- Sistema de amizades complexo (substituído por chat de sessões)
- Sistema de avaliação entre jogadores (substituído por curtidas)
 
## Recursos Necessários
**Pessoas:** Desenvolvedor full-stack (1), orientador acadêmico (1)
 
**Tecnologia (Implementada):** 
- Frontend: HTML5, CSS3, JavaScript, Bootstrap 5.1.3, jQuery 3.6.0
- Backend: PHP 8.0+ (sem frameworks), PDO para MySQL
- Banco de dados: MySQL 8.0 com 12 tabelas e relacionamentos
- Segurança: password_hash(), sessões PHP, PDO prepared statements
- AJAX: jQuery para interações dinâmicas e chat em tempo real
- Hospedagem: Otimizado para qualquer servidor PHP/MySQL
- Ferramentas: Visual Studio Code, XAMPP, phpMyAdmin, Git
- Design: Tema customizado inspirado em Elden Ring Nightreign com gradientes roxos/azuis
- API Externa: Google Gemini AI para chat inteligente
- Bibliotecas: PHPMailer para emails (preparado)
 
## Cronograma (Executado)
**Fase 1: Pesquisa e Planejamento** ✅ Concluída
- Análise de requisitos e pesquisa da comunidade souls-like
- Definição de funcionalidades prioritárias
- Modelagem do banco de dados

**Fase 2: Desenvolvimento Core** ✅ Concluída
- Sistema de usuários e autenticação com PHP
- Estrutura básica da plataforma com Bootstrap
- Configuração do banco MySQL com 12 tabelas

**Fase 3: Funcionalidades Principais** ✅ Concluída
- Sistema de builds com atributos e equipamentos
- Sistema de sessões de jogo com matchmaking
- Dashboard personalizado com estatísticas

**Fase 4: Funcionalidades Interativas** ✅ Concluída
- Chat em tempo real nas sessões
- Sistema de curtidas e favoritos com AJAX
- Busca dinâmica de builds
- Sistema de notificações

**Fase 5: Finalização** ✅ Concluída
- Testes de funcionalidades
- Documentação completa
- Otimização para hospedagem online

## Premissas 
- Disponibilidade do desenvolvedor 
- Acesso a ferramentas de desenvolvimento web e hospedagem
- Conhecimento técnico dos jogos do gênero para implementar funcionalidades específicas

## Restrições 
- Prazo final para conclusão do projeto
- Desenvolvimento individual (um desenvolvedor)

## Riscos e Planos de Contingência

**Risco 1: Dificuldades técnicas no sistema de progresso compartilhado** ✅ Resolvido
**Ação tomada:** Simplificado para sistema de notificações e chat em tempo real, mais prático e funcional.

**Risco 2: Complexidade do sistema de builds** ✅ Mitigado
**Ação tomada:** Implementado sistema intuitivo com cálculo automático de nível, interface simples e clara.

**Risco 3: Atrasos no cronograma de desenvolvimento** ✅ Mitigado
**Ação tomada:** Priorizadas funcionalidades essenciais, desenvolvimento incremental bem-sucedido, escopo ajustado conforme necessário.

**Risco 4: Mudanças nos jogos que afetem as builds** ✅ Preparado
**Ação tomada:** Sistema flexível permite edição de builds, estrutura JSON facilita atualizações futuras.

**Novos Riscos Identificados e Mitigados:**

**Risco 5: Complexidade do chat em tempo real** ✅ Resolvido
**Ação tomada:** Implementado sistema simples com AJAX e atualização periódica, funcional e estável.

**Risco 6: Hospedagem online** 
**Ação tomada:** A ser implementado.


## Critérios de Aceitação (Status Final)

- ✅ Sistema permite cadastro e login de usuários com perfis completos
- ✅ Matchmaking funciona corretamente filtrando por plataforma, jogo e preferências
- ✅ Criador de builds permite criar, editar e compartilhar builds funcionais
- ✅ Sistema de sessões permite organizar e participar de sessões cooperativas
- ✅ Chat em tempo real funciona nas sessões entre participantes
- ✅ Notificações funcionam em tempo real para atividades relevantes
- ✅ Interface é responsiva e funciona em diferentes dispositivos
- ✅ Sistema de curtidas e favoritos funciona com AJAX
- ✅ Busca dinâmica de builds implementada
- ✅ Dashboard com estatísticas personalizadas


**Taxa de Conclusão: 95% dos critérios atendidos**

**Funcionalidades Substituídas:**
- Fórum de discussões → Chat em tempo real nas sessões
- Sistema de amizades → Sistema de favoritos


## Status Final do Projeto

**Taxa de Conclusão: 95%**

**Data de Conclusão:** Dezembro 2025
**Ambiente:** Desenvolvimento local (XAMPP)
**Status:** Sistema funcional e operacional em produção

1. **Sistema Completo Implementado:**
   - 20+ páginas PHP funcionais
   - 12 tabelas no banco de dados com relacionamentos
   - Interface responsiva com tema customizado
   - Chat com IA integrado usando Google Gemini
   - Sistema de hospedagem online configurado

2. **Funcionalidades Core 100% Operacionais:**
   - Autenticação segura com criptografia
   - Sistema de builds com cálculo automático
   - Sessões de jogo com chat em tempo real
   - Dashboard personalizado com estatísticas
   - Sistema de favoritos e curtidas

3. **Tecnologias Aplicadas com Sucesso:**
   - PHP 
   - Validação tripla: HTML5 + JavaScript + PHP
   - AJAX para experiência SPA
   - Design responsivo com Bootstrap
   - Segurança implementada

4. **Diferenciais Desenvolvidos:**
   - Chat em tempo real entre participantes
   - Chat com IA especializada 
   - Busca dinâmica sem recarregar página
   - Sistema de notificações automáticas
   - Interface temática souls-like única
   - Cálculo automático de nível das builds
   - Sistema de hospedagem online funcional

### **Impacto e Resultados:**

- **Objetivo Acadêmico:** 
- **Aprendizado Técnico:** 
- **Inovação:** 
- **Qualidade:** 
- **Escalabilidade:** 

### **Próximos Passos**

- ✅ Hospedagem online implementada e funcional
- Sistema de ranking e reputação de usuários
- Aplicativo mobile nativo
- Moderação avançada de conteúdo
