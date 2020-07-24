<?php


namespace App\Framework\Database;

use App\Blog\Entity\Post;
use PDO;
use Pagerfanta\Adapter\AdapterInterface;

class PaginatedQuery implements AdapterInterface
{

    /**
     * @var Query
     */
    private $query;


    public function __construct(Query $query)
    {
        $this->query = $query;
    }

    public function getNbResults()
    {
        $query = clone $this->query;

        return $query->count();
    }


    public function getSlice($offset, $length)
    {
        $query = clone $this->query;
        return $query->limit($length, $offset)->fetchAll();
    }
}
