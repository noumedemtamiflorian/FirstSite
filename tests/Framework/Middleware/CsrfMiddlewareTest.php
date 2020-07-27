<?php

namespace Tests\Framework\Middleware;

use App\Framework\Exception\CsrfInvalidException;
use App\Framework\Middleware\CsrfMiddleware;
use Exception;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;

class CsrfMiddlewareTest extends TestCase
{
    /**
     * @var CsrfMiddleware
     */
    private $middleware;
    private array $session;

    public function setUp(): void
    {
        $this->session = [];
        $this->middleware = new CsrfMiddleware($this->session);
    }

    public function testLetGetRequestPass()
    {
        $handle = $this->getMockBuilder(RequestHandlerInterface::class)
            ->setMethods(['handle'])
            ->getMock();

        $handle->expects($this->once())
            ->method('handle')
            ->willReturn(new Response());

        $request = (new  ServerRequest('GET', '/demo'));
        $this->middleware->process($request, $handle);
    }

    public function testBlockPostRequestWithouCsrf()
    {
        $handle = $this->getMockBuilder(RequestHandlerInterface::class)
            ->setMethods(['handle'])
            ->getMock();
        $handle->expects($this->never())
            ->method('handle');
        $request = (new  ServerRequest('POST', '/demo'));
        $this->expectException(CsrfInvalidException::class);
        $this->middleware->process($request, $handle);
    }

    public function testLetPostWithTokensPass()
    {
        $handle = $this->getMockBuilder(RequestHandlerInterface::class)
            ->setMethods(['handle'])
            ->getMock();
        $handle->expects($this->once())
            ->method('handle')
            ->willReturn(new Response());
        $request = (new  ServerRequest('POST', '/demo'));
        $token = $this->middleware->generateToken();
        $request = $request->withParsedBody(['_csrf' => $token]);
        $this->middleware->process($request, $handle);
    }

    public function testBlockPostRequestWithInvalidCsrf()
    {
        $handle = $this->getMockBuilder(RequestHandlerInterface::class)
            ->setMethods(['handle'])
            ->getMock();
        $handle->expects($this->never())
            ->method('handle');
        $request = (new  ServerRequest('POST', '/demo'));
        $this->middleware->generateToken();
        $request = $request->withParsedBody(['_csrf' => "sdsdddddddd"]);
        $this->expectException(CsrfInvalidException::class);
        $this->middleware->process($request, $handle);
    }

    public function testLetPostWithTokensPassOnce()
    {
        $handle = $this->getMockBuilder(RequestHandlerInterface::class)
            ->setMethods(['handle'])
            ->getMock();
        $handle->expects($this->once())
            ->method('handle')
        ->willReturn(new Response());
        $request = (new  ServerRequest('POST', '/demo'));
        $token = $this->middleware->generateToken();
        $request = $request->withParsedBody(['_csrf' => $token]);
        $this->middleware->process($request, $handle);
        $this->expectException(Exception::class);
        $this->middleware->process($request, $handle);
    }

    public function testLimitTheTokenNumber()
    {
        for ($i = 0; $i < 100; $i++) {
            $token = $this->middleware->generateToken();
        }
        $this->assertCount(50,$this->session['csrf']);
        $this->assertEquals($token,$this->session['csrf'][49]);
    }
}