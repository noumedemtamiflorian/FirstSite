<?php
require dirname(__DIR__) . '/vendor/autoload.php';

$modules = [
    \App\Blog\BlogModule::class
];
$builder = new \DI\ContainerBuilder();
$builder->addDefinitions(dirname(__DIR__) . '/config/config.php');
$builder->addDefinitions(dirname(__DIR__) . '/config.php');
foreach ($modules as $module) {
    if ($module::DEFINITIONS) {
        $builder->addDefinitions($module::DEFINITIONS);
    }
}

$container = $builder->build();
$app = new \Framework\App($container, $modules);
if (php_sapi_name() !== "cli") {
    $response = $app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());
    \Http\Response\send($response);
}
