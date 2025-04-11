<?php

namespace CGRD\Core\Validator;

interface ValidatableInterface
{
    public function getValidationRules(): array;
}
