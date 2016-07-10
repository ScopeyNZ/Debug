<?php namespace ScopeyNZ\Debug;

/**
 * Used for returning objects which provide a fluent interface when debug mode is not on.
 */
class VoidObject
{
    function __call($name, $arguments)
    {
        return null;
    }

    public static function __callStatic($name, $arguments)
    {
        return null;
    }

    function __get($name)
    {
        return null;
    }

    function __set($name, $value)
    {
        return null;
    }

    function __toString()
    {
        return '';
    }

    function __invoke()
    {
        return null;
    }
}