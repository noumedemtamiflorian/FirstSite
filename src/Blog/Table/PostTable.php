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

    public function findAll()
    {
        $category = new CategoryTable($this->getPdo());
        return $this->makeQuery()
            ->join($category->getTable() . ' as c', 'c.id = p.category_id')
            ->select("p.* , c.name as category_name , c.slug as category_slug")
            ->order('p.created_at DESC');
    }

    public function findPublic()
    {
        return $this->findAll()
            ->where("p.published = 1")
            ->where("p.created_at < NOW()");
    }

    public function findPublicForCategory($id)
    {
        return $this->findPublic()->where("p.category_id = $id");
    }

    public function findWithCategory($id)
    {
        return $this->findPublic()->where("p.id = $id")->fetch();
    }
}
