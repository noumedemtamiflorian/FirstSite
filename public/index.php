<?php

use App\Admin\AdminModule;
use App\Auth\AuthModule;
use App\Auth\ForbiddenMiddleware;
use App\Blog\BlogModule;
use App\Contact\ContactModule;
use App\Framework\Auth\LoggedInMiddleware;
use App\Framework\Middleware\CsrfMiddleware;
use App\Framework\Middleware\DispatcherMiddleware;
use App\Framework\Middleware\MethodSlashMiddleware;
use App\Framework\Middleware\NotFoundMiddleware;
use App\Framework\Middleware\RouterMiddleware;
use App\Framework\Middleware\TraillingSlashMiddleware;
use App\Registration\RegistrationModule;
use Framework\App;
use Framework\Router\MiddlewareApp;
use Franzl\Middleware\Whoops\WhoopsMiddleware;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use function Http\Response\send;
use Middlewares\TrailingSlash;
use Middlewares\Utils\Dispatcher;
use Middlewares\Whoops;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$app = (new App('config/config.php'))
    ->addModule(AdminModule::class)
    ->addModule(BlogModule::class)
    ->addModule(RegistrationModule::class)
    ->addModule(ContactModule::class)
    ->addModule(AuthModule::class);
$container = $app->getContainer();
$app->pipe(TraillingSlashMiddleware::class)
    ->pipe(ForbiddenMiddleware::class)
    ->pipe(LoggedInMiddleware::class, $container->get('admin.prefix'))
    ->pipe(MethodSlashMiddleware::class)
    ->pipe(CsrfMiddleware::class)
    ->pipe(RouterMiddleware::class)
    ->pipe(DispatcherMiddleware::class)
    ->pipe(NotFoundMiddleware::class);
if (php_sapi_name() !== "cli") {
    $response = $app->run(ServerRequest::fromGlobals());
    send($response);
}
