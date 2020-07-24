<?php


namespace App\Blog\Table;

use App\Blog\Entity\Category;
use App\Framework\Database\Query;
use App\Framework\Database\Table;

class CategoryTable extends Table
{
    protected $table = "categories";
    protected $entity = Category::class;
}
