<?php

namespace Genesis\TestJson;

/**
 * DataGenerator class.
 */
class DataGenerator
{
    public function generate($values, $schema)
    {
        return $this->generateData($values, $schema);
    }

    private function generateData($values, $schema)
    {
        foreach ($schema as $index => $rule) {
            // If scalar, then value changes.
            if (is_array($values[$index])) {
                $schema[$index] = $this->generateData($values[$index], $rule);
            } else {
                if (isset($values[$index])) {
                    $schema[$index] = $this->getData($rule);
                }
            }
        }

        return $schema;
    }

    public function getData($rule)
    {
        switch ($rule['type']) {
            case 'string':
                return 'test-string-' . rand(1, 99999999);

            case 'integer':
                return rand(1, 99999999);

            case 'double':
                return rand(0.01, 0.99);

            case 'boolean':
                return true;

            default:
                return 'random-' . rand(1, 9999999);
        }
    }
}
