@extends('adminlte::page')

@section('title', '- Changelog')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fa fa-fw fa-code"></i> Changelog</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Changelog</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Histórico do Sistema</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-6 order-2 order-md-1">
                        <div class="row">
                            <div class="col-12">
                                <h4>Versionamento:</h4>
                                <x-adminlte-callout theme="warning" class="bg-dark elevation-2" title-class="text-uppercase"
                                    title="1.0.3">
                                    <x-adminlte-profile-row-item text="2023-08-17" url="#" badge="warning" />
                                    <ul>
                                        <li>Implementação - Apresentação das ações executadas na visualização da operação
                                        </li>
                                        <li>Implementação - Listagem das operações em andamento
                                        </li>
                                    </ul>
                                </x-adminlte-callout>

                                <x-adminlte-callout theme="warning" class="bg-dark elevation-2" title-class="text-uppercase"
                                    title="1.0.2">
                                    <x-adminlte-profile-row-item text="2023-08-16" url="#" badge="warning" />
                                    <ul>
                                        <li>Modificação - Alteração da visão de visitantes no banco de dados para otimização
                                            de performance</li>
                                    </ul>
                                </x-adminlte-callout>

                                <x-adminlte-callout theme="warning" class="bg-dark elevation-2" title-class="text-uppercase"
                                    title="1.0.1">
                                    <x-adminlte-profile-row-item text="2023-08-11" url="#" badge="warning" />
                                    <ul>
                                        <li>Modificação - Removida a inserção de imagem no campo "descrição" do formulário
                                            de cadastro de
                                            ferramentas</li>
                                    </ul>
                                </x-adminlte-callout>

                                <x-adminlte-callout theme="warning" class="bg-dark elevation-2" title-class="text-uppercase"
                                    title="1.0.0">
                                    <x-adminlte-profile-row-item text="2023-07-28" url="#" badge="warning" />
                                    <ul>
                                        <li>Implementação - Módulo de ACL com perfis e permissões</li>
                                        <li>Implementação - Módulo de Organizações</li>
                                        <li>Implementação - Módulo de Fases</li>
                                        <li>Implementação - Módulo de Usuários</li>
                                        <li>Implementação - Módulo de Equipes</li>
                                        <li>Implementação - Módulo de Operações com timeline e Kanban</li>
                                        <li>Implementação - Módulo de Ferramentas</li>
                                        <li>Implementação - Módulo de Relatórios</li>
                                        <li>Implementação - Changelog</li>
                                    </ul>
                                </x-adminlte-callout>

                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-6 order-1 order-md-2">

                        <x-adminlte-profile-widget name="SIOC" desc="Sistema de Informações de Operações Cibernéticas"
                            theme="bg-gradient-dark" img="{{ asset('img/logo.png') }}" layout-type="classic">
                            <x-adminlte-profile-row-item
                                title="Alberto da Costa Ramos Bezerra, e-mail: alberto.bezerra@marinha.mil.br"
                                class="text-left border-bottom border-secondary" />
                            <x-adminlte-profile-row-item
                                title="Rodrigo Carvalho de Brito, e-mail: brito.carvalho@marinha.mil.com.br"
                                class="text-left border-bottom border-secondary" />
                            <x-adminlte-profile-row-item title="Vivian Vaz Nascimento, e-mail: vivian.vaz@marinha.mil.br"
                                class="text-left border-bottom border-secondary" />
                            <x-adminlte-profile-row-item title="Rodrigo Luis de Assis, e-mail: r.assis@marinha.mil.br"
                                class="text-left border-bottom border-secondary" />
                            <x-adminlte-profile-col-item title="Javascript" icon="fab fa-2x fa-js text-yellow" size=4 />
                            <x-adminlte-profile-col-item title="PHP" icon="fab fa-2x fa-php text-yellow" ssize=4 />
                            <x-adminlte-profile-col-item title="HTML5" icon="fab fa-2x fa-html5 text-yellow" size=4 />
                            <x-adminlte-profile-col-item title="CSS3" icon="fab fa-2x fa-css3 text-yellow" size=4 />
                            <x-adminlte-profile-col-item title="Bootstrap" icon="fab fa-2x fa-bootstrap text-yellow"
                                size=4 />
                            <x-adminlte-profile-col-item title="Laravel" icon="fab fa-2x fa-laravel text-yellow" size=4 />
                        </x-adminlte-profile-widget>

                        <p class="text-muted">
                            O projeto desenvolvido tem como propósito estabelecer padrões das
                            atividades desenvolvidas, melhorar a comunicação dos colaboradores em
                            ações cibernéticas conjuntas, centralizar relatórios dos serviços
                            realizados e aperfeiçoar continuamente o processo, que são vantagens
                            adquiridas com a utilização de um sistema de controle web. Além disso,
                            incentiva às boas práticas de gestão, definindo os objetivos vinculados as
                            fases de um ataque cibernético, que possibilitam o aperfeiçoamento dos
                            processos e procedimentos institucionais, consolidados em um Sistema de
                            Informações de Operações Cibernéticas - SIOC.
                        </p>
                        <p class="text-muted">
                            Tendo em vista a ausência de ferramentas para controle e monitoramento das
                            operações de ataque cibernético, que podem gerar ações sem padrão de
                            processos definidos e com baixa integração dos componentes de equipe,
                            ainda que em modo de cooperação, bem como uma menor capacidade de
                            monitoramento e avaliação das ações executadas.
                        </p>
                        <p class="text-muted">
                            Para resolver esse cenário avaliou-se ser interessante desenvolver um
                            sistema web para gerir as ações de ataque cibernético podendo definir
                            perfis e papéis dos participantes, cadastrar times, estágios das ações e
                            suas ramificações, exibir a linha do tempo das ações para avaliação e
                            <span class="s2">dashboard </span>com os status das operações e
                            informações estatísticas.
                        </p>
                        <p class="text-muted">
                            O objetivo inicial será o processo de modelagem das fases no sistema e o
                            que cada uma terá como propriedades necessárias para uma correta execução,
                            controle e avaliação, de modo a permitir uma maior sinergia entre os
                            guerreiros cibernéticos para a execução de ataques da forma mais otimizada
                            possível, trocando a individualidade das ações, por uma ação coletiva
                            eficaz e eficiente, da mesma forma que operações militares convencionais
                            são executadas.
                        </p>
                        <p class="text-muted">
                            O módulo de Operações permitirá o estudo sobre o que é crucial para o
                            andamento das operações cibernéticas ofensivas: alvo, estado, componentes,
                            início, término, criticidade, dificuldade, técnicas empregadas dentre
                            outras possíveis propriedades, como informações de planejamento, tais como
                            análise situacional, objetivo da missão, logística e Comando e Controle.
                            Além das propriedades, as operações poderão ser vinculadas às Organizações
                            envolvidas, civis ou militares, e a times com capacidades específicas:
                            Proteção, Ataque e Exploração Cibernética.
                        </p>
                        <p class="text-muted">
                            A metodologia do ataque cibernético, para elaboração do sistema e dos
                            módulos, pode ser dividida em 10 fases: Planejamento; Reconhecimento e
                            Coleta de
                        </p>
                        <p class="text-muted">
                            informações; Enumeração dos Serviços e Busca de Vulnerabilidades; Análise
                            das Vulnerabilidades; Exploração e Obtenção de Acesso; Escalação de
                            Privilégios, Exfiltração; Assalto; Sustentação; Ofuscação; Documentação e
                            Relatório.
                        </p>
                        <p class="text-muted">
                            Estas fases foram centralizadas em um módulo, para que possam ser
                            gerenciadas prevendo alterações e evoluções de metodologia na estrutura de
                            ataque utilizada como referência, podendo seguir modelos civis, tais como
                            OWASP, Cyber Kill Chain, Mittre etc ou modelos militares personalizados.
                        </p>
                        <p class="text-muted">
                            O Planejamento, que definirá o escopo, quais serão os alvos, metodologias
                            utilizadas, os objetivos do teste e qual modalidade de pentest adotada, se externa,
                            simulando um ataque cibernético sem
                            nenhuma informação prévia do alvo, ou interna, tendo informações relevantes sobre o ambiente
                            tecnológico da
                            empresa.
                        </p>
                        <p class="text-muted">
                            Como a guerra convencional, os ataques cibernéticos bem-sucedidos começam
                            com a coleta de informações. O reconhecimento sobre o Espaço Cibernético
                            alvo ou obtenção de dados nele existentes, é o primeiro passo na
                            exploração, pois com essas informações, poderão surgir possíveis
                            alternativas de ataques. Segundo o manual de Doutrina Cibernética da
                            Marinha do Brasil, o reconhecimento pode ser dividido em três categorias:
                            Inteligência de Fontes Abertas, em inglês Open Source Intelligence (OSINT), Reconhecimento
                            Não-Intrusivo e Reconhecimento Intrusivo.
                        </p>
                        <p class="text-muted">
                            Depois de coletar informações suficientes sobre o alvo, inicia-se a etapa
                            de análise dos serviços que estão sendo executados e também das portas de
                            acesso para o sistema. Com isso, deve-se escolher um ou vários vetores de
                            ataque para realizar a invasão.
                        </p>
                        <p class="text-muted">
                            Um vetor de ataque, ou vetor de ameaça, é uma maneira de os invasores entrarem em uma rede
                            ou
                            sistema. Os vetores de ataque comuns
                            incluem ataques de engenharia social, roubo de credenciais, explorações de
                            vulnerabilidades e proteção insuficiente contra ameaças
                            internas.
                        </p>
                        <p class="text-muted">
                            Em posse dos vetores averiguados, o atacante deve criar exploits ou procurar
                            vulnerabilidades
                            conhecidas
                            das aplicações na internet, alguns sites dão bastante suporte para essa
                            fase, como: exploit-db.com, github.com, cve.mitre.org e nvd.nist.gov. Para
                            obter acesso, o profissional vai explorar cada vulnerabilidade encontrada,
                            até realizar o acesso. Para isso, pode-se utilizar inúmeras ferramentas e
                            métodos que dependem de tempo e discrição para sua escolha.
                        </p>
                        <p class="text-muted">
                            De acordo com Hackersec, uma vez que o acesso aos softwares, sistemas e
                            redes do oponente foi conseguido, o ataque continuará para avaliar qual a
                            extensão das vulnerabilidades. Ou seja, se a partir da falha inicial é
                            possível obter acesso a outras partes do ambiente tecnológico da rede.
                        </p>
                        <p class="text-muted">
                            Para a exfiltração, se entre as tarefas a serem realizadas no
                            planejamento, abarcar obter informações de valor do oponente, buscas por
                            essas informações serão realizadas e extraídas do sistema oponente.
                        </p>
                        <p class="text-muted">
                            De acordo com Marinha do Brasil, ao concluir as fases anteriores, o
                            planejamento da ação pode determinar a obtenção de informações de valor do
                            oponente, com isso, buscas por essas serão realizadas e extraídas; poderá
                            determinar o uso do sistema sob controle para promover instabilidade,
                            desinformação e caos no ambiente operacional oponente; ou poderão ser
                            inseridos usuários ou vulnerabilidades no sistema, que evitarão reiniciar
                            as fases anteriores num posterior acesso.
                        </p>
                        <p class="text-muted">
                            Após o planejamento, nas 9 fases do ataque cibernético, serão apagadas ou
                            encobertas evidências da exploração ou invasão, podendo reconfigurar os
                            dados de forma que despistem a sua autoria, deixando o adversário confuso,
                            sem saber as ações que estão sendo realizadas.
                        </p>
                        <p class="text-muted">
                            Depois de ter identificado e coletado as vulnerabilidades do sistema, o
                            sistema de controle de ações ofensivas gerará um relatório expondo todos
                            esses pontos vulneráveis, seus graus de criticidade, os erros de segurança
                            e um plano de ação para correção das falhas.
                        </p>
                        <p class="text-muted">
                            As ações efetuadas nas operações pelos componentes do time serão
                            compartilhadas para que seja possível otimizar as ações, evitando
                            repetição de processos já realizados por algum integrante da equipe. A
                            cada evolução das fases da operação, os elementos gestores da equipe
                            poderão representar graficamente no sistema este andamento, por meio de
                            cartões do tipo Kanban, que consiste em quadros de sinalização para
                            controle dos fluxos da operação, que representam dinamicamente as fases de
                            cada operação de forma sequencial. As alterações das fases ficarão
                            registradas na linha do tempo da operação, com dados temporais e autores.
                        </p>
                        <p class="text-muted">
                            A pesquisa se limitou quanto a viabilidade da utilização do sistema na
                            inserção de dados em um ambiente simulado. A instalação da ferramenta,
                            devido ao teor sigiloso das operações deverá estar em um ambiente de
                            acesso restrito, podendo ser intranet, Rede Virtual Privada (VPN) ou em
                            servidores virtualizados temporários para uma operação ou exercício.
                        </p>
                        <p class="text-muted">
                            Na versão inicial do sistema os recursos disponibilizados são os
                            seguintes: Lista de Controle de Acesso, para gestão de papéis e perfis
                            para usuários, devendo ser manipulado apenas por usuários que
                            administrarão o sistema de forma avançada, tais como programadores. Possui
                            módulo de gestão de perfis, para criar grupos de funções comuns, e módulo
                            de permissões, que contém a ação que será atribuída a cada tipo e
                            gerenciará aspectos visuais, tais como renderização de botões, controle de
                            acesso e execução de tipos de requisição.
                        </p>
                        <p class="text-muted">
                            Cadastro de Organizações, civis ou militares, para vincular aos usuários e
                            às operações, podendo formar uma cadeia de subordinação.
                        </p>
                        <p class="text-muted">
                            Módulo de fases, que serão representadas como cartões Kanban na operação
                            ao qual estarão integradas obrigatoriamente.
                        </p>
                        <p class="text-muted">
                            Controle de Usuários, para cadastro e definição de perfil, inicialmente
                            modelados como Programador com funções de sistema, Administrador com
                            funções de gerências globais, Coordenador com funções de gestão de
                            operações e Guerreiro Cibernético com função de executor de ações na
                            operação em que estiver vinculado a algum time.
                        </p>
                        <p class="text-muted">
                            Módulo de equipes, onde serão montados os times de acordo com as
                            capacidades cibernéticas de proteção, ataque e exploração e elencados seus
                            componentes, que serão os usuários cadastrados no sistema no módulo de
                            usuários.
                        </p>
                        <p class="text-muted">
                            Módulo de Operações, escopo principal do sistema, com visão segregada para
                            cada time e organização, com os dados operacionais, execução de linha do
                            tempo a cada alteração efetuada, gestão de fases por cartões Kanban e
                            controle de ações individuais dos componentes dos times vinculados à
                            operação.
                        </p>
                        <p class="text-muted">
                            Cadastro de relatórios, de visualização global ou individualizada para
                            apresentar passo a passo de execução de ações em máquinas de laboratórios
                            ou Capture the Flag (CTF) para servir como fonte
                            de consulta e referência própria ou coletiva.
                        </p>
                        <p class="text-muted">
                            Módulo de Ferramentas para promulgação de conhecimento, programas ou
                            scripts úteis para as ações operativas cibernéticas.
                        </p>
                        <p class="text-muted">
                            Como módulo auxiliar, o chat foi desenvolvido para permitir a integração
                            entre os utilizadores do sistema.
                        </p>
                        <p class="text-muted">
                            Todas as requisições, contendo dados de IP, cabeçalhos e dados de
                            requisição, usuários e horários de acesso ficam registrados no banco de
                            dados por meio de um módulo de registro de log integrado ao núcleo do
                            sistema, podendo ser auditado via banco de dados.
                        </p>
                        <p class="text-muted">
                            O sistema apresentado tem como relevância para o ambiente militar a
                            possibilidade de padronização e uniformização de Procedimentos Táticas e
                            Técnicas (PTT) em um ambiente com possibilidade de alta disponibilidade e
                            integração, além de permitir o armazenamento dos dados para análise e
                            acesso, em uma ferramenta de baixo custo de implementação, de código
                            aberto, livre de licenças comerciais e com capacidade de adaptabilidade e
                            evolução. Há a possibilidade de personalização e integração de novos
                            módulos para tarefas de outros escopos dentro do âmbito cibernético, como
                            teste de intrusão em ambiente corporativo e gestão de ações defensivas.
                        </p>
                        <p class="text-muted">
                            Os riscos que se aplicam ao sistema se concentram no ambiente de uso, onde
                            a segurança e sigilo talvez não seja aplicável a uma ferramenta de
                            armazenamento e apresentação de dados sensíveis. Contudo, há métodos de
                            segurança aplicáveis à infraestrutura que abrigará o sistema, tal como
                            banco de dados ou sistema operacional criptografados por exemplo, além da
                            gestão de usuários credenciados que não foi modelada nesta primeira versão
                            do sistema.
                        </p>
                        <p class="text-muted">
                            Como uma ferramenta auxiliar para a execução de ações no âmbito de
                            operações cibernéticas, o resultado esperado em sua utilização é o aumento
                            da
                        </p>
                        <p class="text-muted">
                            capacidade de gestão, coordenação e execução da operação e registro de
                            dados em caráter histórico, que podem servir como base para coleta de
                            dados estatísticos e estudo de PTT empregados. Para os operadores,
                            guerreiros cibernéticos, pode ser uma ferramenta valiosa para integração,
                            compartilhamento de conhecimento, e principalmente entendimento do caráter
                            corporativo dentro de um contexto militar das tarefas em execução, onde o
                            que deve prevalecer é o alcance do objetivo da missão alcançados
                            integrando os esforços dos membros da equipe, desprezando alcances
                            individuais.
                        </p>
                        <p class="text-muted">
                            O sistema em lide apresenta grande capacidade de evolução e integração de
                            funcionalidades com uma equipe singela de desenvolvimento. Dentre as
                            possibilidades modeladas e não implementadas na primeira versão do sistema
                            estão: módulo de gestão de tarefas individualizadas para os membros da
                            equipe dentro de uma operação específica; agenda de eventos; e impressão
                            de relatórios de cada operação. Com a utilização do sistema, novas
                            possibilidades serão evidenciadas, bem como necessidades de modificações
                            ou correções, dentro da normalidade de sistemas informatizados, que poderá
                            ser atendida diante do ferramental utilizado para o desenvolvimento do
                            sistema, dentre os quais se destacam a linguagem utilizada no back-end, PHP utilizando o
                            framework Laravel, que
                            apresenta uma baixa curva de aprendizagem e utilização consolidada no
                            mercado, recursos no front-end básicos, somente
                            JavaScript, SCSS e jQuery e banco de dados permutável, com a utilização de
                            arquivos de migração de banco, que se adapta aos sistemas gerenciadores de
                            bancos de dados mais utilizados.
                        </p>
                        <h5 class="mt-5 text-muted">Referências Blibiográficas</h5>
                        <ul class="list-unstyled">
                            <li>
                                <a href="https://www.ema.mb" target="_blank" class="btn-link text-secondary">MARINHA
                                    DO
                                    BRASIL. Estado-Maior da Armada.
                                    Doutrina Cibernética da Marinha:
                                    EMA-419. Brasília: EMA, 2021. Disponível em: https://www.ema.mb. Acesso em: 05 jun
                                    2023.
                                </a>
                            </li>
                            <li>
                                <a href="https://hackersec.com/como-fazer-um-pentest/" target="_blank"
                                    class="btn-link text-secondary">HackerSec;
                                    Disponível em: https://hackersec.com/como-fazer-um-pentest/; Acesso
                                    em: 01 jul 2023.
                                </a>
                            </li>
                            <li>
                                <a href="https://www.cloudflare.com/pt-br/learning/security/glossary/attack-vector/"
                                    target="_blank" class="btn-link text-secondary">Cloudflare; Disponível em:
                                    https://www.cloudflare.com/pt-br/learning/security/glossary/attack-vector/
                                    ; Acesso em: 01 jul 2023.
                                </a>
                            </li>
                            <li>
                                <a href="https://www.slcyber.io/shifting-left-in-the-cyber-kill-chain/" target="_blank"
                                    class="btn-link text-secondary">Robert
                                    Fitzsimons; Disponível em: https://www.slcyber.io/shifting-left-in-the-cyber-
                                    kill-chain/; Acesso em: 04 jul 2023.
                                </a>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>

        </div>

    </section>
@endsection
