<?php

declare(strict_types=1);

namespace App\Services;

use App\Features\MinistryAi\PromptBuilder;

/**
 * OpenAiService — integração segura com a Responses API da OpenAI.
 *
 * Responsável por gerar materiais ministeriais (sermão, estudo, recurso)
 * a partir do payload da Central Pastoral IA. Quando a chave OPENAI_API_KEY não
 * está configurada ou a chamada falha, retorna `null` para que o chamador
 * caia no fallback offline.
 */
class OpenAiService
{
    private string $apiKey;
    private string $model;
    private string $endpoint;
    private int $timeout;
    private float $temperature;

    public function __construct()
    {
        $this->apiKey      = (string) (env('OPENAI_API_KEY', '') ?: $this->platformSetting('openai_api_key', ''));
        $this->model       = (string) (env('OPENAI_MODEL', '') ?: $this->platformSetting('openai_model', 'gpt-4o-mini'));
        $this->endpoint    = rtrim((string) env('OPENAI_BASE_URL', 'https://api.openai.com/v1'), '/') . '/responses';
        $this->timeout     = (int) (env('OPENAI_TIMEOUT', '') ?: $this->platformSetting('openai_timeout', '60'));
        $this->temperature = (float) (env('OPENAI_TEMPERATURE', '') ?: $this->platformSetting('openai_temperature', '0.6'));
    }

    private function platformSetting(string $key, string $default = ''): string
    {
        try {
            if (!class_exists(\App\Models\PlatformSetting::class)) {
                return $default;
            }

            return (string) (\App\Models\PlatformSetting::get($key, $default) ?? $default);
        } catch (\Throwable $e) {
            return $default;
        }
    }

    public function isEnabled(): bool
    {
        return $this->apiKey !== '';
    }

    public function generateMinistryAi(string $workflowId, array $inputPayload): ?array
    {
        if (!$this->isEnabled() || $this->model === '') {
            return null;
        }

        try {
            $prompt = PromptBuilder::build($workflowId, $inputPayload);
        } catch (\Throwable $e) {
            return null;
        }

        $body = $this->postJson([
            'model' => $this->model,
            'instructions' => (string) $prompt['systemPrompt'],
            'input' => (string) $prompt['userPrompt'],
        ]);

        if ($body === null) {
            return null;
        }

        $output = $this->extractResponseText($body);
        if ($output === null || trim($output) === '') {
            return null;
        }

        return [
            'title' => (string) ($prompt['title'] ?? 'Geracao ministerial'),
            'outputMarkdown' => trim($output),
            'modelUsed' => $this->model,
        ];
    }

