<?php


namespace App\Framework\Database;

use App\Blog\Entity\Post;
use PDO;
use Pagerfanta\Adapter\AdapterInterface;

class PaginatedQuery implements AdapterInterface
{
    /**
     * @var PDO
     */
    private $pdo;
    /**
     * @var string
     */
    private $query;
    /**
     * @var string
     */
    private $countQuery;
    /**
     * @var string
     */
    private $entity;


    /**
     * PaginatedQuery constructor.
     * @param PDO $pdo
     * @param string  Requete permettant de
     * recuperer X resultat
     * @param string $countQuery
     * Requete permettant de compter le nombre resultat total
     */
    public function __construct(PDO $pdo, string $query, string $countQuery, string $entity)
    {
        $this->pdo = $pdo;
        $this->query = $query;
        $this->countQuery = $countQuery;
        $this->entity = $entity;
    }

    /**
     * Returns the number of results for the list.
     *
     * @return int
     */
    public function getNbResults()
    {
        return $this->pdo->query($this->countQuery)->fetchColumn();
    }

    /**
     * Returns an slice of the results representing the current page of items in the list.
     *
     * @param int $offset
     * @param int $length
     *
     * @return iterable
     */
    public function getSlice($offset, $length)
    {
        $statement = $this->pdo->prepare($this->query . ' LIMIT :offset , :length ');
        $statement->bindParam('offset', $offset, PDO::PARAM_INT);
        $statement->bindParam('length', $length, PDO::PARAM_INT);
        $statement->setFetchMode(PDO::FETCH_CLASS, $this->entity);
        $statement->execute();
        return $statement->fetchAll();
    }
}
