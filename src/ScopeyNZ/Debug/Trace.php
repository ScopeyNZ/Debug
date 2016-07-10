<?php namespace ScopeyNZ\Debug;

class Trace
{
    /**
     * @param array $ignoreClasses List of callers to ignore
     */
    public static function getSingleEntry($ignoreClasses = array())
    {
        $stack = self::generate();

        $ignoreClasses = array_merge(array(__CLASS__), $ignoreClasses);

        $stackPosition = 1;
        while (isset($stack[$stackPosition])) {
            $entry = $stack[$stackPosition];

            if (!isset($entry['class'])) {
                break;
            }
            if (!in_array($entry['class'], $ignoreClasses)) {
                break;
            }

            $stackPosition++;
        }

        return $stack[$stackPosition - 1];
    }

    public static function generate()
    {
        return debug_backtrace();
    }
}