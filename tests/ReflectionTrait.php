<?php

trait ReflectionTrait
{
    public function callPrivateMethod(object $object, string $name, array $args): mixed
    {
        $class = new ReflectionClass($object);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $args);
    }

    public function setStaticPropertyValue(object|string $object, string $property, mixed $value): void
    {
        $class = new ReflectionClass($object);
        $class->setStaticPropertyValue($property, $value);
    }

    public function getConstants(object|string $object): array
    {
        $class = new ReflectionClass($object);

        return $class->getConstants();
    }

    public function setPrivatePropertyValue(object $object, string $name, mixed $value): void
    {
        $class = new ReflectionClass($object);
        $property = $class->getProperty($name);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }

    public function getPrivatePropertyValue(object $object, string $name): mixed
    {
        $class = new ReflectionClass($object);
        $property = $class->getProperty($name);
        $property->setAccessible(true);

        return $property->getValue($object);
    }
}
