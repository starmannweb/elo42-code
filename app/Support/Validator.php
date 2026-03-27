<?php

declare(strict_types=1);

namespace App\Support;

class Validator
{
    private array $errors = [];

    public function validate(array $data, array $rules): array
    {
        $this->errors = [];

        foreach ($rules as $field => $ruleSet) {
            $fieldRules = is_string($ruleSet) ? explode('|', $ruleSet) : $ruleSet;
            $value = $data[$field] ?? null;
            $label = str_replace('_', ' ', $field);

            foreach ($fieldRules as $rule) {
                $params = [];
                if (str_contains($rule, ':')) {
                    [$rule, $paramStr] = explode(':', $rule, 2);
                    $params = explode(',', $paramStr);
                }

                $this->applyRule($field, $label, $value, $rule, $params, $data);
            }
        }

        return [
            'valid'  => empty($this->errors),
            'errors' => $this->errors,
        ];
    }

    public function errors(): array
    {
        return $this->errors;
    }

    private function applyRule(string $field, string $label, mixed $value, string $rule, array $params, array $data): void
    {
        match ($rule) {
            'required'  => $this->validateRequired($field, $label, $value),
            'email'     => $this->validateEmail($field, $label, $value),
            'min'       => $this->validateMin($field, $label, $value, (int)($params[0] ?? 0)),
            'max'       => $this->validateMax($field, $label, $value, (int)($params[0] ?? 255)),
            'confirmed' => $this->validateConfirmed($field, $label, $value, $data),
            'numeric'   => $this->validateNumeric($field, $label, $value),
            'string'    => $this->validateString($field, $label, $value),
            default     => null,
        };
    }

    private function validateRequired(string $field, string $label, mixed $value): void
    {
        if (is_null($value) || (is_string($value) && trim($value) === '')) {
            $this->errors[$field][] = "O campo {$label} é obrigatório.";
        }
    }

    private function validateEmail(string $field, string $label, mixed $value): void
    {
        if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = "O campo {$label} deve ser um e-mail válido.";
        }
    }

    private function validateMin(string $field, string $label, mixed $value, int $min): void
    {
        if (is_string($value) && mb_strlen($value) < $min) {
            $this->errors[$field][] = "O campo {$label} deve ter pelo menos {$min} caracteres.";
        }
    }

    private function validateMax(string $field, string $label, mixed $value, int $max): void
    {
        if (is_string($value) && mb_strlen($value) > $max) {
            $this->errors[$field][] = "O campo {$label} não pode ter mais de {$max} caracteres.";
        }
    }

    private function validateConfirmed(string $field, string $label, mixed $value, array $data): void
    {
        $confirmField = $field . '_confirmation';
        if ($value !== ($data[$confirmField] ?? null)) {
            $this->errors[$field][] = "A confirmação de {$label} não confere.";
        }
    }

    private function validateNumeric(string $field, string $label, mixed $value): void
    {
        if ($value && !is_numeric($value)) {
            $this->errors[$field][] = "O campo {$label} deve ser numérico.";
        }
    }

    private function validateString(string $field, string $label, mixed $value): void
    {
        if ($value && !is_string($value)) {
            $this->errors[$field][] = "O campo {$label} deve ser texto.";
        }
    }
}
