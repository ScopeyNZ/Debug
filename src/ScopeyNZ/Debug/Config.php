<?php namespace ScopeyNZ\Debug;

use ScopeyNZ\Debug\Config\Exception as ConfigException;

class Config implements \ArrayAccess
{
    protected static $config;

    protected $scope;

    /**
     * Singleton
     */
    protected function __construct($configScope)
    {
        $this->scope = $configScope;
    }

    public static function get($part = null)
    {
        if (!is_string($part)) {
            throw new ConfigException('Config options can only be keyed by strings');
        }
        
        if ($part) {
            if (!isset(self::$config[$part])) {
                throw new ConfigException('Option "'.$part.'" does not exist in this configuration');
            }
            if (is_array(self::$config[$part])) {
                return new static(self::$config[$part]);
            }
            return self::$config[$part];

        }
        return new static(self::$config); 
    }

    public static function configure($config)
    {
        if (self::$config === null) {
            throw new ConfigException('Debug config can only be configured once');
        }

        self::$config = (array) $config;
    }
    
    

    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }

    public function offsetGet($offset)
    {
        // TODO: Implement offsetGet() method.
    }

    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }


}