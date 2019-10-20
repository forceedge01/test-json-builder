<?php

use PHPUnit_Framework_TestCase;
use ReflectionClass;

class ValidatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ValidatorInterface The object to be tested.
     */
    private $testObject;

    /**
     * @var ReflectionClass The reflection class.
     */
    private $reflection;

    /**
     * @var array The test object dependencies.
     */
    private $dependencies = [];

    /**
     * Set up the testing object.
     */
    public function setUp()
    {
        $this->dependencies = [
            'Name of dependency' => null, //nmock
        ];

        $this->reflection = new ReflectionClass(Validator::class);
        $this->testObject = $this->reflection->newInstanceArgs($this->dependencies);
    }

    //tmethod
}