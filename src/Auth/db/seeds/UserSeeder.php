<?php


use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{

    public function run()
    {
        $this->table('users')
            ->insert([
                'username' => 'admin',
                'email' => 'admin@admin.fr',
                'password' => password_hash('admin', PASSWORD_DEFAULT)
            ])
            ->save();
    }
}
