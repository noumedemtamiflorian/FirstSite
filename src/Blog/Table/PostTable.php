<?php


namespace App\Blog\Table;

use App\Blog\Entity\Post;
use App\Framework\Database\NoRecordException;
use App\Framework\Database\PaginatedQuery;
use App\Framework\Database\Table;
use Pagerfanta\Pagerfanta;
use PDO;

class PostTable extends Table
{
    protected $table = "posts";
    protected $entity = Post::class;

    public function findPaginatedPublic(int $perPage, int $currentPage)
    {

        $query = new PaginatedQuery(
            $this->getPdo(),
            "SELECT p.* , c.name as category_name , c.slug as category_slug 
                    FROM {$this->table} AS p 
                    LEFT  JOIN  categories as c ON c.id = p.category_id
                    ORDER  BY p.created_at DESC ",
            "SELECT COUNT(id) FROM {$this->table}",
            $this->entity
        );
        return (new Pagerfanta($query))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($currentPage);
    }

    public function findPaginatedPublicForCategory(int $perPage, int $currentPage, int $category_id)
    {

        $query = new PaginatedQuery(
            $this->getPdo(),
            "SELECT p.* , c.name as category_name , c.slug as category_slug 
                    FROM {$this->table} AS p 
                    LEFT  JOIN  categories as c ON c.id = p.category_id
                    WHERE  p.category_id = :category
                    ORDER  BY p.created_at DESC 
                   ",
            "SELECT COUNT(id) FROM {$this->table} WHERE  category_id = :category",
            $this->entity,
            ['category' => $category_id]
        );
        return (new Pagerfanta($query))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($currentPage);
    }

    public function findWithCategory($attribute)
    {
        return $this->fecthOrFail(
            "SELECT p.* , c.name category_name , c.slug category_slug
           FROM posts as p 
           LEFT  JOIN  categories as c ON c.id = p.category_id 
           WHERE  p.id = ?          ",
            [$attribute]
        );
    }

    protected function paginationQuery()
    {
        return "
        SELECT p.id id , p.created_at created_at,
         p.name name , p.slug slug, c.name category_name 
         , p.content  content
        FROM {$this->table} as p
        LEFT  JOIN  categories as c ON  p.category_id = c.id
         ORDER BY created_at  DESC 
         ";
    }
}
