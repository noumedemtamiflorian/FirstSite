<?php


namespace App\Blog\Table;

use App\Blog\Entity\Post;
use App\Framework\Database\PaginatedQuery;
use Pagerfanta\Pagerfanta;
use PDO;
use Psr\Http\Message\ServerRequestInterface;
use stdClass;

class PostTable
{
    /**
     * @var PDO
     */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     *
     * pagine les articles
     * @param int $perPage
     * @return Pagerfanta
     */
    public function findPaginated(int $perPage, int $currentPage)
    {
        $query = new PaginatedQuery(
            $this->pdo,
            'SELECT * FROM posts ORDER  BY created_at DESC ',
            'SELECT COUNT(id) FROM posts',
            Post::class
        );
        return (new Pagerfanta($query))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($currentPage);
    }

    /**
     *
     * recupere un article a partir de son ID
     *
     * @param int $id
     * @return Post|null
     */
    public function find(int $id): ?Post
    {

        $query = $this->pdo->prepare('SELECT * FROM posts WHERE id = ? ');
        $query->execute([$id]);
        $query->setFetchMode(PDO::FETCH_CLASS, Post::class);
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
        $statement = $this->pdo->prepare("UPDATE posts SET $fieldsQuery WHERE id = :id ");
        return $statement->execute($params);
    }

    public function insert(array $params)
    {
        $fields = array_keys($params);
        $values = array_map(function ($field) {
            return ':' . $field;
        }, $fields);
        $statement = $this->pdo->prepare("INSERT INTO posts (" . join(' , ', $fields) . ") VALUES (" . join(' , ', $values) . ") ");
        return $statement->execute($params);
    }

    /**
     *
     * supprime un article
     *
     * @param int $id
     * @return bool|\PDOStatement
     */
    public function delete(int $id)
    {
        $statement = $this->pdo->prepare('DELETE FROM posts WHERE  id = ? ');
        return $statement->execute([$id]);
    }

    private function buildFieldQuery(array $params)
    {
        return join(' , ', array_map(function ($field) {
            return "$field = :$field ";
        }, array_keys($params)));
    }
}
