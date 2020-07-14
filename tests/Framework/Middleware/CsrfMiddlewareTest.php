<?php

namespace Tests\Framework\Middleware;
require dirname(dirname(dirname(__DIR__))) . "/vendor/autoload.php";
use App\Framework\Middleware\CsrfMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\ServerRequest;

class CsrfMiddlewareTest extends TestCase
{
    /**
     * @var CsrfMiddleware
     */
    private $middleware;

    public function setUp(): void
    {
        $this->middleware = new CsrfMiddleware();
    }

}