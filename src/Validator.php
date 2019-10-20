<?php

namespace Genesis\TestJson;

/**
 * Validator class.
 */
class Validator
{
    public function schema($schema)
    {
        $this->schema = $this->parse($schema);

        return $this;
    }

    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * [
     *     [
     *         'abc' => int,
     *         'xyz' => ?string:regex,
     *         ''
     *     ]
     * ].
     * @param mixed $value
     * @param mixed $values
     */
    public function validate($values)
    {
        // Validate value against parsed schema.
        // [
        //     'name' => 'Abdul',
        //     'dob' => '10-05-1989',
        //     'age' => 30,
        //     'address' => [
        //         'line 1' => '54 george road',
        //         'city' => 'Birmingham',
        //         'post code' => 'B23 7QB',
        //         'country' => 'United Kingdom',
        //     ]
        // ];
        //
        // [
        //      'name' => 'string',
        //      'dob' => 'string:/[0-9]{2}-[0-9]{2}-[0-9]{4}/',
        //      'age' => 'int',
        //      'address' => [
        //          'line 1' => 'string',
        //          'city' => 'string',
        //          'post code' => 'string',
        //          'country' => 'string',
        //      ]
        // ]

        // Validate that all passed in conforms to schema.
        // The schema forms the basis of validation so loop around the
        // schema to check the values.
        foreach ($this->schema as $index => $value) {
            if (is_array($value)) {
                $this->validate($value);
            } else {
                $this->validateValue(
                    $values[$index],
                    $value['type'],
                    $value['regex'],
                    $value['required']
                );
            }
        }
    }

    private function validateValue($value, string $type, string $regex = null, bool $optional = true)
    {
        switch(strtolower(gettype($value))) {
            case 'null':
                if (!$optional) {
                    throw new Exception('Provided value is not expected to be nullable.');
                }
                return;
            case 'array':
            case 'object':
            case 'resource':
                throw new Exception('Unsupported type: ' . strtolower(gettype($value)));
        }

        if (strtolower(gettype($value)) !== strtolower($type)) {
            throw new Exception('Value does not match data type specified: ' . $type);
        }

        if ($regex && preg_match($regex, $value) === false) {
            throw new Exception('Value does not match provided regex: ' . $regex);
        }
    }

    private function parse($schema)
    {
        $parsedSchema = [];

        foreach ($schema as $key => $value) {
            // If its an array or object, then parse further.
            if (is_array($value)) {
                if (is_integer($key)) {
                    $parsedSchema[] = $this->parse($value);
                } else {
                    $parsedSchema[$key] = $this->parse($value);
                }
            } else {
                // If value is scalar, then parse it as is
                list($required, $type, $regex) = $this->parseRule($value);
                $parsedSchema[$key] = [
                    'type' => $type,
                    'regex' => $regex,
                    'required' => $required,
                ];
            }
        }

        return $parsedSchema;
    }

    private function parseRule($rule)
    {
        return [
            $this->isRequired($rule),
            $this->getType($rule),
            $this->getRegex($rule),
        ];
    }

    private function isRequired(string $rule): bool
    {
        return strpos($rule, '?') === 0 ? false : true;
    }

    private function getType(string $rule): string
    {
        return explode(':', ltrim($rule, '?'))[0];
    }

    private function getRegex(string $rule): ?string
    {
        $chunks = explode(':', ltrim($rule, '?'));

        return $chunks[1] ?? null;
    }
}
