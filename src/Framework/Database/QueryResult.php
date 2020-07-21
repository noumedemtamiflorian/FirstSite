<?php


namespace App\Framework\Database;


use ArrayAccess;
use Exception;
use Iterator;

class QueryResult implements Iterator, ArrayAccess
{
    private array $records;
    private $index = 0;
    private $hydratedRecords = [];
    /**
     * @var string|null
     */
    private ?string $entity;

    public function __construct(array $records,?string  $entity = null)
    {
        $this->records = $records;
        $this->entity = $entity;
    }
    public function get($index)
    {
        if ($this->entity)
            if (isset($this->hydratedRecords[$index])) {
                $this->hydratedRecords[$index] = hydrator::hydrate($this->all()[$index], $this->entity);
            }
        return $this->all()[$index];
    }
    public
    function offsetExists($offset)
    {
        return isset($this->records[$offset]);
    }

    public
    function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public
    function offsetSet($offset, $value)
    {
        throw  new Exception("Impossible de modifier comme ca a la voler");
    }

    public
    function offsetUnset($offset)
    {
        throw  new Exception("Impossible de supprimer comme ca a la voler");
    }

    public
    function current()
    {
        return $this->get($this->index);
    }

    public
    function next()
    {
        $this->index++;
    }

    public
    function key()
    {
        return $this->index;
    }

    public
    function valid()
    {
        return isset($this->records[$this->index]);
    }

    public
    function rewind()
    {
        $this->index = 0;
    }
}