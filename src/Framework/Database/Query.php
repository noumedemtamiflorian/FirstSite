<?php


namespace App\Framework\Database;

use IteratorAggregate;
use Pagerfanta\Pagerfanta;
use PDO;

class Query implements IteratorAggregate
{

    private $joins = [];
    private $orders = [];
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
     * @var string
     */
    private string $entity;
    /**
     * @var array
     */
    private $records;
    /**
     * @var array
     */
    private $params = [];

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo;
    }

    public function from(string $table, ?string $alias = null)
    {
        if ($alias) {
            $this->from[$table] = $alias;
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

    public function limit(int $length, int $offset = 0)
    {
        $this->limit = "$offset , $length";
        return $this;
    }

    public function order(string $order)
    {
        $this->orders[] = $order;
        return $this;
    }

    public function join(string $table, string $condition, string $type = "left")
    {
        $this->joins[$type][] = [$table, $condition];
        return $this;
    }

    public function where(string ...$condition)
    {
        $this->where = array_merge($this->where, $condition);
        return $this;
    }

    public function count()
    {
        $query = clone $this;
        $table = current($this->from);
        return $query->select("COUNT($table.id)")
            ->execute()
            ->fetchColumn();
    }

    public function params(array $params)
    {
        $this->params = array_merge($this->params, $params);
        return $this;
    }

    public function into(string $entity)
    {
        $this->entity = $entity;
        return $this;
    }

    public function fetch()
    {
        $record = $this->execute()->fetch(PDO::FETCH_ASSOC);
        if ($record === false) {
            return false;
        }
        if ($this->entity) {
            return Hydrator::hydrate($record, $this->entity);
        }
        return $record;
    }

    public function fetchOrFail()
    {
        $record = $this->fetch();
        if ($record === false) {
            throw  new  NoRecordException();
        }
        return $record;
    }

    public function fetchAll()
    {
        $result = $this->records = $this->execute()->fetchAll(PDO::FETCH_ASSOC);
        return new  QueryResult($result, $this->entity);
    }

    public function paginate(int $perPage, int $currentPage = 1)
    {
        $paginator = new  PaginatedQuery($this);
        return (new  Pagerfanta($paginator))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($currentPage);
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
        if (!empty($this->joins)) {
            foreach ($this->joins as $type => $joins) {
                foreach ($joins as [$table, $condition]) {
                    $parts[] = strtoupper($type) . " JOIN $table ON $condition ";
                }
            }
        }
        if (!empty($this->where)) {
            $parts[] = 'WHERE';
            $parts[] = "(" . join(') AND (', $this->where) . ")";
            return join(' ', $parts);
        }
        if (!empty($this->orders)) {
            $parts[] = "ORDER BY";
            $parts[] = join(', ', $this->orders);
        }
        if (!empty($this->limit)) {
            $parts[] = "LIMIT " . $this->limit;
        }
        return join(' ', $parts);
    }

    private function buildFrom()
    {
        $from = [];
        foreach ($this->from as $key => $value) {
            if (is_string($key)) {
                $from[] = "$key as $value";
            } else {
                $from[] = $value;
            }
        }
        return join(', ', $from);
    }

    private function execute()
    {
        $query = $this->__toString();
        if (!empty($this->params)) {
            $statement = $this->pdo->prepare($query);
            $statement->execute($this->params);
            return $statement;
        }
        return $this->pdo->query($query);
    }

    public function getIterator()
    {
        return $this->fetchAll();
    }
}
