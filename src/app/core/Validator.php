<?php

declare(strict_types=1);

namespace App\Core;

use DateTime;

final class Validator
{
    private const DATE_FORMATS = ['Y-m-d', 'Y-m-d\TH:i', 'Y-m-d H:i', 'Y-m-d H:i:s', 'Y-m-d\TH:i:s'];

    private array $errors = [];

    private function __construct(private array $data, private array $rules, private array $labels)
    {
        $this->run();
    }

    public static function make(array $data, array $rules, array $labels = []): self
    {
        return new self($data, $rules, $labels);
    }

    public function fails(): bool
    {
        return $this->errors !== [];
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function validated(): array
    {
        $validated = [];
        foreach (array_keys($this->rules) as $field) {
            $validated[$field] = $this->value($field);
        }

        return $validated;
    }

    private function run(): void
    {
        foreach ($this->rules as $field => $ruleString) {
            $rules = is_array($ruleString) ? $ruleString : explode('|', (string) $ruleString);
            $value = $this->value($field);
            $isEmpty = $value === '' || $value === [];

            if ($isEmpty) {
                if (in_array('required', $rules, true)) {
                    $this->errors[$field] = 'Vui lòng nhập ' . $this->label($field) . '.';
                }
                continue;
            }

            foreach ($rules as $rule) {
                [$ruleName, $parameter] = array_pad(explode(':', $rule, 2), 2, '');
                $message = $this->checkRule($field, $value, $ruleName, $parameter);
                if ($message !== null) {
                    $this->errors[$field] = $message;
                    break;
                }
            }
        }
    }

    private function checkRule(string $field, mixed $value, string $ruleName, string $parameter): ?string
    {
        $label = $this->label($field);
        if (!is_string($value) && $ruleName !== 'required') {
            return $label . ' không hợp lệ.';
        }

        return match ($ruleName) {
            'required' => null,
            'email' => filter_var($value, FILTER_VALIDATE_EMAIL) !== false
                ? null : $label . ' không đúng định dạng.',
            'max' => mb_strlen($value) <= (int) $parameter
                ? null : $label . ' tối đa ' . (int) $parameter . ' ký tự.',
            'min' => mb_strlen($value) >= (int) $parameter
                ? null : $label . ' tối thiểu ' . (int) $parameter . ' ký tự.',
            'phone' => preg_match('/^(0\d{9,10}|\+\d{8,14})$/', preg_replace('/[\s.\-]/', '', $value)) === 1
                ? null : $label . ' không đúng định dạng.',
            'date' => $this->isValidDate($value)
                ? null : $label . ' không đúng định dạng ngày.',
            'in' => in_array($value, explode(',', $parameter), true)
                ? null : $label . ' không hợp lệ.',
            'numeric' => is_numeric($value)
                ? null : $label . ' phải là số.',
            'confirmed' => $value === $this->value($field . '_confirmation')
                ? null : $label . ' xác nhận không khớp.',
            default => null,
        };
    }

    private function isValidDate(string $value): bool
    {
        foreach (self::DATE_FORMATS as $format) {
            $date = DateTime::createFromFormat('!' . $format, $value);
            if ($date !== false && $date->format($format) === $value) {
                return true;
            }
        }

        return false;
    }

    private function value(string $field): mixed
    {
        $value = $this->data[$field] ?? '';

        return is_string($value) ? trim($value) : $value;
    }

    private function label(string $field): string
    {
        return $this->labels[$field] ?? ucfirst(str_replace('_', ' ', $field));
    }
}
