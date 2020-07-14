<?php

use App\Admin\AdminModule;
use App\Blog\BlogModule;
use App\Framework\Middleware\CsrfMiddleware;
use App\Framework\Middleware\DispatcherMiddleware;
use App\Framework\Middleware\MethodSlashMiddleware;
use App\Framework\Middleware\NotFoundMiddleware;
use App\Framework\Middleware\RouterMiddleware;
use App\Framework\Middleware\TraillingSlashMiddleware;
use Framework\App;
use Framework\Router\MiddlewareApp;
use Franzl\Middleware\Whoops\WhoopsMiddleware;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use function Http\Response\send;
use Middlewares\TrailingSlash;
use Middlewares\Utils\Dispatcher;
use Middlewares\Whoops;

require dirname(__DIR__) . '/vendor/autoload.php';

$app = (new App(dirname(__DIR__) . '/config/config.php'))
    ->addModule(AdminModule::class)
    ->addModule(BlogModule::class)
    ->pipe(WhoopsMiddleware::class)
    ->pipe(TraillingSlashMiddleware::class)
    ->pipe(MethodSlashMiddleware::class)
    ->pipe(CsrfMiddleware::class)
    ->pipe(RouterMiddleware::class)
    ->pipe(DispatcherMiddleware::class)
    ->pipe(NotFoundMiddleware::class);
if (php_sapi_name() !== "cli") {
    $response = $app->run(ServerRequest::fromGlobals());
    send($response);
}
