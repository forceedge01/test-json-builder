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

    public function addScalar($value, $key = null)
    {
        $this->json[$key] = $value;

        return $this;
    }

    public function build()
    {
        if ($this->validator) {
            $this->json = $this->dataGenerator->generate(
                $this->json,
                $this->validator->getSchema()
            );

            $this->validator->validate($this->json);
        }

        return json_encode($this->json);
    }
}
