<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddPublishedToPosts extends AbstractMigration
{

    public function change(): void
    {
        $this->table('posts')
            ->addColumn('published', 'boolean', ['default' => false])
            ->update();
    }
}
