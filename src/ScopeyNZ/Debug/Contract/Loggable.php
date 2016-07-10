<?php namespace ScopeyNZ\Debug\Contract;

interface Loggable
{
    /**
     * Return the message to log that can be read by users
     *
     * @return mixed
     */
    public function getReadableMessage();

    /**
     * Return the message to log that is provided as an array
     *
     * @return array
     */
    public function getAsArray();

    /**
     * Return an integer (or null) that indicates how this entry should be sorted against other entries of the same
     * type
     *
     * @return mixed
     */
    public function getSortPriority();

    /**
     * Get the name for logs of this type to be listed under in a cumulative log
     *
     * @return mixed
     */
    public static function getCategoryName();
}