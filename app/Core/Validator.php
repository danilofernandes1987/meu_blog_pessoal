<?php
// app/Core/Validator.php
namespace App\Core;

class Validator
{
    private $data;
    private $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Valida os dados com base em um conjunto de regras.
     * @param array $rules Regras no formato ['campo' => 'regra1|regra2:parametro'].
     */
    public function validate(array $rules): bool
    {
        foreach ($rules as $field => $ruleString) {
            $rulesArray = explode('|', $ruleString);
            $value = $this->data[$field] ?? null;

            foreach ($rulesArray as $rule) {
                $ruleName = $rule;
                $ruleParam = null;

                if (strpos($rule, ':') !== false) {
                    list($ruleName, $ruleParam) = explode(':', $rule, 2);
                }
                
                // Constrói o nome do método de validação
                $methodName = 'validate' . ucfirst($ruleName);

                // Se o método de validação existir, chama-o
                if (method_exists($this, $methodName)) {
                    $this->{$methodName}($field, $value, $ruleParam);
                }
            }
        }

        return empty($this->errors);
    }

    /**
     * Retorna true se a validação falhou.
     * @return bool
     */
    public function fails(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Retorna o array de erros de validação.
     * @return array
     */
    public function errors(): array
    {
        return $this->errors;
    }

    // --- MÉTODOS DE VALIDAÇÃO ---

    private function validateRequired($field, $value, $param)
    {
        if (empty(trim((string)$value))) {
            $this->errors[$field] = "O campo {$field} é obrigatório.";
        }
    }

    private function validateEmail($field, $value, $param)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "O campo {$field} deve ser um endereço de e-mail válido.";
        }
    }

    private function validateMin($field, $value, $param)
    {
        if (strlen($value) < $param) {
            $this->errors[$field] = "O campo {$field} deve ter no mínimo {$param} caracteres.";
        }
    }

    private function validateSlug($field, $value, $param)
    {
        if (!preg_match('/^[a-z0-9-]+$/', $value)) {
            $this->errors[$field] = 'O campo slug deve conter apenas letras minúsculas, números e hífens.';
        }
    }

    // Você pode adicionar mais regras aqui (ex: max, numeric, matches, etc.)
}
