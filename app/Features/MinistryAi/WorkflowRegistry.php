<?php

declare(strict_types=1);

namespace App\Features\MinistryAi;

final class WorkflowRegistry
{
    public static function modules(): array
    {
        return [
            [
                'id' => 'planejamento',
                'title' => 'Planejamento Ministerial',
                'description' => 'Curriculos, discipulados, lideranca, pequenos grupos e plano anual.',
            ],
            [
                'id' => 'pregacao',
                'title' => 'Pregacao',
                'description' => 'Sermoes, rascunhos, cultos ocasionais e aulas EBD.',
            ],
            [
                'id' => 'estudos',
                'title' => 'Estudos Biblicos',
                'description' => 'Estudos exegeticos, tematicos, confessionais e devocionais.',
            ],
        ];
    }

    public static function workflows(): array
    {
        return [
            'series_sermoes' => [
                'module' => 'planejamento',
                'title' => 'Series de Sermoes',
                'description' => 'Planeje uma serie com progressao pastoral, textos e mensagens.',
                'icon' => 'mic',
                'accent' => '#D97706',
                'fields' => [
                    self::text('series_theme', 'Tema da serie', true, 'Ex.: As promessas de Deus em tempos dificeis'),
                    self::select('message_count', 'Numero de mensagens', true, [['4', '4 mensagens'], ['6', '6 mensagens'], ['8', '8 mensagens'], ['10', '10 mensagens'], ['12', '12 mensagens']]),
                    self::select('audience', 'Publico-alvo', true, self::audienceOptions()),
                    self::select('series_emphasis', 'Enfase da serie', true, self::pastoralEmphasisOptions()),
                ],
            ],
            'curriculo_escola_dominical' => [
                'module' => 'planejamento',
                'title' => 'Curriculo de Escola Dominical',
                'description' => 'Crie uma serie pedagogica para classes e trilhas de formacao.',
                'icon' => 'book',
                'accent' => '#5B21B6',
                'fields' => [
                    self::select('creation_mode', 'Modo de criacao', true, [['trilhas_sugeridas', 'Trilhas sugeridas'], ['criar_do_zero', 'Criar do zero']]),
                    self::select('audience', 'Publico-alvo', true, [['adolescentes', 'Adolescentes'], ['jovens', 'Jovens'], ['casais', 'Casais'], ['adultos', 'Adultos'], ['catecumenos', 'Catecumenos'], ['novos_membros', 'Novos membros']]),
                    self::select('suggested_track', 'Trilha sugerida', false, [['solas_reforma', 'Solas da Reforma'], ['panorama_biblico', 'Panorama Biblico'], ['evangelho_joao', 'Evangelho de Joao'], ['doutrina_salvacao', 'Doutrina da Salvacao'], ['vida_crista_reformada', 'Vida Crista Reformada'], ['catecismo_comentado', 'Catecismo Comentado'], ['igreja_sacramentos', 'Igreja e Sacramentos'], ['cosmovisao_crista', 'Cosmovisao Crista']]) + ['condition' => ['field' => 'creation_mode', 'equals' => 'trilhas_sugeridas']],
                    self::text('series_theme', 'Tema da serie', false, 'Quando criar do zero') + ['condition' => ['field' => 'creation_mode', 'equals' => 'criar_do_zero']],
                    self::text('pedagogical_goal', 'Objetivo pedagogico', true, 'O que a turma deve saber, crer e praticar?'),
                    self::textarea('class_need', 'Necessidade especifica da turma', false, 'Opcional') + ['condition' => ['field' => 'creation_mode', 'equals' => 'criar_do_zero']],
                    self::select('lesson_count', 'Quantidade de licoes', true, [['4', '4'], ['6', '6'], ['8', '8'], ['10', '10'], ['12', '12'], ['13', '13']]),
                    self::select('confessional_layer', 'Camada confessional', true, self::confessionalLayerOptions()),
                ],
            ],
            'roteiro_discipulado' => [
                'module' => 'planejamento',
                'title' => 'Roteiro de Discipulado',
                'description' => 'Gere uma trilha de 12 encontros para acompanhamento espiritual.',
                'icon' => 'users',
                'accent' => '#059669',
                'fields' => [
                    self::select('spiritual_profile', 'Perfil espiritual', true, [['novo_convertido', 'Novo convertido'], ['catolicismo', 'Vindo do catolicismo'], ['pentecostalismo', 'Vindo do pentecostalismo'], ['evangelico_tradicional', 'Evangelico tradicional'], ['contexto_reformado', 'Contexto reformado'], ['membro_antigo', 'Membro antigo'], ['retornando_fe', 'Retornando a fe']]),
                    self::select('age_group', 'Faixa etaria', true, [['adolescente', 'Adolescente'], ['jovem', 'Jovem'], ['adulto', 'Adulto'], ['maduro_idoso', 'Maduro / idoso']]),
                    self::select('biblical_level', 'Nivel biblico', true, [['basico', 'Basico'], ['intermediario', 'Intermediario'], ['avancado', 'Avancado']]),
                    self::select('main_goal', 'Objetivo principal', true, [['fundamentos_fe', 'Fundamentos da fe'], ['integracao_igreja', 'Integracao na igreja'], ['profissao_membresia', 'Profissao de fe / membresia'], ['formacao_lideranca', 'Formacao para lideranca'], ['restauracao_espiritual', 'Restauracao espiritual']]),
                    self::select('frequency', 'Frequencia', true, [['semanal', 'Semanal'], ['quinzenal', 'Quinzenal'], ['mensal', 'Mensal']]),
                    self::select('meeting_duration', 'Duracao do encontro', true, [['45', '45 minutos'], ['60', '60 minutos'], ['75', '75 minutos'], ['90', '90 minutos']]),
                    self::select('environment', 'Ambiente', true, [['casa', 'Casa'], ['igreja', 'Igreja'], ['cafe', 'Cafe'], ['online', 'Online']]),
                    self::select('confessional_layer', 'Camada confessional', true, self::confessionalLayerOptions()),
                    self::textarea('discipler_notes', 'Observacoes do discipulador', false, 'Opcional'),
                ],
            ],
            'discipulado_casais' => [
                'module' => 'planejamento',
                'title' => 'Discipulado de Casais',
                'description' => 'Prepare uma trilha de 7 encontros para noivos ou casais.',
                'icon' => 'heart',
                'accent' => '#DB2777',
                'insights' => [
                    ['title' => 'Estrutura fixa - 7 encontros', 'items' => ['Alianca e temor do Senhor', 'Amor, comunicacao e servico', 'Familia de origem e modelos familiares', 'Papeis, funcoes e responsabilidade', 'Financas e acordos conjugais', 'Conflitos, perdao e renovacao', 'Sexualidade e intimidade conjugal']],
                ],
                'fields' => [
                    self::select('couples_track', 'Sugestao de trilha', true, [['estrutura_7', 'Estrutura fixa - 7 encontros'], ['noivos', 'Preparacao para noivos'], ['casais_novos', 'Casais no inicio da caminhada'], ['restauracao', 'Restauracao e renovacao conjugal']]),
                    self::textarea('couple_context', 'Contexto do casal', true, 'Descreva o contexto com cuidado pastoral.'),
                    self::select('meeting_duration', 'Duracao do encontro', true, [['60', '60 minutos'], ['75', '75 minutos'], ['90', '90 minutos']]),
                    self::select('environment', 'Ambiente', true, [['sala_igreja', 'Sala na igreja'], ['casa_pastoral', 'Casa pastoral'], ['casa_casal', 'Casa do casal'], ['online', 'Online']]),
                    self::select('confessional_layer', 'Camada confessional', true, self::confessionalLayerOptions()),
                ],
            ],
            'treinamento_lideranca' => [
                'module' => 'planejamento',
                'title' => 'Treinamento de Lideranca',
                'description' => 'Monte uma trilha para formacao de lideres e equipes ministeriais.',
                'icon' => 'award',
                'accent' => '#0D9488',
                'fields' => [
                    self::select('training_type', 'Tipo de treinamento', true, [['base_geral', 'Base geral'], ['presbiteros', 'Formacao de presbiteros'], ['diaconos', 'Formacao de diaconos'], ['jovens', 'Lideres de jovens'], ['professores_ebd', 'Professores de EBD'], ['pequenos_grupos', 'Lideres de pequenos grupos']]),
                    self::select('member_count', 'Numero aproximado de membros', true, [['ate_50', 'Ate 50 membros'], ['50_150', '50 a 150 membros'], ['150_500', '150 a 500 membros'], ['500_plus', 'Acima de 500 membros']]),
                    self::select('church_moment', 'Momento atual da igreja', true, [['rotina_ministerial', 'Rotina ministerial'], ['eleicao_proxima', 'Eleicao proxima'], ['crescimento', 'Fase de crescimento'], ['conflito', 'Conflito interno'], ['reorganizacao', 'Reorganizacao'], ['outra', 'Outra']]),
                    self::select('denominational_base', 'Base denominacional', true, [['presbiteriana', 'Presbiteriana'], ['batista_reformada', 'Batista reformada'], ['independente_reformada', 'Independente reformada'], ['outra', 'Outra']]),
                    self::select('depth', 'Profundidade', true, [['pastoral', 'Pastoral'], ['estruturada', 'Estruturada']]),
                    self::select('confessional_layer', 'Camada confessional', true, self::confessionalLayerOptions()),
                    self::textarea('pastor_notes', 'Observacoes do pastor', false, 'Opcional'),
                ],
            ],
            'pequenos_grupos' => [
                'module' => 'planejamento',
                'title' => 'Planejamento de Pequenos Grupos',
                'description' => 'Crie ciclos com encontros, perguntas, aplicacao e envio.',
                'icon' => 'network',
                'accent' => '#7C3AED',
                'insights' => [
                    ['title' => 'Estrutura do ciclo', 'items' => ['Encontro 1 - Introducao', 'Encontros 2 a 6 - Desenvolvimento', 'Encontro 7 - Consolidacao', 'Encontro 8 - Envio']],
                ],
                'fields' => [
                    self::textarea('group_context', 'Visao e contexto do grupo', true, 'Descreva visao, publico, momento e necessidade.'),
                    self::select('meeting_count', 'Numero de encontros', true, [['6', '6 encontros'], ['8', '8 encontros (padrao)'], ['10', '10 encontros']]),
                    self::select('meeting_duration', 'Duracao por encontro', true, [['60', '60 minutos'], ['75', '75 minutos'], ['90', '90 minutos'], ['120', '120 minutos']]),
                    self::select('frequency', 'Frequencia', true, [['semanal', 'Semanal'], ['quinzenal', 'Quinzenal'], ['mensal', 'Mensal']]),
                    self::select('confessional_layer', 'Camada confessional', true, self::confessionalLayerOptions()),
                ],
            ],
            'plano_anual_igreja' => [
                'module' => 'planejamento',
                'title' => 'Plano Anual da Igreja',
                'description' => 'Estruture discernimento pastoral, pilares e acoes para o ano.',
                'icon' => 'target',
                'accent' => '#F59E0B',
                'insights' => [
                    ['title' => 'Processo em duas etapas', 'items' => ['Etapa 1 - Esboco macro: sintese pastoral, tema norteador, 4 a 6 pilares e distribuicao anual', 'Etapa 2 - Cinco secoes: diagnostico, tema, pilares, diretrizes e indicadores']],
                ],
                'fields' => [
                    self::textarea('pastoral_context', 'Visao e contexto pastoral', true, 'Descreva momento atual, desafios, oportunidades e direcao percebida.'),
                    self::text('reference_year', 'Ano de referencia', true, (string) ((int) date('Y') + 1)),
                    self::select('denominational_base', 'Base denominacional', true, [['presbiteriana', 'Presbiteriana'], ['batista_reformada', 'Batista reformada'], ['independente_reformada', 'Independente reformada'], ['outra', 'Outra']]),
                    self::select('confessional_layer', 'Camada confessional', true, self::confessionalLayerOptions()),
                ],
            ],
            'gerar_sermao' => [
                'module' => 'pregacao',
                'title' => 'Gerar Sermao',
                'description' => 'Monte um sermao estruturado a partir de texto, tema e contexto pastoral.',
                'icon' => 'pen',
                'accent' => '#0A4DFF',
                'fields' => [
                    self::select('sermon_type', 'Tipo de sermao', true, [['expositivo', 'Expositivo'], ['textual', 'Textual'], ['tematico', 'Tematico'], ['biografico', 'Biografico']]),
                    self::select('homiletic_structure', 'Estrutura homiletica', true, self::homileticOptions()),
                    self::text('main_passage', 'Passagem biblica principal', true, 'Ex.: Efesios 2:1-10'),
                    self::text('central_theme', 'Tema central do sermao', true, 'Ex.: A soberania de Deus na salvacao'),
                    self::text('biblical_character', 'Personagem biblico', false, 'Opcional'),
                    self::select('pastoral_emphasis', 'Enfase pastoral', true, self::pastoralEmphasisOptions()),
                    self::select('audience', 'Publico-alvo', true, self::audienceOptions()),
                    self::select('duration_minutes', 'Duracao em minutos', true, [['25', '25'], ['35', '35'], ['45', '45'], ['60', '60']]),
                    self::select('bible_version', 'Versao biblica', true, self::bibleVersionOptions()),
                    self::select('application_style', 'Estilo de aplicacao', true, [['ao_final', 'Ao final'], ['por_ponto', 'Ao longo dos pontos'], ['misto', 'Misto']]),
                    self::select('include_reformed_quotes', 'Incluir citacoes reformadas', true, [['sim', 'Incluir'], ['nao', 'Nao incluir']]),
                    self::select('include_illustrations', 'Incluir ilustracoes', true, [['sim', 'Incluir'], ['nao', 'Nao incluir']]),
                ],
            ],
            'refinar_rascunho' => [
                'module' => 'pregacao',
                'title' => 'Refinar Rascunho',
                'description' => 'Reorganize e fortaleca um rascunho sem apagar a voz do pastor.',
                'icon' => 'spark',
                'accent' => '#7C3AED',
                'fields' => [
                    self::textarea('draft', 'Rascunho do sermao', true, 'Cole ou digite aqui o rascunho...'),
                    self::select('homiletic_structure', 'Estrutura homiletica', true, self::homileticOptions()),
                    self::select('sermon_type', 'Tipo de sermao', true, [['expositivo', 'Expositivo'], ['textual', 'Textual'], ['tematico', 'Tematico'], ['biografico', 'Biografico']]),
                    self::text('main_passage', 'Passagem biblica principal', true, 'Ex.: Efesios 2:1-10'),
                    self::text('central_theme', 'Tema central do sermao', true, 'Ex.: A graca de Deus'),
                ],
            ],
            'culto_ocasional' => [
                'module' => 'pregacao',
                'title' => 'Culto Ocasional',
                'description' => 'Prepare uma mensagem sensivel para ocasioes especiais.',
                'icon' => 'heart',
                'accent' => '#E11D48',
                'fields' => [
                    self::select('occasion_type', 'Tipo de ocasiao', true, [['casamento', 'Casamento'], ['culto_funebre', 'Culto funebre'], ['acao_gracas', 'Acao de gracas'], ['apresentacao_crianca', 'Apresentacao de crianca'], ['formatura', 'Formatura'], ['ordenacao_pastoral', 'Ordenacao pastoral']]),
                    self::select('bible_version', 'Versao biblica', true, self::bibleVersionOptions()),
                    self::textarea('occasion_context', 'Contexto da ocasiao', true, 'Descreva pessoas, momento, cuidados pastorais e tom desejado.'),
                    self::text('central_passage', 'Passagem biblica central', true, 'Ex.: 1 Corintios 13'),
                    self::text('message_theme', 'Tema da mensagem', false, 'Opcional'),
                ],
            ],
            'aula_ebd' => [
                'module' => 'pregacao',
                'title' => 'Aula EBD',
                'description' => 'Gere um plano de aula completo para Escola Biblica Dominical.',
                'icon' => 'book',
                'accent' => '#0F766E',
                'fields' => [
                    self::text('passage', 'Passagem biblica', false, 'Ex.: Efesios 2:1-10'),
                    self::text('lesson_theme', 'Tema da aula', false, 'Ex.: A justificacao pela fe'),
                    self::select('age_group', 'Faixa', true, [['adultos', 'Adultos'], ['casados', 'Casados'], ['jovens', 'Jovens'], ['adolescentes', 'Adolescentes'], ['catecumenos', 'Catecumenos / novos membros']]),
                    self::select('available_time', 'Tempo disponivel', true, [['30', '30 minutos'], ['45', '45 minutos'], ['60', '60 minutos'], ['75', '75 minutos']]),
                    self::select('level', 'Nivel', true, [['basico', 'Basico'], ['intermediario', 'Intermediario'], ['avancado', 'Avancado']]),
                    self::select('bible_version', 'Versao biblica', true, self::bibleVersionOptions()),
                    self::textarea('additional_context', 'Contexto adicional', false, 'Opcional'),
                ],
            ],
            'estudo_exegetico' => [
                'module' => 'estudos',
                'title' => 'Estudo Exegetico',
                'description' => 'Aprofunde uma passagem antes de pregar, ensinar ou discipular.',
                'icon' => 'search',
                'accent' => '#2563EB',
                'fields' => [
                    self::select('depth', 'Profundidade', true, [['pastoral', 'Pastoral'], ['academico', 'Academico']]),
                    self::text('passage', 'Passagem biblica', true, 'Ex.: Romanos 8:28-39'),
                    self::select('bible_version', 'Versao biblica', true, self::bibleVersionOptions()),
                ],
            ],
            'estudo_biblico' => [
                'module' => 'estudos',
                'title' => 'Estudo Biblico',
                'description' => 'Prepare estudos por passagem, tema, confissao, palavra-chave ou familia.',
                'icon' => 'book-open',
                'accent' => '#0891B2',
                'fields' => [
                    self::select('study_type', 'Tipo de estudo', true, [['exegetico_passagem', 'Exegetico por passagem'], ['teologico_tema', 'Teologico por tema'], ['confessional_catecismo', 'Confessional / catecismo'], ['palavra_chave', 'Palavra-chave'], ['familiar_devocional', 'Familiar / devocional']]),
                    self::select('depth_level', 'Nivel de profundidade', true, [['basico', 'Basico'], ['intermediario', 'Intermediario'], ['avancado', 'Avancado']]),
                    self::text('passage', 'Passagem biblica', false, 'Opcional'),
                    self::text('study_theme', 'Tema do estudo', false, 'Opcional'),
                    self::select('bible_version', 'Versao biblica', true, self::bibleVersionOptions()),
                ],
            ],
        ];
    }

