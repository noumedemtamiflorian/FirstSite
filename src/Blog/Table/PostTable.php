<?php


namespace App\Blog\Table;

use PDO;
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
     *
     * @return stdClass[]
     */
    public function findPaginated()
    {
        return $this->pdo
            ->query('SELECT * FROM posts ORDER  BY created_at DESC  LIMIT 10')
            ->fetchAll();
    }

    /**
     *
     * recupere un article a partir de son ID
     *
     * @param int $id
     * @return stdClass
     */
    public function find(int $id)
    {
        $query = $this->pdo->prepare('SELECT * FROM posts WHERE id = ? ');
        $query->execute([$id]);
        return $query->fetch();
    }
}
