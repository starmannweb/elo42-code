<?php

declare(strict_types=1);

namespace App\Features\MinistryAi;

final class PromptBuilder
{
    public static function build(string $workflowId, array $inputPayload): array
    {
        $workflow = WorkflowRegistry::getWorkflow($workflowId);
        if ($workflow === null) {
            throw new \InvalidArgumentException('Fluxo invalido.');
        }

        $title = (string) ($workflow['title'] ?? 'Geracao ministerial');
        $payloadSummary = self::payloadSummary($workflow, $inputPayload);

        $userPrompt = <<<PROMPT
Fluxo solicitado: {$title}

Dados informados pelo lider:
{$payloadSummary}

Formato esperado:
Responda em Markdown, com titulos claros, organizacao pastoral e linguagem natural.

Estrutura obrigatoria:
{$workflowId}:
PROMPT;

        $userPrompt .= "\n" . self::expectedStructure($workflowId);

        return [
            'systemPrompt' => self::systemPrompt(),
            'userPrompt' => $userPrompt,
            'expectedFormat' => 'markdown',
            'title' => $title,
        ];
    }

    public static function systemPrompt(): string
    {
        return <<<PROMPT
Voce atua como assistente ministerial cristao dentro do Elo 42, apoiando a preparacao de sermoes, estudos biblicos, discipulados, aulas, treinamentos e planejamentos pastorais.

Diretrizes fixas:
1. Auxilie com estrutura, clareza, organizacao, profundidade biblica e aplicacao pastoral.
2. Nao substitua o pastor, a lideranca local ou o discernimento comunitario.
3. Nao afirme revelacoes pessoais.
4. Nao tome decisoes espirituais pela lideranca da igreja.
5. Nao crie doutrina nova.
6. Use linguagem reverente, biblica, pastoral e clara.
7. Trate camadas confessionais como referencia historica auxiliar, sem substituir a Escritura.
8. Sempre recomende revisao pastoral, conferencia biblica e adaptacao ao contexto local.
9. Responda em portugues do Brasil.
10. Entregue conteudo estruturado em Markdown.
11. Evite tom robotico.
12. Nao apresente o conteudo como decisao final.
13. Sugira que o lider adapte o material a realidade da igreja local.
PROMPT;
    }

    public static function fallbackMarkdown(string $workflowId, array $payload): string
    {
        $workflow = WorkflowRegistry::getWorkflow($workflowId);
        $title = (string) ($workflow['title'] ?? 'Material ministerial');
        $summary = $workflow ? self::payloadSummary($workflow, $payload) : '- Dados nao reconhecidos';

        return <<<MARKDOWN
# {$title}

Nao foi possivel concluir a geracao pela API neste momento. Abaixo esta um roteiro de revisao para preservar o trabalho preenchido e orientar a proxima tentativa.

## Dados informados
{$summary}

## Proxima acao sugerida
Revise os campos, confirme se a chave e o modelo da OpenAI estao configurados no Admin Master e tente gerar novamente.

## Observacao pastoral
Use este material apenas como preparacao inicial. Todo conteudo deve ser revisado biblicamente e adaptado ao contexto da igreja local.
MARKDOWN;
    }

    private static function payloadSummary(array $workflow, array $inputPayload): string
    {
        $lines = [];
        foreach (($workflow['fields'] ?? []) as $field) {
            $name = (string) ($field['name'] ?? '');
            if ($name === '') {
                continue;
            }

            $value = trim((string) ($inputPayload[$name] ?? ''));
            if ($value === '') {
                continue;
            }

            $label = (string) ($field['label'] ?? $name);
            $lines[] = '- ' . $label . ': ' . self::humanOptionValue($field, $value);
        }

        return $lines ? implode("\n", $lines) : '- Nenhum dado detalhado foi informado.';
    }

    private static function humanOptionValue(array $field, string $value): string
    {
        foreach (($field['options'] ?? []) as $option) {
            if ((string) ($option[0] ?? '') === $value) {
                return (string) ($option[1] ?? $value);
            }
        }

        return $value;
    }