    public static function workflowsByModule(): array
    {
        $grouped = [];
        foreach (self::workflows() as $id => $workflow) {
            $module = (string) $workflow['module'];
            $workflow['id'] = $id;
            $grouped[$module][] = $workflow;
        }

        return $grouped;
    }

    public static function getWorkflow(string $workflowId): ?array
    {
        $workflows = self::workflows();
        if (!isset($workflows[$workflowId])) {
            return null;
        }

        $workflow = $workflows[$workflowId];
        $workflow['id'] = $workflowId;
        return $workflow;
    }

    public static function validatePayload(string $workflowId, array $payload): array
    {
        $workflow = self::getWorkflow($workflowId);
        if ($workflow === null) {
            return ['Fluxo invalido.'];
        }

        $errors = [];
        foreach (($workflow['fields'] ?? []) as $field) {
            if (!self::fieldConditionMatches($field, $payload)) {
                continue;
            }

            $key = (string) ($field['name'] ?? '');
            $value = trim((string) ($payload[$key] ?? ''));
            if (!empty($field['required']) && $value === '') {
                $errors[] = 'Preencha o campo "' . (string) ($field['label'] ?? $key) . '".';
            }
            if (($field['type'] ?? '') === 'textarea' && strlen($value) > 8000) {
                $errors[] = 'O campo "' . (string) ($field['label'] ?? $key) . '" ultrapassou o limite de 8000 caracteres.';
            }
            if (($field['type'] ?? '') !== 'textarea' && strlen($value) > 500) {
                $errors[] = 'O campo "' . (string) ($field['label'] ?? $key) . '" ultrapassou o limite de 500 caracteres.';
            }
        }

        return $errors;
    }