    /**
     * Gera o material ministerial respeitando o formato da Central Pastoral IA.
     *
     * @return string|null  Texto pronto para exibir ou null em caso de falha/desabilitado.
     */
    public function generateExpositorMaterial(array $form): ?string
    {
        $contentType = trim((string) ($form['content_type'] ?? 'sermon'));
        $resource = strtolower(trim((string) ($form['resource_title'] ?? '')));
        $workflowId = 'gerar_sermao';

        if ($contentType === 'study') {
            $workflowId = str_contains($resource, 'academico') ? 'estudo_exegetico' : 'estudo_biblico';
        } elseif (str_contains($resource, 'refinar')) {
            $workflowId = 'refinar_rascunho';
        } elseif (str_contains($resource, 'culto ocasional')) {
            $workflowId = 'culto_ocasional';
        } elseif (str_contains($resource, 'aula ebd')) {
            $workflowId = 'aula_ebd';
        }

        $payload = [
            'main_passage' => trim((string) ($form['passage'] ?? '')),
            'passage' => trim((string) ($form['passage'] ?? '')),
            'central_passage' => trim((string) ($form['passage'] ?? '')),
            'central_theme' => trim((string) ($form['theme'] ?? '')),
            'study_theme' => trim((string) ($form['theme'] ?? '')),
            'lesson_theme' => trim((string) ($form['theme'] ?? '')),
            'pastoral_emphasis' => trim((string) ($form['theme'] ?? '')),
            'draft' => trim((string) ($form['notes'] ?? '')),
            'occasion_context' => trim((string) ($form['notes'] ?? $form['passage'] ?? '')),
            'bible_version' => 'ara',
            'sermon_type' => 'expositivo',
            'homiletic_structure' => 'automatico',
            'audience' => trim((string) ($form['audience'] ?? 'geral')) ?: 'geral',
            'duration_minutes' => trim((string) ($form['duration'] ?? '35')) ?: '35',
            'application_style' => 'misto',
            'include_reformed_quotes' => 'nao',
            'include_illustrations' => 'sim',
            'depth' => trim((string) ($form['depth'] ?? 'pastoral')) ?: 'pastoral',
            'study_type' => 'exegetico_passagem',
            'depth_level' => 'intermediario',
            'occasion_type' => 'acao_gracas',
            'age_group' => 'adultos',
            'available_time' => '45',
            'level' => 'intermediario',
        ];

        $result = $this->generateMinistryAi($workflowId, $payload);

        return is_array($result) ? (string) $result['outputMarkdown'] : null;

        if (!$this->isEnabled()) {
            return null;
        }

        $passage      = trim((string) ($form['passage'] ?? ''));
        $theme        = trim((string) ($form['theme'] ?? ''));
        $confessional = trim((string) ($form['confessional'] ?? 'biblico-evangelico'));
        $depth        = trim((string) ($form['depth'] ?? 'pastoral'));
        $contentType  = trim((string) ($form['content_type'] ?? 'sermon'));
        $resource     = trim((string) ($form['resource_title'] ?? ''));
        $duration     = trim((string) ($form['duration'] ?? ''));
        $audience     = trim((string) ($form['audience'] ?? ''));
        $notes        = trim((string) ($form['notes'] ?? ''));

        $contentLabel = $this->expositorFlowLabel($contentType, $resource);

        $depthLabel = match ($depth) {
            'teologico' => 'Aprofundamento teológico, com referências doutrinárias claras',
            'academico' => 'Exegese acadêmica, dialogando com gênero literário e contexto histórico',
            default     => 'Sermão pastoral acessível, com aplicação prática',
        };

        $system = <<<PROMPT
Você é um pastor/expositor bíblico maduro escrevendo material para outro pastor revisar.
Seu trabalho é entregar conteúdo bíblico fiel, sóbrio e prático, alinhado à confissão indicada,
sem inventar passagens nem alegorizar. Respeite a Escritura como autoridade final.
Estruture a resposta em seções com títulos numerados e bullets quando ajudar a leitura.
Use português do Brasil, tom pastoral e claro.
PROMPT;

        $userPrompt = "Tipo de material: {$contentLabel}\n";
        if ($resource !== '') { $userPrompt .= "Recurso/aplicação: {$resource}\n"; }
        $userPrompt .= "Passagem ou contexto: " . ($passage !== '' ? $passage : 'não informado') . "\n";
        $userPrompt .= "Tema/ênfase: " . ($theme !== '' ? $theme : 'livre') . "\n";
        if ($duration !== '') { $userPrompt .= "Duracao desejada: {$duration}\n"; }
        if ($audience !== '') { $userPrompt .= "Publico-alvo: {$audience}\n"; }
        if ($notes !== '') { $userPrompt .= "Observacoes ou rascunho-base: {$notes}\n"; }
        $userPrompt .= "Linha confessional: {$confessional}\n";
        $userPrompt .= "Profundidade: {$depthLabel}\n\n";
        $userPrompt .= $this->expositorFlowInstructions($contentType, $resource);

        $payload = [
            'model'       => $this->model,
            'temperature' => $this->temperature,
            'messages'    => [
                ['role' => 'system', 'content' => $system],
                ['role' => 'user',   'content' => $userPrompt],
            ],
        ];

        $body = $this->postJson($payload);
        if ($body === null) {
            return null;
        }

        $content = $body['choices'][0]['message']['content'] ?? null;
        if (!is_string($content) || trim($content) === '') {
            return null;
        }

        return trim($content);
    }

    private function expositorFlowLabel(string $contentType, string $resource): string
    {
        $resource = strtolower($resource);

        if ($contentType === 'series') {
            return 'Planejamento de série de sermões';
        }

        if ($contentType === 'resource' && $resource !== '') {
            return $resource;
        }

        if ($contentType === 'study' && str_contains($resource, 'academico')) {
            return 'Estudo de texto acadêmico';
        }

        if ($contentType === 'study') {
            return $resource !== '' ? $resource : 'Estudo de texto pastoral';
        }

        if ($contentType === 'reading_plan') {
            return 'Plano de leitura bíblica';
        }

        if (str_contains($resource, 'refinar')) {
            return 'Refinamento de rascunho de sermão';
        }

        if (str_contains($resource, 'culto ocasional')) {
            return 'Roteiro para culto ocasional';
        }

        if (str_contains($resource, 'aula ebd')) {
            return 'Aula para Escola Bíblica Dominical';
        }

        return 'Sermão expositivo';
    }

