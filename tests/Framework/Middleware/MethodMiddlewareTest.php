<?php

namespace Tests\Framework\Middleware;
use App\Framework\Middleware\CsrfMiddleware;
use App\Framework\Middleware\MethodSlashMiddleware;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class MethodMiddlewareTest extends TestCase
{
    /**
     * @var CsrfMiddleware
     */
    private $middleware;
protected function setUp(): void
{
    $this->middleware = new MethodSlashMiddleware();
}

    public function testAddMethod()
    {

       $request = (new  ServerRequest('POST','/demo'))
       ->withParsedBody(['_method' => 'DELETE']);
       call_user_func_array($this->middleware,[$request,function(ServerRequestInterface $request){
           $this->assertEquals('DELETE',$request->getMethod());
       }]);
    }

}