<?php


namespace App\Shop\Table;

use App\Framework\Database\Table;
use App\Shop\Entity\Product;

class ProductTable extends Table
{
    protected $table = "products";
    protected $entity = Product::class;
}
