<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateProductsTable extends AbstractMigration
{

    public function change(): void
    {
        $this->table("products")
            ->addColumn("name", "string")
            ->addColumn("price", "float")
            ->create();
    }
}
