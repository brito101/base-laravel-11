<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StepsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('steps')->insert([
            [
                'name'      => 'Planejamento',
                'description' => 'Consiste em definir o escopo, quais serão os alvos, metodologias, os objetivos e qual modalidade adotada',
                'color'  => '#f8f9fa',
                'sequence' => 1,
                'created_at' => new DateTime('now')
            ],
            [
                'name'      => 'Reconhecimento e Coleta de Informações',
                'description' => 'Levantamento de informações sobre o oponente. No reconhecimento, a ExpCiber é empregada a fim de obter informações privilegiadas sem alertar o adversário, permitindo, assim, efetuar um mapeamento do alvo',
                'color'  => '#17a2b8',
                'sequence' => 2,
                'created_at' => new DateTime('now')
            ],
            [
                'name'      => 'Busca e Análise de Vulnerabilidades',
                'description' => 'Busca por potenciais vulnerabilidades no espaço cibernético de interesse. Pesquisas mais profundas são feitas no Ambiente Operacional e nas diversas aplicações, visando encontrar brechas de segurança, decorrentes da não correção de falhas conhecidas/divulgadas ou da identificação daquelas ainda não divulgadas por seus desenvolvedores.',
                'color'  => '#007bff',
                'sequence' => 3,
                'created_at' => new DateTime('now')
            ],
            [
                'name'      => 'Acesso',
                'description' => 'Pode ser obtido por uma ampla variedade de ferramentas e métodos que dependem de tempo e discrição para sua escolha, por emprego direto de uma credenciais lícitas, uebra de senhas por força bruta (maior tempo para sua execução, que contribui para aumentar a probabilidade de detecção) ou exploração de vulnerabilidades para obter acesso a equipamentos de usuários do espaço cibernético alvo, visando empregar credenciais necessárias para acesso aos demais subsistemas que se encontram em níveis de segurança mais elevados.',
                'color'  => '#28a745',
                'sequence' => 4,
                'created_at' => new DateTime('now')
            ],
            [
                'name'      => 'Escalada de Privilégios',
                'description' => 'Após o acesso, é necessário ganhar mais privilégios a fim de obter amplos direitos de administrador do sistema alvo. Diferentes técnicas são utilizadas para adquirir tais direitos, destacando-se a exploração de falhas de aplicações ( software) em execução, falhas de configuração (firmware) do equipamento acessado e falhas nas configurações de aplicativos.',
                'color'  => '#ffc107',
                'sequence' => 5,
                'created_at' => new DateTime('now')
            ],
            [
                'name'      => 'Exfiltração',
                'description' => 'Se a captura de dados estiver no conjunto de tarefas necessárias ao cumprimento da missão, iniciam-se buscas por informações de valor, que devem ser extraídas do sistema alvo.',
                'color'  => '#ff851b',
                'sequence' => 6,
                'created_at' => new DateTime('now')
            ],
            [
                'name'      => 'Assalto',
                'description' => 'o planejamento da ação pode determinar o uso do sistema sob controle para promover instabilidade, desinformação e caos no Ambiente Operacional oponente. Adotam-se ações necessárias para interromper ou negar o acesso do adversário à sua própria Informação; degradar, corromper ou destruir as informações armazenadas em dispositivos computacionais ou em redes de computadores de interesse.',
                'color'  => '#dc3545',
                'sequence' => 7,
                'created_at' => new DateTime('now')
            ],
            [
                'name'      => 'Sustentação',
                'description' => 'Caso esteja definido no planejamento, poderão ser feitas configurações no sistema alvo sob controle a fim de permitir novos acessos no futuro, evitando-se reiniciar as fases anteriores.',
                'color'  => '#3d9970',
                'sequence' => 8,
                'created_at' => new DateTime('now')
            ],
            [
                'name'      => 'Ofuscação',
                'description' => 'Em todas as fases do ataque cibernético, executam-se técnicas, táticas e procedimentos para encobrir ou apagar evidências da invasão ou atribuindo sua autoria a outrem, a fim de permanecer oculto ou confundir o adversário a respeito das ações em andamento.',
                'color'  => '#6c757d',
                'sequence' => 9,
                'created_at' => new DateTime('now')
            ],
            [
                'name'      => 'Documentação e Relatório',
                'description' => 'Fase da confeccção de documentação para tramitação e arquivamanento caso necessário.',
                'color'  => '#36cccc',
                'sequence' => 10,
                'created_at' => new DateTime('now')
            ],
        ]);
    }
}
