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

    private function generateData($schema)
    {
        foreach ($schema as $index => $value) {
            // If scalar, then value changes.
            if (is_array($value)) {
                $schema[$key] = $this->generateData($value);
            } else {
                $schema[$key] = $this->getData($value);
            }
        }

        return $schema;
    }

    public function getData($value)
    {
        switch (gettype($value)) {
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
