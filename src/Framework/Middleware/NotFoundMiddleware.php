<?php


namespace App\Framework\Middleware;


use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;

class NotFoundMiddleware
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        return new Response(404, [], '<h1>Erreur 404</h1>');
    }

}