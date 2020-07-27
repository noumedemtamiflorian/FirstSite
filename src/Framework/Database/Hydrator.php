<?php


namespace App\Framework\Database;

use App\Blog\Entity\Post;
use function PHPUnit\Framework\isInstanceOf;

class Hydrator
{
    public static function hydrate(array $array, $object)
    {
        if (is_string($object)) {
            $instance = new  $object();
        } else {
            $instance = $object;
        }
        $instance = new  $object();
        foreach ($array as $key => $value) {
            $method = self::getSetter($key);
            if (method_exists($instance, $method)) {
                $instance->$method($value);
            } else {
                $property = lcfirst(self::getProperty($key));
                $instance->$property = $value;
            }
        }
        return $instance;
    }

    private static function getSetter(string $fieldName)
    {
        return 'set' . self::getProperty($fieldName);
    }

    private static function getProperty(string $fieldName)
    {
        return join('', array_map('ucfirst', explode('_', $fieldName)));
    }
}