    private function expositorFlowInstructions(string $contentType, string $resource): string
    {
        $resource = strtolower($resource);

        if ($contentType === 'series') {
            return <<<TEXT
Estruture a resposta com:
1. Visão geral da série: tese, objetivo pastoral, público e fio teológico.
2. Mapa das mensagens: título, texto base, ideia central, objetivo, aplicações e ligação entre os encontros.
3. Calendário sugerido e materiais de apoio para pequenos grupos, EBD ou discipulado.
4. Critérios de revisão: coerência bíblica, progressão pastoral e próximos passos.
TEXT;
        }

        if ($contentType === 'resource') {
            if (str_contains($resource, 'curr')) {
                return <<<TEXT
Estruture a resposta como curriculo de Escola Dominical:
1. Visao geral da serie: objetivo pedagogico, publico, duracao e resultado esperado.
2. Trilha de aulas: titulo, texto biblico, doutrina central, dinamica e aplicacao de cada encontro.
3. Camada do professor: perguntas de observacao, interpretacao, aplicacao e avaliacao.
4. Integracao com o Hub: sugestao de serie, aulas publicaveis e proximos passos para membros.
TEXT;
            }

            if (str_contains($resource, 'discipulado')) {
                return <<<TEXT
Estruture a resposta como roteiro de discipulado:
1. Diagnostico do perfil espiritual e objetivo da trilha.
2. Encontros: titulo, passagem, objetivo, conversa guiada, pratica semanal e acompanhamento.
3. Criterios pastorais: sinais de avanco, alertas e proximos passos para integracao na igreja.
4. Integracao com o Hub: tarefas, membros responsaveis e materiais publicaveis.
TEXT;
            }

            if (str_contains($resource, 'casamento')) {
                return <<<TEXT
Estruture a resposta como preparacao biblica para casamento:
1. Visao pastoral do casal, tom adequado e objetivo dos encontros.
2. Estrutura fixa de encontros com tema, texto biblico, perguntas, exercicio e alerta pastoral.
3. Recomendacoes de acompanhamento, limites e proximos passos antes da cerimonia.
4. Integracao com o Hub: checklist, agenda de encontros e material para o casal.
TEXT;
            }

            if (str_contains($resource, 'pequenos grupos')) {
                return <<<TEXT
Estruture a resposta como planejamento de pequenos grupos:
1. Visao pastoral do ciclo, publico, ritmo e resultado esperado.
2. Encontros: titulo, passagem, quebra-gelo, estudo, perguntas e aplicacao comunitaria.
3. Orientacoes para lideres: acompanhamento, metricas saudaveis e alertas de cuidado.
4. Integracao com o Hub: grupos, lideres, calendario e publicacao para membros.
TEXT;
            }

            if (str_contains($resource, 'plano anual')) {
                return <<<TEXT
Estruture a resposta como plano anual da igreja:
1. Sintese de discernimento pastoral e tema norteador do ano.
2. Pilares, trimestre a trimestre, com objetivos, acoes, responsaveis e indicadores.
3. Agenda macro: culto, discipulado, pequenos grupos, comunicacao, financas e ministerios.
4. Integracao com o Hub: tarefas, calendario, campanhas e relatorios que devem ser criados.
TEXT;
            }

            return <<<TEXT
Estruture a resposta como recurso ministerial pratico:
1. Objetivo pastoral, publico e contexto de uso.
2. Estrutura do material com etapas, textos biblicos, perguntas e aplicacoes.
3. Plano de execucao: responsaveis, duracao, calendario e acompanhamento.
4. Integracao com o Hub: onde publicar, quais tarefas criar e como medir avanco.
TEXT;
        }
        if (str_contains($resource, 'refinar')) {
            return <<<TEXT
Estruture a resposta como refinamento do rascunho recebido:
1. Diagnóstico breve: pontos fortes, lacunas, riscos de interpretação e ajustes de clareza.
2. Tema central, proposição, pergunta de controle e objetivo pastoral revisados.
3. Esboço refinado com introdução, movimentos, transições, aplicações e conclusão.
4. Sugestões opcionais de ilustração, citações bíblicas de apoio e próximos ajustes.
TEXT;
        }

        if (str_contains($resource, 'culto ocasional')) {
            return <<<TEXT
Estruture a resposta para uma ocasião especial:
1. Sensibilidade pastoral da ocasião e tom adequado.
2. Roteiro da mensagem: abertura, texto central, desenvolvimento, aplicação e oração.
3. Elementos litúrgicos sugeridos: leitura bíblica, cânticos/ênfases, palavras de transição e encerramento.
4. Cuidados pastorais para não soar genérico nem deslocado do contexto informado.
TEXT;
        }

        if (str_contains($resource, 'aula ebd')) {
            return <<<TEXT
Estruture a resposta como plano de aula EBD:
1. Objetivo da aula, faixa/faixa etária e resultado esperado.
2. Exposição bíblica em blocos curtos com perguntas de observação, interpretação e aplicação.
3. Dinâmica de sala, atividade prática, avaliação de compreensão e tarefa para a semana.
4. Resumo para o professor e cuidados doutrinários.
TEXT;
        }

        if ($contentType === 'study' && str_contains($resource, 'academico')) {
            return <<<TEXT
Estruture a resposta como estudo acadêmico do texto:
1. Delimitação, contexto histórico-literário, gênero e estrutura da perícope.
2. Observações exegéticas: termos-chave, sintaxe, paralelos bíblicos e debates interpretativos.
3. Síntese teológica, implicações hermenêuticas e bibliografia sugerida por categorias.
4. Ponte responsável para ensino, pregação ou pesquisa posterior.
TEXT;
        }

        if ($contentType === 'study') {
            return <<<TEXT
Estruture a resposta como estudo bíblico/pastoral:
1. Observação do texto: contexto, personagens, palavras-chave e estrutura.
2. Interpretação: ideia central, doutrina, alertas contra leituras forçadas e eixo cristológico quando cabível.
3. Aplicação: perguntas para grupo, desafios práticos, oração e acompanhamento pastoral.
4. Resumo final para líder, professor ou discipulador.
TEXT;
        }

        if ($contentType === 'reading_plan') {
            return <<<TEXT
Estruture a resposta como plano de leitura:
1. Objetivo espiritual e orientação de uso.
2. Cronograma por dias ou semanas, com texto, foco, pergunta de reflexão e prática.
3. Sugestões para acompanhamento individual, grupo pequeno ou família.
4. Encerramento com oração e revisão semanal.
TEXT;
        }

        return <<<TEXT
Estruture a resposta como sermão:
1. Caminho exegético: contexto, estrutura, palavras-chave, teologia e ideia central.
2. Esboço homilético: introdução, proposição, movimentos principais, transições e conclusão.
3. Aplicações pastorais: coração, família, igreja, missão e vida pública quando pertinente.
4. Materiais derivados: perguntas para pequenos grupos, ideia para EBD/discipulado e resumo publicável.
TEXT;
    }

