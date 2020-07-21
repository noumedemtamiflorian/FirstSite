<?php


namespace App\Framework\Database;


class Hydrator
{
    public static function hydrate(array $array, $object)
    {
        $instance = new  $object();
        foreach ($array as $key => $value) {
            $method = self::getSetter($key);
            if (method_exists($instance, $method)) {
                return $instance->$method($value);
            } else {
                $property = lcfirst(self::getProperty($key));
                return $instance->$property = $value;
            }
        }
    }

    private static function getSetter(string $fieldName)
    {
        return 'set' . self::getProperty($fieldName);
    }

    private static function getProperty(string $fieldName)
    {
        return join('', array_map('ucfirst', explode('-', $fieldName)));
    }
}