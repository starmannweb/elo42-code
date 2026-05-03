<?php

declare(strict_types=1);

namespace App\Services;

/**
 * OpenAiService — Integração com a API de Chat Completions da OpenAI.
 *
 * Responsável por gerar materiais ministeriais (sermão, estudo, recurso)
 * a partir do payload do Expositor IA. Quando a chave OPENAI_API_KEY não
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
        $this->apiKey      = (string) env('OPENAI_API_KEY', '');
        $this->model       = (string) env('OPENAI_MODEL', 'gpt-4o-mini');
        $this->endpoint    = rtrim((string) env('OPENAI_BASE_URL', 'https://api.openai.com/v1'), '/') . '/chat/completions';
        $this->timeout     = (int) env('OPENAI_TIMEOUT', 60);
        $this->temperature = (float) env('OPENAI_TEMPERATURE', 0.6);
    }

    public function isEnabled(): bool
    {
        return $this->apiKey !== '';
    }

    /**
     * Gera o material ministerial respeitando o formato/expectativa do Expositor IA.
     *
     * @return string|null  Texto pronto para exibir ou null em caso de falha/desabilitado.
     */
    public function generateExpositorMaterial(array $form): ?string
    {
        if (!$this->isEnabled()) {
            return null;
        }

        $passage      = trim((string) ($form['passage'] ?? ''));
        $theme        = trim((string) ($form['theme'] ?? ''));
        $confessional = trim((string) ($form['confessional'] ?? 'biblico-evangelico'));
        $depth        = trim((string) ($form['depth'] ?? 'pastoral'));
        $contentType  = trim((string) ($form['content_type'] ?? 'sermon'));
        $resource     = trim((string) ($form['resource_title'] ?? ''));

        $contentLabel = match ($contentType) {
            'study'        => 'Estudo bíblico',
            'reading_plan' => 'Plano de leitura',
            'resource'     => 'Recurso ministerial',
            default        => 'Sermão expositivo',
        };

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
        $userPrompt .= "Linha confessional: {$confessional}\n";
        $userPrompt .= "Profundidade: {$depthLabel}\n\n";
        $userPrompt .= "Estruture a resposta com:\n";
        $userPrompt .= "1. Caminho exegético (contexto, estrutura, palavras-chave, teologia, eixo cristológico)\n";
        $userPrompt .= "2. Ponte de revisão pastoral (tema central, pergunta de controle, ajustes sugeridos)\n";
        $userPrompt .= "3. Desenvolvimento (introdução, movimentos, aplicação, conclusão)\n";
        $userPrompt .= "4. Materiais derivados (sermão, PG, EBD/discipulado, série)\n";
        $userPrompt .= "Encerre com uma nota pastoral curta enfatizando oração e revisão local.";

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
                error_log('[OpenAiService] HTTP ' . $status . ' err=' . $error . ' body=' . (is_string($response) ? substr($response, 0, 200) : '-'));
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
