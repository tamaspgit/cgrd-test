<?php

namespace CGRD\Core\Validator;

class Validator
{
    public static function validate(ValidatableInterface $model, array $data): array
    {
        $errors = [];
        $rules = $model->getValidationRules();

        foreach ($rules as $field => $ruleSet) {
            $value = $data[$field] ?? null;
            $ruleList = explode('|', $ruleSet);

            foreach ($ruleList as $rule) {
                $rule = trim($rule);

                if ($rule === 'required' && (is_null($value) || $value === '')) {
                    $errors[$field][] = 'This field is required.';
                }

                if (str_starts_with($rule, 'min:')) {
                    $min = (int) explode(':', $rule)[1];
                    if (is_string($value) && mb_strlen($value) < $min) {
                        $errors[$field][] = "Minimum length is $min characters.";
                    }
                }

                if (str_starts_with($rule, 'max:')) {
                    $max = (int) explode(':', $rule)[1];
                    if (is_string($value) && mb_strlen($value) > $max) {
                        $errors[$field][] = "Maximum length is $max characters.";
                    }
                }

                if ($rule === 'integer' && !filter_var($value, FILTER_VALIDATE_INT)) {
                    $errors[$field][] = 'Must be an integer.';
                }

                if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = 'Must be a valid email address.';
                }

                if (str_starts_with($rule, 'regex:')) {
                    $pattern = substr($rule, 6);
                    if (!preg_match($pattern, $value)) {
                        $errors[$field][] = 'Invalid format.';
                    }
                }

                if ($rule === 'date' && !($value instanceof \DateTime)) {
                    $errors[$field][] = 'Must be a valid date.';
                }

                if (str_starts_with($rule, 'unique:')) {
                    $callbackName = explode(':', $rule)[1];
                    if (method_exists($model, $callbackName)) {
                        $isUnique = $model->$callbackName($value, $data);
                        if (!$isUnique) {
                            $errors[$field][] = 'Value must be unique.';
                        }
                    } else {
                        throw new \Exception("Unique rule callback '$callbackName' not found on model.");
                    }
                }
            }
        }

        return $errors;
    }
}
