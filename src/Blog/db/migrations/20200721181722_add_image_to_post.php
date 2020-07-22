<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddImageToPost extends AbstractMigration
{
    public function change(): void
    {
        $this->table('posts')
            ->addColumn('image', 'string', ['null' => true])
            ->update();
    }
}
