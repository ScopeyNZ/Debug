<?php namespace ScopeyNZ\Debug;

use ScopeyNZ\Debug\Contract\Loggable;
use ScopeyNZ\Debug\Exception as DebugException;

class Timer implements Loggable
{
    /**
     * @var float A timestamp of the start time of this timer 
     */
    protected $start;

    /**
     * @var float A timestamp of the end time of this timer
     */
    protected $end;

    /**
     * @var string The name of this timer
     */
    protected $name;

    /**
     * Timer constructor.
     * @param string $name
     * @param boolean $defer - Indicates this timer should not autostart
     */
    public function __construct($name, $defer = false)
    {
        $this->name = $name;
        
        if (!$defer) {
            $this->start();
        }
    }

    /**
     * Start the timer
     *
     * @throws Exception
     */
    public function start()
    {
        if (!empty($this->start)) {
            throw new DebugException('Timers cannot be started twice');
        }
        $this->start = $this->getTimestamp();
    }

    /**
     * Stop the timer
     *
     * @throws Exception
     */
    public function stop()
    {
        if (empty($this->start)) {
            throw new DebugException('Timers cannot be stopped unless they have started.');
        }
        if (!empty($this->end)) {
            return;
        }
        $this->end = $this->getTimestamp();
    }

    /**
     * Get the time once the timer has completed
     *
     * @return float
     * @throws Exception
     */
    public function getTime()
    {
        if (empty($this->start) || empty($this->end)) {
            throw new DebugException('Timers cannot return a time until they have run');
        }
        return $this->end - $this->start;
    }

    /**
     * Internal method - returns a current timestamp
     *
     * @return mixed
     */
    protected function getTimestamp()
    {
        return microtime(true);
    }

    /**
     * @see Loggable::getReadableMessage
     * @return string
     * @throws Exception
     */
    public function getReadableMessage()
    {
        return 'Timer "'.$this->name.'" took '.round($this->getTime(), 4).'s';
    }

    /**
     * @see Loggable::getAsArray
     * @return string
     * @throws Exception
     */
    public function getAsArray()
    {
        return array(
            'name' => $this->name,
            'time' => $this->getTime(),
        );
    }

    /**
     * @see Loggable::getSortPriority
     * @return float
     * @throws Exception
     */
    public function getSortPriority()
    {
        // Sort by time taken.
        return $this->getTime();
    }

    /**
     * @see Loggable::getCategoryName
     * @return string
     */
    public static function getCategoryName()
    {
        return 'Timers';
    }


}