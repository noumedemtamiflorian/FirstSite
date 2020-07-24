<?php


namespace App\Framework\Database;

use Pagerfanta\Pagerfanta;
use PDO;
use stdClass;

class Table
{
    protected $table;
    /**
     * @var PDO|null
     */
    private $pdo;
    protected $entity = stdClass::class;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function makeQuery()
    {
        return (new  Query($this->pdo))
            ->from($this->table, $this->table[0])
            ->into($this->entity);
    }

    /**
     * Recupere une liste  cle valeur de nos enregistrement
     */
    public function findList(): array
    {
        $results = $this->pdo->query("SELECT id , name FROM {$this->table}")
            ->fetchAll(PDO::FETCH_NUM);
        $list = [];
        foreach ($results as $result) {
            $list[$result[0]] = $result[1];
        }
        return $list;
    }

    public function findAll()
    {
        return $this->makeQuery();
    }

    public function findBy(string $field, string $value)
    {
        return $this->makeQuery()->where("$field = :$field")->params(["$field" => $value])->fetchOrFail();
    }

    public function find(int $id)
    {
        return $this->makeQuery()->where("id = :id")->params(["id" => $id])->fetchOrFail();
    }

    /**
     *
     * met ajout les champs un enregistement
     *
     * @param int $id
     * @param array $params
     * @return bool
     */
    public function update(int $id, array $params): bool
    {
        $fieldsQuery = $this->buildFieldQuery($params);
        $params['id'] = $id;
        $statement = $this->pdo->prepare("UPDATE {$this->table} SET $fieldsQuery WHERE id = :id ");
        return $statement->execute($params);
    }

    public function insert(array $params)
    {
        $fields = array_keys($params);
        $values = join(', ', array_map(function ($field) {
            return ':' . $field;
        }, $fields));
        $fields = join(", ", $fields);

        $statement = $this->pdo->prepare("INSERT INTO {$this->table} ($fields) VALUES ($values) ");
        return $statement->execute($params);
    }

    /**
     *
     * supprime un element
     *
     * @param int $id
     * @return bool|\PDOStatement
     */
    public function delete(int $id)
    {
        $statement = $this->pdo->prepare("DELETE FROM {$this->table} WHERE  id = ? ");
        return $statement->execute([$id]);
    }

    private function buildFieldQuery(array $params)
    {
        return join(' , ', array_map(function ($field) {
            return "$field = :$field ";
        }, array_keys($params)));
    }

    public function exists($id)
    {
        $statement = $this->pdo->prepare("SELECT id FROM {$this->table} WHERE  id = ? ");
        $statement->execute([$id]);
        return $statement->fetchColumn() !== false;
    }

    public function count()
    {
        return $this->makeQuery()->count();
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return PDO
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}
