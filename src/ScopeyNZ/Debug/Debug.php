<?php namespace ScopeyNZ\Debug;

use ScopeyNZ\Debug\Contract\Loggable;

class Debug
{
    const DEFAULT_FILENAME_BASE = 'application_debug';
    const DEFAULT_FILENAME_SUFFIX = 'log';

    protected static $enabled = false;
    protected static $logs = array();
    
    protected $config;

    public static function log($message, $sort = null)
    {
        $object = new Message($message, $sort);
        if (self::isEnabled()) {
            self::$logs[] = $object;
        }
        return $object;
    }

    public static function logSql($statementName, $statement, array $params = array(), $deferTimer = false)
    {
        $object = new Sql($statementName, $statement, $params, $deferTimer);
        if (self::isEnabled()) {
            self::$logs[] = $object;
        }
        return $object;
    }

    public static function logTimer($name, $defer)
    {
        if (!self::isEnabled()) {
            return new VoidObject();
        }
        return self::$logs[] = new Timer($name, $defer);
    }

    public static function enable()
    {
        self::$enabled = true;
    }

    public static function disable()
    {
        self::$enabled = false;
    }

    public static function isEnabled()
    {
        return self::$enabled;
    }

    /**
     * Save all logs generated in this request to an optional filename and directory
     *
     * @param null $fileName
     * @param null $directory
     */
    public static function saveLog($fileName = null, $directory = null)
    {
        if (!self::isEnabled()) {
            return;
        }

        // Assert the directory is accessible
        $directory = File::isDirectoryAccessible($directory) ? $directory : File::getTempDirectory();

        if (!$fileName) {
            // Default just appends the pid to the defined constant default
            $fileName = self::DEFAULT_FILENAME_BASE.'.'.getmypid().'.'.self::DEFAULT_FILENAME_SUFFIX;
        }

        $fileFormat = '----- SUMMARY -----'.PHP_EOL.PHP_EOL.'%s'.PHP_EOL.PHP_EOL.'----- PARSABLE -----'.PHP_EOL.PHP_EOL.
                      '%s';

        $summaryComponents = array();
        $arrayComponents = array();

        foreach (self::$logs as $log) {
            if (!$log instanceof Loggable) {
                continue;
            }

            $summaryComponents[] = $log->getReadableMessage();

            $arrayComponents[] = $log->getAsArray() + array(
                'sort' => $log->getSortPriority(),
                'category' => $log::getCategoryName(),
            );
        }

        File::saveToFile(
            $directory.'/'.$fileName,
            sprintf($fileFormat, implode(PHP_EOL, $summaryComponents), json_encode($arrayComponents))
        );
    }
}