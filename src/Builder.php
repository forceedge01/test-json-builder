<?php

namespace Genesis\TestJson;

/**
 * Builder class.
 */
class Builder
{
    private $json;

    public function __construct()
    {
        $this->json = [];
        $this->dataGenerator = new DataGenerator();
    }

    public function setValidator($validator)
    {
        $this->validator = $validator;

        return $this;
    }

    public function setGenerator($dataGenerator)
    {
        $this->dataGenerator = $dataGenerator;

        return $this;
    }

    public function addArray($value)
    {
        $this->json[] = $value;

        return $this;
    }

    public function add($value, $key = null)
    {
        if ($value instanceof Builder) {
            $value = $value->get();
        }

        if ($key) {
            $this->json[$key] = $value;
        } else {
            $this->json[] = $value;
        }

        return $this;
    }

    public function get()
    {
        return $this->json;
    }

    public function validate()
    {
        $this->validator->validate($this->json);

        return $this;
    }

    public function build()
    {
        if ($this->validator) {
            $this->json = $this->dataGenerator->generate(
                $this->json,
                $this->validator->getSchema()
            );
        }

        return json_encode($this->json);
    }
}