    private static function expectedStructure(string $workflowId): string
    {
        return match ($workflowId) {
            'gerar_sermao' => <<<TEXT
1. Titulo do sermao
2. Texto base
3. Ideia central
4. Objetivo pastoral
5. Introducao
6. Contexto biblico e historico
7. Estrutura homiletica
8. Desenvolvimento ponto a ponto
9. Aplicacoes pastorais
10. Possiveis ilustracoes
11. Conclusao
12. Chamado a resposta
13. Observacoes para revisao pastoral
TEXT,
            'refinar_rascunho' => <<<TEXT
1. Diagnostico do rascunho
2. Estrutura refinada
3. Introducao melhorada
4. Desenvolvimento reorganizado
5. Transicoes sugeridas
6. Aplicacoes fortalecidas
7. Conclusao sugerida
8. Observacoes pastorais
Importante: refine sem substituir a voz original do pastor.
TEXT,
            'culto_ocasional' => <<<TEXT
1. Titulo da mensagem
2. Contexto pastoral da ocasiao
3. Texto biblico central
4. Roteiro sugerido
5. Mensagem principal
6. Tom adequado para a ocasiao
7. Aplicacao pastoral
8. Oracao sugerida
9. Observacoes sensiveis
TEXT,
            'aula_ebd' => <<<TEXT
1. Titulo da aula
2. Objetivo da aula
3. Texto base
4. Verdade central
5. Abertura/pergunta inicial
6. Explicacao biblica
7. Desenvolvimento em blocos
8. Perguntas para interacao
9. Aplicacoes praticas
10. Atividade opcional
11. Resumo final
12. Oracao sugerida
13. Observacoes para o professor
TEXT,
            'estudo_exegetico' => <<<TEXT
1. Texto analisado
2. Delimitacao da pericope
3. Contexto literario
4. Contexto historico
5. Estrutura do texto
6. Palavras e temas-chave
7. Argumento central
8. Enfases teologicas
9. Pontes para pregacao ou ensino
10. Aplicacoes pastorais
11. Alertas interpretativos
12. Observacoes para revisao
TEXT,
            'estudo_biblico' => <<<TEXT
1. Titulo do estudo
2. Objetivo
3. Texto ou tema base
4. Explicacao biblica
5. Desenvolvimento tematico
6. Perguntas de reflexao
7. Aplicacoes praticas
8. Sugestao de oracao
9. Observacoes pastorais
TEXT,
            'curriculo_escola_dominical' => <<<TEXT
1. Nome da serie
2. Publico-alvo
3. Objetivo pedagogico geral
4. Visao geral da trilha
5. Lista de licoes. Para cada licao: titulo, texto biblico, objetivo, doutrina/verdade central, resumo, perguntas e aplicacao
6. Recomendacoes ao professor
7. Observacoes pastorais
TEXT,
            'roteiro_discipulado' => <<<TEXT
Gere 12 encontros. Para cada encontro: titulo, objetivo, texto biblico, verdade central, conteudo resumido, perguntas para conversa, exercicio pratico da semana, oracao sugerida e alerta pastoral quando necessario.
TEXT,
            'discipulado_casais' => <<<TEXT
Gere 7 encontros. Para cada encontro: titulo, texto biblico, objetivo, conteudo, perguntas para o casal, exercicio pratico, alerta pastoral e oracao sugerida.
Inclua nota de cuidado para temas sensiveis, violencia, abuso, vicios ou crises graves, recomendando acompanhamento pastoral direto e, quando necessario, profissional especializado.
TEXT,
            'treinamento_lideranca' => <<<TEXT
1. Diagnostico do contexto
2. Objetivo da trilha
3. Perfil de lideranca desejado
4. Encontros sugeridos. Para cada encontro: titulo, objetivo, texto biblico, competencia ministerial, conteudo, perguntas e exercicio pratico
5. Recomendacoes ao pastor
6. Indicadores de maturidade
TEXT,
            'pequenos_grupos' => <<<TEXT
1. Visao do ciclo
2. Objetivo pastoral
3. Estrutura do ciclo
4. Encontros organizados por introducao, desenvolvimento, consolidacao e envio. Para cada encontro: titulo, texto biblico, objetivo, dinamica de abertura, conteudo, perguntas, aplicacao e oracao
5. Recomendacoes ao lider
TEXT,
            'plano_anual_igreja' => <<<TEXT
1. Sintese pastoral do momento atual
2. Tema norteador sugerido para o ano
3. Texto biblico ancora
4. 4 a 6 pilares ministeriais
5. Diagnostico de forcas e fragilidades
6. Enfases espirituais prioritarias
7. Distribuicao anual por trimestre
8. Sugestoes de series de sermoes
9. Sugestoes para EBD e discipulado
10. Acoes para lideranca
11. Indicadores pastorais de acompanhamento
12. Riscos a observar
13. Proximos passos para revisao com a lideranca
Importante: apresente como material de discernimento assistido, nao como decisao final automatica.
TEXT,
            default => 'Entregue um material ministerial estruturado, revisavel e adaptavel ao contexto local.',
        };
    }
}
