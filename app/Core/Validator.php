<?php

declare(strict_types=1);

namespace Core;

final class Validator
{
    private array $data;
    private array $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Validate according to the supplied rules.
     */
    public function validate(array $rules): bool
    {
        foreach ($rules as $field => $fieldRules) {

            $value = trim((string)($this->data[$field] ?? ''));

            foreach ($fieldRules as $rule) {

                $parameter = null;

                if (str_contains($rule, ':')) {
                    [$rule, $parameter] = explode(':', $rule, 2);
                }

                switch ($rule) {

                    case 'required':
                        if ($value === '') {
                            $this->addError($field, 'This field is required.');
                        }
                        break;

                    case 'email':
                        if ($value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $this->addError($field, 'Invalid email address.');
                        }
                        break;

                    case 'numeric':
                        if ($value !== '' && !is_numeric($value)) {
                            $this->addError($field, 'Must be numeric.');
                        }
                        break;

                    case 'integer':
                        if ($value !== '' && filter_var($value, FILTER_VALIDATE_INT) === false) {
                            $this->addError($field, 'Must be an integer.');
                        }
                        break;

                    case 'min':
                        if ($parameter !== null && mb_strlen($value) < (int)$parameter) {
                            $this->addError($field, "Minimum {$parameter} characters.");
                        }
                        break;

                    case 'max':
                        if ($parameter !== null && mb_strlen($value) > (int)$parameter) {
                            $this->addError($field, "Maximum {$parameter} characters.");
                        }
                        break;

                    case 'min_value':
                        if ($parameter !== null && $value !== '' && (float)$value < (float)$parameter) {
                            $this->addError($field, "Minimum value is {$parameter}.");
                        }
                        break;

                    case 'max_value':
                        if ($parameter !== null && $value !== '' && (float)$value > (float)$parameter) {
                            $this->addError($field, "Maximum value is {$parameter}.");
                        }
                        break;

                    case 'date':
                        if ($value !== '' && strtotime($value) === false) {
                            $this->addError($field, 'Invalid date.');
                        }
                        break;

                    case 'url':
                        if ($value !== '' && !filter_var($value, FILTER_VALIDATE_URL)) {
                            $this->addError($field, 'Invalid URL.');
                        }
                        break;
                }
            }
        }

        return empty($this->errors);
    }

    /**
     * Add an error.
     */
    private function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    /**
     * Return all validation errors.
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Return the first error for a field.
     */
    public function first(string $field): ?string
    {
        return $this->errors[$field][0] ?? null;
    }

    /**
     * Determine whether a field has errors.
     */
    public function has(string $field): bool
    {
        return isset($this->errors[$field]);
    }

    /**
     * Determine whether validation failed.
     */
    public function fails(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Determine whether validation passed.
     */
    public function passes(): bool
    {
        return empty($this->errors);
    }
}