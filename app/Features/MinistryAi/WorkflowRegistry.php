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
                'description' => 'Currículos, discipulados, liderança, pequenos grupos e plano anual.',
            ],
            [
                'id' => 'pregacao',
                'title' => 'Pregação',
                'description' => 'Sermões, rascunhos, cultos ocasionais e aulas EBD.',
            ],
            [
                'id' => 'estudos',
                'title' => 'Estudos Bíblicos',
                'description' => 'Estudos exegéticos, temáticos, confessionais e devocionais.',
            ],
        ];
    }

    public static function workflows(): array
    {
        return [
            'series_sermoes' => [
                'module' => 'planejamento',
                'title' => 'Séries de Sermões',
                'description' => 'Planeje uma série com progressão pastoral, textos e mensagens.',
                'icon' => 'mic',
                'accent' => '#D97706',
                'fields' => [
                    self::text('series_theme', 'Tema da série', true, 'Ex.: As promessas de Deus em tempos difíceis'),
                    self::select('message_count', 'Número de mensagens', true, [['4', '4 mensagens'], ['6', '6 mensagens'], ['8', '8 mensagens'], ['10', '10 mensagens'], ['12', '12 mensagens']]),
                    self::select('audience', 'Público-alvo', true, self::audienceOptions()),
                    self::select('series_emphasis', 'Ênfase da série', true, self::pastoralEmphasisOptions()),
                ],
            ],
            'curriculo_escola_dominical' => [
                'module' => 'planejamento',
                'title' => 'Currículo de Escola Dominical',
                'description' => 'Crie uma série pedagógica para classes e trilhas de formação.',
                'icon' => 'book',
                'accent' => '#5B21B6',
                'fields' => [
                    self::select('creation_mode', 'Modo de criação', true, [['trilhas_sugeridas', 'Trilhas sugeridas'], ['criar_do_zero', 'Criar do zero']]),
                    self::select('audience', 'Público-alvo', true, [['adolescentes', 'Adolescentes'], ['jovens', 'Jovens'], ['casais', 'Casais'], ['adultos', 'Adultos'], ['catecumenos', 'Catecúmenos'], ['novos_membros', 'Novos membros']]),
                    self::select('suggested_track', 'Trilha sugerida', false, [['solas_reforma', 'Solas da Reforma'], ['panorama_biblico', 'Panorama Bíblico'], ['evangelho_joao', 'Evangelho de João'], ['doutrina_salvacao', 'Doutrina da Salvação'], ['vida_crista_reformada', 'Vida Cristã Reformada'], ['catecismo_comentado', 'Catecismo Comentado'], ['igreja_sacramentos', 'Igreja e Sacramentos'], ['cosmovisao_crista', 'Cosmovisão Cristã']]) + ['condition' => ['field' => 'creation_mode', 'equals' => 'trilhas_sugeridas']],
                    self::text('series_theme', 'Tema da série', false, 'Quando criar do zero') + ['condition' => ['field' => 'creation_mode', 'equals' => 'criar_do_zero']],
                    self::text('pedagogical_goal', 'Objetivo pedagógico', true, 'O que a turma deve saber, crer e praticar?'),
                    self::textarea('class_need', 'Necessidade específica da turma', false, 'Opcional') + ['condition' => ['field' => 'creation_mode', 'equals' => 'criar_do_zero']],
                    self::select('lesson_count', 'Quantidade de lições', true, [['4', '4'], ['6', '6'], ['8', '8'], ['10', '10'], ['12', '12'], ['13', '13']]),
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
                    self::select('spiritual_profile', 'Perfil espiritual', true, [['novo_convertido', 'Novo convertido'], ['catolicismo', 'Vindo do catolicismo'], ['pentecostalismo', 'Vindo do pentecostalismo'], ['evangelico_tradicional', 'Evangélico tradicional'], ['contexto_reformado', 'Contexto reformado'], ['membro_antigo', 'Membro antigo'], ['retornando_fe', 'Retornando à fé']]),
                    self::select('age_group', 'Faixa etária', true, [['adolescente', 'Adolescente'], ['jovem', 'Jovem'], ['adulto', 'Adulto'], ['maduro_idoso', 'Maduro / idoso']]),
                    self::select('biblical_level', 'Nível bíblico', true, [['basico', 'Básico'], ['intermediario', 'Intermediário'], ['avancado', 'Avançado']]),
                    self::select('main_goal', 'Objetivo principal', true, [['fundamentos_fe', 'Fundamentos da fé'], ['integracao_igreja', 'Integração na igreja'], ['profissao_membresia', 'Profissão de fé / membresia'], ['formacao_lideranca', 'Formação para liderança'], ['restauracao_espiritual', 'Restauração espiritual']]),
                    self::select('frequency', 'Frequência', true, [['semanal', 'Semanal'], ['quinzenal', 'Quinzenal'], ['mensal', 'Mensal']]),
                    self::select('meeting_duration', 'Duração do encontro', true, [['45', '45 minutos'], ['60', '60 minutos'], ['75', '75 minutos'], ['90', '90 minutos']]),
                    self::select('environment', 'Ambiente', true, [['casa', 'Casa'], ['igreja', 'Igreja'], ['cafe', 'Café'], ['online', 'Online']]),
                    self::select('confessional_layer', 'Camada confessional', true, self::confessionalLayerOptions()),
                    self::textarea('discipler_notes', 'Observações do discipulador', false, 'Opcional'),
                ],
            ],
            'discipulado_casais' => [
                'module' => 'planejamento',
                'title' => 'Discipulado de Casais',
                'description' => 'Prepare uma trilha de 7 encontros para noivos ou casais.',
                'icon' => 'heart',
                'accent' => '#DB2777',
                'fields' => [
                    self::select('couples_track', 'Sugestão de trilha', true, [['estrutura_7', 'Estrutura fixa - 7 encontros'], ['noivos', 'Preparação para noivos'], ['casais_novos', 'Casais no início da caminhada'], ['restauracao', 'Restauração e renovação conjugal']]),
                    self::select('confessional_layer', 'Camada confessional', true, self::confessionalLayerOptions()),
                    self::textarea('couple_context', 'Contexto do casal', true, 'Descreva o contexto com cuidado pastoral.'),
                    self::select('meeting_duration', 'Duração do encontro', true, [['60', '60 minutos'], ['75', '75 minutos'], ['90', '90 minutos']]),
                    self::select('environment', 'Ambiente', true, [['sala_igreja', 'Sala na igreja'], ['casa_pastoral', 'Casa pastoral'], ['casa_casal', 'Casa do casal'], ['online', 'Online']]),
                ],
            ],
            'plano_anual_igreja' => [
                'module' => 'planejamento',
                'title' => 'Plano Anual da Igreja',
                'description' => 'Estruture discernimento pastoral, pilares e ações para o ano.',
                'icon' => 'target',
                'accent' => '#F59E0B',
                'fields' => [
                    self::textarea('pastoral_context', 'Visão e Contexto Pastoral', true, 'Escreva livremente. Quanto mais contexto, mais preciso o discernimento.'),
                    self::text('reference_year', 'Ano de referência', true, (string) ((int) date('Y') + 1)) + ['width' => 'third'],
                    self::select('denominational_base', 'Base Denominacional', true, [
                        ['presbiteriana', 'Presbiteriana'],
                        ['batista_reformada', 'Batista reformada'],
                        ['batista_tradicional', 'Batista (Tradicional)'],
                        ['congregacional', 'Congregacional'],
                        ['pentecostal', 'Pentecostal'],
                        ['assembleiana', 'Assembleiana'],
                        ['luterana', 'Luterana'],
                        ['anglicana', 'Anglicana'],
                        ['independente', 'Independente'],
                        ['outra', 'Outra']
                    ]) + ['width' => 'third'],
                    self::select('confessional_layer', 'Camada Confessional', true, [
                        ['somente_biblico', 'Somente bíblico'],
                        ['westminster', 'Confissão de Westminster'],
                        ['londres_1689', 'Confissão Batista de Londres'],
                        ['tres_formas_unidade', 'Três Formas de Unidade'],
                        ['catecismo_heidelberg', 'Catecismo de Heidelberg'],
                        ['canones_dort', 'Cânones de Dort'],
                        ['confissao_belga', 'Confissão Belga'],
                        ['augsburgo', 'Confissão de Augsburgo'],
                        ['trinta_nove_artigos', 'Trinta e Nove Artigos'],
                        ['metodista_wesleyana', 'Base metodista / wesleyana'],
                        ['batista_fe_mensagem', 'Fé e Mensagem Batista']
                    ]) + ['width' => 'third'],
                ],
            ],
            'treinamento_lideranca' => [
                'module' => 'planejamento',
                'title' => 'Treinamento de Liderança',
                'description' => 'Monte uma trilha para formação de líderes e equipes ministeriais.',
                'icon' => 'award',
                'accent' => '#0D9488',
                'fields' => [
                    self::select('training_type', 'Tipo de treinamento', true, [['base_geral', 'Base geral'], ['presbiteros', 'Formação de presbíteros'], ['diaconos', 'Formação de diáconos'], ['jovens', 'Líderes de jovens'], ['professores_ebd', 'Professores de EBD'], ['pequenos_grupos', 'Líderes de pequenos grupos']]),
                    self::select('member_count', 'Número aproximado de membros', true, [['ate_50', 'Até 50 membros'], ['50_150', '50 a 150 membros'], ['150_500', '150 a 500 membros'], ['500_plus', 'Acima de 500 membros']]),
                    self::select('church_moment', 'Momento atual da igreja', true, [['rotina_ministerial', 'Rotina ministerial'], ['eleicao_proxima', 'Eleição próxima'], ['crescimento', 'Fase de crescimento'], ['conflito', 'Conflito interno'], ['reorganizacao', 'Reorganização'], ['outra', 'Outra']]),
                    self::select('denominational_base', 'Base Denominacional', true, [
                        ['presbiteriana', 'Presbiteriana'],
                        ['batista_reformada', 'Batista reformada'],
                        ['batista_tradicional', 'Batista (Tradicional)'],
                        ['congregacional', 'Congregacional'],
                        ['pentecostal', 'Pentecostal'],
                        ['assembleiana', 'Assembleiana'],
                        ['luterana', 'Luterana'],
                        ['anglicana', 'Anglicana'],
                        ['independente', 'Independente'],
                        ['outra', 'Outra']
                    ]),
                    self::select('depth', 'Profundidade', true, [['pastoral', 'Pastoral'], ['estruturada', 'Estruturada']]),
                    self::select('confessional_layer', 'Camada confessional', true, self::confessionalLayerOptions()),
                    self::textarea('pastor_notes', 'Observações do pastor', false, 'Opcional'),
                ],
            ],
            'pequenos_grupos' => [
                'module' => 'planejamento',
                'title' => 'Planejamento de Pequenos Grupos',
                'description' => 'Crie ciclos com encontros, perguntas, aplicação e envio.',
                'icon' => 'network',
                'accent' => '#7C3AED',
                'fields' => [
                    self::textarea('group_context', 'Visão e contexto do grupo', true, 'Descreva visão, público, momento e necessidade.'),
                    self::select('meeting_count', 'Número de encontros', true, [['6', '6 encontros'], ['8', '8 encontros (padrão)'], ['10', '10 encontros']]),
                    self::select('meeting_duration', 'Duração por encontro', true, [['60', '60 minutos'], ['75', '75 minutos'], ['90', '90 minutos'], ['120', '120 minutos']]),
                    self::select('frequency', 'Frequência', true, [['semanal', 'Semanal'], ['quinzenal', 'Quinzenal'], ['mensal', 'Mensal']]),
                    self::select('confessional_layer', 'Camada confessional', true, self::confessionalLayerOptions()),
                ],
            ],
            'gerar_sermao' => [
                'module' => 'pregacao',
                'title' => 'Gerar Sermão',
                'description' => 'Monte um sermão estruturado a partir de texto, tema e contexto pastoral.',
                'icon' => 'pen',
                'accent' => '#0A4DFF',
                'fields' => [
                    self::select('sermon_type', 'Tipo de sermão', true, [['expositivo', 'Expositivo'], ['textual', 'Textual'], ['tematico', 'Temático'], ['biografico', 'Biográfico']]),
                    self::select('homiletic_structure', 'Estrutura homilética', true, self::homileticOptions()),
                    self::text('main_passage', 'Passagem bíblica principal', true, 'Ex.: Efésios 2:1-10'),
                    self::text('central_theme', 'Tema central do sermão', true, 'Ex.: A soberania de Deus na salvação'),
                    self::text('biblical_character', 'Personagem bíblico', false, 'Opcional'),
                    self::select('pastoral_emphasis', 'Ênfase pastoral', true, self::pastoralEmphasisOptions()),
                    self::select('audience', 'Público-alvo', true, self::audienceOptions()),
                    self::select('duration_minutes', 'Duração em minutos', true, [['25', '25'], ['35', '35'], ['45', '45'], ['60', '60']]),
                    self::select('bible_version', 'Versão bíblica', true, self::bibleVersionOptions()),
                    self::select('application_style', 'Estilo de aplicação', true, [['ao_final', 'Ao final'], ['por_ponto', 'Ao longo dos pontos'], ['misto', 'Misto']]),
                    self::select('include_reformed_quotes', 'Incluir citações reformadas', true, [['sim', 'Incluir'], ['nao', 'Não incluir']]),
                    self::select('include_illustrations', 'Incluir ilustrações', true, [['sim', 'Incluir'], ['nao', 'Não incluir']]),
                ],
            ],
            'refinar_rascunho' => [
                'module' => 'pregacao',
                'title' => 'Refinar Rascunho',
                'description' => 'Reorganize e fortaleça um rascunho sem apagar a voz do pastor.',
                'icon' => 'spark',
                'accent' => '#7C3AED',
                'fields' => [
                    self::textarea('draft', 'Rascunho do sermão', true, 'Cole ou digite aqui o rascunho...'),
                    self::select('homiletic_structure', 'Estrutura homilética', true, self::homileticOptions()),
                    self::select('sermon_type', 'Tipo de sermão', true, [['expositivo', 'Expositivo'], ['textual', 'Textual'], ['tematico', 'Temático'], ['biografico', 'Biográfico']]),
                    self::text('main_passage', 'Passagem bíblica principal', true, 'Ex.: Efésios 2:1-10'),
                    self::text('central_theme', 'Tema central do sermão', true, 'Ex.: A graça de Deus'),
                ],
            ],
            'culto_ocasional' => [
                'module' => 'pregacao',
                'title' => 'Culto Ocasional',
                'description' => 'Prepare uma mensagem sensível para ocasiões especiais.',
                'icon' => 'heart',
                'accent' => '#E11D48',
                'fields' => [
                    self::select('occasion_type', 'Tipo de ocasião', true, [['casamento', 'Casamento'], ['culto_funebre', 'Culto fúnebre'], ['acao_gracas', 'Ação de graças'], ['apresentacao_crianca', 'Apresentação de criança'], ['formatura', 'Formatura'], ['ordenacao_pastoral', 'Ordenação pastoral']]),
                    self::select('bible_version', 'Versão bíblica', true, self::bibleVersionOptions()),
                    self::textarea('occasion_context', 'Contexto da ocasião', true, 'Descreva pessoas, momento, cuidados pastorais e tom desejado.'),
                    self::text('central_passage', 'Passagem bíblica central', true, 'Ex.: 1 Coríntios 13'),
                    self::text('message_theme', 'Tema da mensagem', false, 'Opcional'),
                ],
            ],
            'aula_ebd' => [
                'module' => 'pregacao',
                'title' => 'Aula EBD',
                'description' => 'Gere um plano de aula completo para Escola Bíblica Dominical.',
                'icon' => 'book',
                'accent' => '#0F766E',
                'fields' => [
                    self::text('passage', 'Passagem bíblica', false, 'Ex.: Efésios 2:1-10'),
                    self::text('lesson_theme', 'Tema da aula', false, 'Ex.: A justificação pela fé'),
                    self::select('age_group', 'Faixa', true, [['adultos', 'Adultos'], ['casados', 'Casados'], ['jovens', 'Jovens'], ['adolescentes', 'Adolescentes'], ['catecumenos', 'Catecúmenos / novos membros']]),
                    self::select('available_time', 'Tempo disponível', true, [['30', '30 minutos'], ['45', '45 minutos'], ['60', '60 minutos'], ['75', '75 minutos']]),
                    self::select('level', 'Nível', true, [['basico', 'Básico'], ['intermediario', 'Intermediário'], ['avancado', 'Avançado']]),
                    self::select('bible_version', 'Versão bíblica', true, self::bibleVersionOptions()),
                    self::textarea('additional_context', 'Contexto adicional', false, 'Opcional'),
                ],
            ],
            'estudo_exegetico' => [
                'module' => 'estudos',
                'title' => 'Estudo Exegético',
                'description' => 'Aprofunde uma passagem antes de pregar, ensinar ou discipular.',
                'icon' => 'search',
                'accent' => '#2563EB',
                'fields' => [
                    self::select('depth', 'Profundidade', true, [['pastoral', 'Pastoral'], ['academico', 'Acadêmico']]) + ['width' => 'third'],
                    self::text('passage', 'Passagem bíblica', true, 'Ex.: Romanos 8:28-39') + ['width' => 'third'],
                    self::select('bible_version', 'Versão bíblica', true, self::bibleVersionOptions()) + ['width' => 'third'],
                ],
            ],
            'estudo_biblico' => [
                'module' => 'estudos',
                'title' => 'Estudo Bíblico',
                'description' => 'Prepare estudos por passagem, tema, confissão, palavra-chave ou família.',
                'icon' => 'book-open',
                'accent' => '#0891B2',
                'fields' => [
                    self::select('study_type', 'Tipo de estudo', true, [['exegetico_passagem', 'Exegético por passagem'], ['teologico_tema', 'Teológico por tema'], ['confessional_catecismo', 'Confessional / catecismo'], ['palavra_chave', 'Palavra-chave'], ['familiar_devocional', 'Familiar / devocional']]),
                    self::select('depth_level', 'Nível de profundidade', true, [['basico', 'Básico'], ['intermediario', 'Intermediário'], ['avancado', 'Avançado']]),
                    self::text('passage', 'Passagem bíblica', false, 'Opcional'),
                    self::text('study_theme', 'Tema do estudo', false, 'Opcional'),
                    self::select('bible_version', 'Versão bíblica', true, self::bibleVersionOptions()),
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
            return ['Fluxo inválido.'];
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
            ['automatico', 'Automático recomendado'],
            ['expositivo_sequencial', 'Expositivo sequencial'],
            ['dedutiva_classica', 'Dedutiva clássica'],
            ['tres_pontos', 'Clássica de três pontos'],
            ['proposicao_prova_pratica', 'Proposição, prova e prática'],
            ['problematico_solutiva', 'Problemático-solutiva'],
            ['narrativa', 'Narrativa'],
            ['analitico_sintetica', 'Analítico-sintética'],
            ['paradoxo', 'Paradoxo'],
            ['pergunta_resposta', 'Pergunta-resposta catequético'],
        ];
    }

    private static function bibleVersionOptions(): array
    {
        return [['ara', 'Almeida Revista e Atualizada'], ['acf', 'Almeida Corrigida Fiel'], ['naa', 'Nova Almeida Atualizada'], ['nvi', 'Nova Versão Internacional']];
    }

    private static function audienceOptions(): array
    {
        return [['geral', 'Geral'], ['jovens', 'Jovens'], ['casais', 'Casais'], ['lideres', 'Líderes'], ['nao_crentes', 'Não-crentes']];
    }

    private static function pastoralEmphasisOptions(): array
    {
        return [['doutrinaria', 'Doutrinária'], ['pastoral_consoladora', 'Pastoral / Consoladora'], ['exortativa', 'Exortativa'], ['evangelistica', 'Evangelística'], ['devocional', 'Devocional']];
    }

    private static function confessionalLayerOptions(): array
    {
        return [
            ['somente_biblico', 'Somente bíblico'],
            ['westminster', 'Confissão de Westminster'],
            ['londres_1689', 'Confissão Batista de Londres'],
            ['tres_formas_unidade', 'Três Formas de Unidade'],
            ['catecismo_heidelberg', 'Catecismo de Heidelberg'],
            ['canones_dort', 'Cânones de Dort'],
            ['confissao_belga', 'Confissão Belga'],
            ['augsburgo', 'Confissão de Augsburgo'],
            ['trinta_nove_artigos', 'Trinta e Nove Artigos'],
            ['metodista_wesleyana', 'Base metodista / wesleyana'],
            ['batista_fe_mensagem', 'Fé e Mensagem Batista'],
        ];
    }
}
