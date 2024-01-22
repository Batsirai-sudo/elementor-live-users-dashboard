<?php

namespace Tests\Traits;

use ReflectionClass;
use ReflectionException;

trait HasReflectionHelper
{
    private function setClassProperty($object, $propertyName, $value): void
    {
        $reflection = new ReflectionClass($object);
        $property = $reflection->getProperty($propertyName);
        $property->setValue($object, $value);
    }
}