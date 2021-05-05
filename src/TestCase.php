<?php

declare(strict_types=1);

namespace Ostiwe\PhpUnitHelpers;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use ReflectionClass;

/**
 * Класс с хелпер-методами для тестов.
 */
abstract class TestCase extends PHPUnitTestCase
{
    /**
     * Вызываем private/protected метод объекта.
     *
     * @param object    $object     Объект, из которого будет получено свойство
     * @param string    $method     Имя свойства
     * @param array     $parameters Параметры метода
     *
     * @throws \ReflectionException
     * @throws \Exception
     *
     * @return mixed
     */
    protected function callMethod($object, string $method , array $parameters = [])
    {
        $className = get_class($object);
        $reflection = new ReflectionClass($className);

        $method = $reflection->getMethod($method);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * Получаем private/protected свойство объекта.
     *
     * @param object      $object    Объект, из которого будет получено свойство
     * @param string      $name      Имя свойства
     * @param null|string $className Имя класса, в котором объявлено свойство
     *
     * @throws \ReflectionException
     *
     * @return mixed значение свойства
     */
    protected function getPrivateProperty(object $object, string $name, string $className = null)
    {
        $reflectionClass = new ReflectionClass(null === $className ? $object : $className);

        if ($reflectionClass->hasProperty($name)) {
            $property = $reflectionClass->getProperty($name);
            $property->setAccessible(true);

            return $property->getValue($object);
        }
        $parent = $reflectionClass->getParentClass();
        if ($parent) {
            return $this->getPrivateProperty($object, $name, $parent->getName());
        }

        return $object->{$name};
    }

    /**
     * Получаем private/protected статическое свойство объекта.
     *
     * @param string $className имя класс, в котором объявлено свойство
     * @param string $name      Имя свойства
     *
     * @throws \ReflectionException
     *
     * @return mixed значение свойства
     */
    protected function getPrivateStaticProperty(string $className, string $name)
    {
        $reflectedClass = new ReflectionClass($className);
        $reflectedProperty = $reflectedClass->getProperty($name);

        return $reflectedProperty->getValue($name);
    }

    /**
     * Метод, выполняющий подмену значения для защищенного свойства класса.
     *
     * @param object      $object    Объект, в котором будет заменено значение свойства
     * @param string      $name      Имя свойства
     * @param mixed       $value     Новое значение свойства
     * @param null|string $className Имя класса, в котором объявлено свойство
     *
     * @throws \ReflectionException
     */
    protected function setPrivateProperty(object $object, string $name, $value, string $className = null)
    {
        $reflectionClass = new ReflectionClass(null === $className ? $object : $className);

        if ($reflectionClass->hasProperty($name)) {
            $property = $reflectionClass->getProperty($name);
            $property->setAccessible(true);
            $property->setValue($object, $value);
        } else {
            $parent = $reflectionClass->getParentClass();
            if ($parent) {
                $this->setPrivateProperty($object, $name, $value, $parent->getName());
            } else {
                $object->{$name} = $value;
            }
        }
    }

    /**
     * Установить private/protected статическое свойство объекта.
     *
     * @param string $className имя класс, в котором объявлено свойство
     * @param string $name      Имя свойства
     * @param mixed  $value     значение свойства
     *
     * @throws \ReflectionException
     */
    protected function setPrivateStaticProperty(string $className, string $name, $value)
    {
        $reflectedClass = new ReflectionClass($className);
        $reflectedProperty = $reflectedClass->getProperty($name);
        $reflectedProperty->setAccessible(true);
        $reflectedProperty->setValue($value);
    }
}
