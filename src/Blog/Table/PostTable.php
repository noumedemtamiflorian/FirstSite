<?php


namespace App\Blog\Table;

use App\Blog\Entity\Post;
use App\Framework\Database\Table;

class PostTable extends Table
{
    protected $table = "posts";
    protected $entity = Post::class;

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
