<?php namespace ScopeyNZ\Debug;

use ScopeyNZ\Debug\Contract\Loggable;

class Sql implements Loggable
{
    /**
     * The SQL statement that is being run
     *
     * @var string
     */
    protected $sql;

    /**
     * The parameters of the SQL that is being run
     *
     * @var array
     */
    protected $params;

    /**
     * A name for this SQL statement
     *
     * @var string
     */
    protected $name;

    /**
     * A timer for the duration of this SQL statement
     *
     * @var Timer
     */
    protected $timer;

    /**
     * @var mixed
     */
    protected $result;

    /**
     * @var int
     */
    protected $rowCount;

    /**
     * @var int
     */
    protected $count;

    /**
     * @var array
     */
    protected $caller;
    
    /**
     * Keeps a static count of queries so the order of this query in the request can be logged 
     * 
     * @var int
     */
    protected static $queryCount = 1;

    /**
     * Sql constructor.
     * @param string $sql
     * @param array $params
     * @param string $name
     * @param bool $deferTimer
     */
    public function __construct($name, $sql, array $params = array(), $deferTimer = false)
    {
        if (!Debug::isEnabled()) {
            $this->timer = new VoidObject();
            return;
        }
        $this->sql = $sql;
        $this->params = $params;
        $this->name = $name;
        $this->timer = new Timer($name.' timer', $deferTimer);
    }

    public function timer()
    {
        return $this->timer;
    }

    public function complete($result = null, $rowCount = null)
    {
        if (!Debug::isEnabled()) {
            return;
        }
        $this->timer()->stop();

        $this->result = $result;
        $this->rowCount = $rowCount;
        
        $this->count = self::$queryCount++;

        $this->caller = array_intersect_key(
            Trace::getSingleEntry(array_merge(array(__CLASS__), self::$sqlClasses)),
            array_flip(array('file', 'line'))
        );
    }

    public function getReadableMessage()
    {
        return 'SQL Statement #'.$this->count.': "'.$this->name.'". Took '.round($this->timer()->getTime() * 1000, 2).
               'ms. ';
    }

    public function getAsArray()
    {
        $result = array(
            'count' => $this->count,
            'name' => $this->name,
            'time' => $this->timer()->getTime() * 1000,
            'statement' => $this->sql,
            'caller' => $this->caller,
            'hash' => md5($this->sql.json_encode($this->params)),
        );

        if (!empty($this->params)) {
            $result['params'] = $this->params;
        }

        if ($this->rowCount !== null) {
            $result['rowCount'] = $this->rowCount;
        }

        if ($this->result !== null) {
            $result['result'] = $this->result;
        }

        return $result;
    }

    public function getSortPriority()
    {
        // Sort by null. This will result in them being ordered by the order in which they happened
        return null;
    }

    /**
     * @see Loggable::getCategoryName
     * @return string
     */
    public static function getCategoryName()
    {
        return 'SQL Statements';
    }

    public static function addSqlClass($class)
    {
        self::$sqlClasses[] = $class;
    }


}