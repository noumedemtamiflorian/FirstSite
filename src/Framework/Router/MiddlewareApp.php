<?php

namespace Framework\Router;

use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MiddlewareApp implements MiddlewareInterface
{


    private $callback;

    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface|null $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler = null): ResponseInterface
    {
        return $this->process($request, $handler);
    }

    /**
     * @return callable
     */
    public function getCallback()
    {
        return $this->callback;
    }
}
