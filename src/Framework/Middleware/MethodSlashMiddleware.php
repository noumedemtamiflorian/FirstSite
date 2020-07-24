<?php


namespace App\Framework\Middleware;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;

class MethodSlashMiddleware
{
    public function __invoke(ServerRequestInterface $request, $next)
    {
        $pasedBody = $request->getParsedBody();
        if ((array_key_exists('_method', $pasedBody)) &&
            in_array($pasedBody['_method'], ['PUT', 'DELETE'])
        ) {
            $request = $request->withMethod($pasedBody['_method']);
        }
        return $next($request);
    }
}
