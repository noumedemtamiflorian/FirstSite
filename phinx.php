<?php
require __DIR__ . "/public/index.php";
$modules = $app->getModules();
$migrations = [];
$seeds = [];
foreach ($modules as $module) {
    if ($module::MIGRATIONS) {
        $migrations[] = $module::MIGRATIONS;
    }
    if ($module::SEEDS) {
        $seeds[] = $module::SEEDS;
    }
}
return
    [

        'paths' => [
            'migrations' => $migrations,
            'seeds' => $seeds
        ],
        'environments' => [
            'default_database' => 'development',
            'development' => [
                'adapter' => 'mysql',
                'host' => $app->getContainer()->get('database.host'),
                'name' => $app->getContainer()->get('database.name'),
                'user' => $app->getContainer()->get('database.username'),
                'pass' => $app->getContainer()->get('database.password')
            ]
        ],
        'test'=>[
            'adapter' => 'sqlite',
            'memory' => true,
            'name' => 'test'
        ]

        /* 'paths' => [
            'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
            'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
        ],
        'environments' => [
            'default_migration_table' => 'phinxlog',
            'default_environment' => 'development',
            'production' => [
                'adapter' => 'mysql',
                'host' => 'localhost',
                'name' => 'production_db',
                'user' => 'root',
                'pass' => '',
                'port' => '3306',
                'charset' => 'utf8',
            ],
            'development' => [
                'adapter' => 'mysql',
                'host' => 'localhost',
                'name' => 'development_db',
                'user' => 'root',
                'pass' => '',
                'port' => '3306',
                'charset' => 'utf8',
            ],
            'testing' => [
                'adapter' => 'mysql',
                'host' => 'localhost',
                'name' => 'testing_db',
                'user' => 'root',
                'pass' => '',
                'port' => '3306',
                'charset' => 'utf8',
            ]
        ],
        'version_order' => 'creation' */
    ];
