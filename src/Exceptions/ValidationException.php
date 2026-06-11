<?php

namespace Vendor\HetznerCloud\Exceptions;

class ValidationException extends ApiException
{
    /**
     * Get the validation errors mapped by field name.
     *
     * @return array
     */
    public function getErrors(): array
    {
        $fields = $this->details['fields'] ?? [];
        $errors = [];

        foreach ($fields as $field) {
            if (isset($field['name']) && isset($field['message'])) {
                $errors[$field['name']] = (array) $field['message'];
            }
        }

        return $errors;
    }
}