    private static function fieldConditionMatches(array $field, array $payload): bool
    {
        $condition = $field['condition'] ?? null;
        if (!is_array($condition)) {
            return true;
        }

        $fieldName = (string) ($condition['field'] ?? '');
        $expected = (string) ($condition['equals'] ?? '');
        if ($fieldName === '') {
            return true;
        }

        return (string) ($payload[$fieldName] ?? '') === $expected;
    }

    private static function text(string $name, string $label, bool $required, string $placeholder = ''): array
    {
        return compact('name', 'label', 'required', 'placeholder') + ['type' => 'text'];
    }

    private static function textarea(string $name, string $label, bool $required, string $placeholder = ''): array
    {
        return compact('name', 'label', 'required', 'placeholder') + ['type' => 'textarea'];
    }

    private static function select(string $name, string $label, bool $required, array $options): array
    {
        return compact('name', 'label', 'required', 'options') + ['type' => 'select'];
    }

    private static function homileticOptions(): array
    {
        return [
            ['automatico', 'Automatico recomendado'],
            ['expositivo_sequencial', 'Expositivo sequencial'],
            ['dedutiva_classica', 'Dedutiva classica'],
            ['tres_pontos', 'Classica de tres pontos'],
            ['proposicao_prova_pratica', 'Proposicao, prova e pratica'],
            ['problematico_solutiva', 'Problematico-solutiva'],
            ['narrativa', 'Narrativa'],
            ['analitico_sintetica', 'Analitico-sintetica'],
            ['paradoxo', 'Paradoxo'],
            ['pergunta_resposta', 'Pergunta-resposta catequetico'],
        ];
    }

    private static function bibleVersionOptions(): array
    {
        return [['ara', 'Almeida Revista e Atualizada'], ['acf', 'Almeida Corrigida Fiel'], ['naa', 'Nova Almeida Atualizada'], ['nvi', 'Nova Versao Internacional']];
    }

    private static function audienceOptions(): array
    {
        return [['geral', 'Geral'], ['jovens', 'Jovens'], ['casais', 'Casais'], ['lideres', 'Lideres'], ['nao_crentes', 'Nao-crentes']];
    }

    private static function pastoralEmphasisOptions(): array
    {
        return [['doutrinaria', 'Doutrinaria'], ['pastoral_consoladora', 'Pastoral / Consoladora'], ['exortativa', 'Exortativa'], ['evangelistica', 'Evangelistica'], ['devocional', 'Devocional']];
    }

    private static function confessionalLayerOptions(): array
    {
        return [['somente_biblico', 'Somente biblico'], ['westminster', 'Confissao de Westminster'], ['londres_1689', 'Confissao Batista de Londres']];
    }
}
