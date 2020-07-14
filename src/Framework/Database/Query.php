<?php


namespace App\Framework\Database;

use PDO;

class Query
{

    private $group;
    private $order;
    private $limit;
    /**
     * @var string[]
     */
    private $select;
    /**
     * @var string[]
     */
    private $where = [];
    /**
     * @var array
     */
    private $from;
    /**
     * @var PDO|null
     */
    private $pdo;
    /**
     * @var array
     */
    private $params;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo;
    }

    public function from(string $table, ?string $alias = null)
    {
        if ($alias) {
            $this->from[$alias] = $table;
        } else {
            $this->from[] = $table;
        }
        return $this;
    }

    public function select(string  ...$fields)
    {
        $this->select = $fields;
        return $this;
    }

    public function where(string ...$condition)
    {
        $this->where = array_merge($this->where, $condition);
        return $this;
    }

    public function count()
    {
        $this->select("COUNT(id)");
        return $this->execute()->fetchColumn();
    }

    public function params(array $prams)
    {
        $this->params = $prams;
        return $this;
    }

    public function __toString()
    {
        $parts = ['SELECT'];
        if ($this->select) {
            $parts[] = join(', ', $this->select);
        } else {
            $parts[] = '*';
        }
        $parts[] = 'FROM';
        $parts[] = $this->buildFrom();
        if (!empty($this->where)) {
            $parts[] = 'WHERE';
            $parts[] = "(" . join(') AND (', $this->where) . ")";
            return join(' ', $parts);
        }
    }

    private function buildFrom()
    {
        $from = [];
        foreach ($this->from as $key => $value) {
            if (is_string($key)) {
                $from[] = "$value as $key";
            } else {
                $from[] = $value;
            }
        }
        return join(', ', $from);
    }

    private function execute()
    {
        $query = $this->__toString();
        if ($this->params) {
            $statement = $this->pdo->prepare($query);
            $statement->execute($this->params);
            return $statement;
        }
        return $this->pdo->query($query);
    }
}
