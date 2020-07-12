<?php


use Faker\Factory;
use Phinx\Seed\AbstractSeed;

class PostSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
   // Seeding des categories
        $data = [];
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 5; ++$i) {
            $data[] = [
                'name' => $faker->catchPhrase,
                'slug' => $faker->slug,
            ];
        }
        $this->table('categories')
            ->insert($data)
            ->save();
        // Seeding des articles
        $data = [];
        for ($i = 0; $i < 100; ++$i) {
            $date = $faker->unixTime('now');
            $data[] = [
                'name' => $faker->catchPhrase,
                'slug' => $faker->slug,
                'category_id' => rand(1, 5),
                'content' => $faker->text(3000),
                'created_at' => date('Y-m-d H:i:s', $date),
                'updated_at' => date('Y-m-d H:i:s', $date)
            ];
        }
        $this->table('posts')
            ->insert($data)
            ->save();
    }
}
