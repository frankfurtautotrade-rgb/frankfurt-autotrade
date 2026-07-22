<?php

declare(strict_types=1);

namespace App\Core;

final class Validator
{
    /**
     * Validation errors.
     */
    private array $errors = [];

    /**
     * Validate data against rules.
     */
    public function validate(array $data, array $rules): bool
    {
        $this->errors = [];

        foreach ($rules as $field => $ruleString) {

            $rulesArray = explode('|', $ruleString);

            $value = trim((string)($data[$field] ?? ''));

            foreach ($rulesArray as $rule) {

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
                        if (mb_strlen($value) < (int)$parameter) {
                            $this->addError(
                                $field,
                                "Minimum {$parameter} characters."
                            );
                        }
                        break;

                    case 'max':
                        if (mb_strlen($value) > (int)$parameter) {
                            $this->addError(
                                $field,
                                "Maximum {$parameter} characters."
                            );
                        }
                        break;

                    case 'min_value':
                        if ($value !== '' && (float)$value < (float)$parameter) {
                            $this->addError(
                                $field,
                                "Minimum value is {$parameter}."
                            );
                        }
                        break;

                    case 'max_value':
                        if ($value !== '' && (float)$value > (float)$parameter) {
                            $this->addError(
                                $field,
                                "Maximum value is {$parameter}."
                            );
                        }
                        break;

                    case 'url':
                        if ($value !== '' && !filter_var($value, FILTER_VALIDATE_URL)) {
                            $this->addError($field, 'Invalid URL.');
                        }
                        break;

                    case 'alpha':
                        if ($value !== '' && !preg_match('/^[a-zA-Z]+$/', $value)) {
                            $this->addError(
                                $field,
                                'Only alphabetic characters are allowed.'
                            );
                        }
                        break;

                    case 'alpha_num':
                        if ($value !== '' && !preg_match('/^[a-zA-Z0-9]+$/', $value)) {
                            $this->addError(
                                $field,
                                'Only letters and numbers are allowed.'
                            );
                        }
                        break;

                    case 'regex':
                        if ($value !== '' && $parameter !== null && !preg_match($parameter, $value)) {
                            $this->addError($field, 'Invalid format.');
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
     * Get all errors.
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Get first error.
     */
    public function first(string $field): ?string
    {
        return $this->errors[$field][0] ?? null;
    }

    /**
     * Determine if validation failed.
     */
    public function fails(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Determine if validation passed.
     */
    public function passes(): bool
    {
        return empty($this->errors);
    }
}