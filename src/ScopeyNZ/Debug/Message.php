<?php namespace ScopeyNZ\Debug;

use ScopeyNZ\Debug\Contract\Loggable;

class Message implements Loggable
{
    /**
     * The message to log
     *
     * @var string
     */
    protected $message;

    /**
     * @var int
     */
    protected $sort;

    /**
     * @param string $message
     * @param null $sort
     */
    public function __construct($message, $sort = null)
    {
        $this->message = $message;
        $this->sort = $sort;
    }

    public function getReadableMessage()
    {
        return $this->message;
    }

    public function getAsArray()
    {
        return array(
            'message' => $this->message,
        );
    }

    public function getSortPriority()
    {
        return $this->sort;
    }

    /**
     * @see Loggable::getCategoryName
     * @return string
     */
    public static function getCategoryName()
    {
        return 'Messages';
    }


}