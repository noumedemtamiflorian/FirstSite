<?php


namespace App\Framework\Database;

use Pagerfanta\Pagerfanta;
use PDO;

class Table
{
    protected $table;
    private $pdo;
    protected $entity;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     *
     * pagine les elements
     * @param int $perPage
     * @return Pagerfanta
     */
    public function findPaginated(int $perPage, int $currentPage)
    {

        $query = new PaginatedQuery(
            $this->pdo,
            $this->paginationQuery(),
            "SELECT COUNT(id) FROM {$this->table}",
            $this->entity
        );
        return (new Pagerfanta($query))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($currentPage);
    }

    protected function paginationQuery()
    {
        return "SELECT * FROM {$this->table} ";
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

    /**
     *
     * recupere un element
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id)
    {

        $query = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = ? ");
        $query->execute([$id]);
        if ($this->entity) {
            $query->setFetchMode(PDO::FETCH_CLASS, $this->entity);
        }
        return $query->fetch() ?: null;
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