    private function extractResponseText(array $body): ?string
    {
        if (isset($body['output_text']) && is_string($body['output_text'])) {
            return $body['output_text'];
        }

        foreach (($body['output'] ?? []) as $output) {
            foreach (($output['content'] ?? []) as $content) {
                if (isset($content['text']) && is_string($content['text'])) {
                    return $content['text'];
                }
            }
        }

        return null;
    }

    private function postJson(array $payload): ?array
    {
        try {
            $ch = curl_init($this->endpoint);
            if ($ch === false) {
                return null;
            }

            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                CURLOPT_HTTPHEADER     => [
                    'Authorization: Bearer ' . $this->apiKey,
                    'Content-Type: application/json',
                ],
                CURLOPT_TIMEOUT        => $this->timeout,
                CURLOPT_CONNECTTIMEOUT => 10,
            ]);

            $response = curl_exec($ch);
            $status   = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error    = curl_error($ch);
            curl_close($ch);

            if ($response === false || $status >= 400) {
                error_log('[OpenAiService] HTTP ' . $status . ' err=' . $error);
                return null;
            }

            $decoded = json_decode((string) $response, true);
            return is_array($decoded) ? $decoded : null;
        } catch (\Throwable $e) {
            error_log('[OpenAiService] ' . $e->getMessage());
            return null;
        }
    }
}
