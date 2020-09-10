<?php


namespace App\Framework\Middleware;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MethodSlashMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $pasedBody = $request->getParsedBody();
        if ((array_key_exists('_method', $pasedBody)) &&
            in_array($pasedBody['_method'], ['PUT', 'DELETE'])
        ) {
            $request = $request->withMethod($pasedBody['_method']);
        }
        return  $handler->handle($request);
    }
}
