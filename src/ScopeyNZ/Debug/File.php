<?php namespace ScopeyNZ\Debug;

class File
{
    public static function getTempDirectory()
    {
        if (self::isDirectoryAccessible($directory = sys_get_temp_dir())) {
            return $directory;
        }

        return null;
    }

    public static function isDirectoryAccessible($directory)
    {
        return is_readable($directory) && is_writable($directory);
    }

    /**
     * Overwrite the contents of the given file with the content provided
     *
     * @param $fileName
     * @param $content
     */
    public static function saveToFile($fileName, $content)
    {
        $fh = fopen($fileName, 'w');
        fwrite($fh, $content);
        fclose($fh);
    }
}