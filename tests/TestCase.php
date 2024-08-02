<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use ReflectionException;

abstract class TestCase extends BaseTestCase
{
    /**
     * @param $object
     * @param $methodName
     * @param $parameters
     * @return mixed
     * @throws ReflectionException
     */
    public function callPrivateMethod(&$object, $methodName, $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $parameters);
    }
}
