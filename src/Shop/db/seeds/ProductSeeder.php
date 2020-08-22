<?php


use Phinx\Seed\AbstractSeed;

class ProductSeeder extends AbstractSeed
{

    public function run()
    {
        $data = [];
        $faker = \Faker\Factory::create("fr_Fr");
        for ($i = 0; $i < 50; $i++) {
            $data[] = [
                'name' => $faker->catchPhrase,
                'price' => $faker->randomFloat(2, 5, 1050)
            ];
        }
        $this->table('products')
            ->insert($data)
            ->save();
    }
}
