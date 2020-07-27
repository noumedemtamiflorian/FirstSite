<?php

namespace Framework\Router;

class Route
{

    /**
     * @var string
     */
    private $name;
    /**
     * @var callable
     */
    private $callback;
    /**
     * @var array
     */
    private $parameters;

    public function __construct(string $name, $callback, array $parameters)
    {
        $this->name = $name;
        $this->callback = $callback;
        $this->parameters = $parameters;
    }

    /**
     * Renvoir le Nom de la route
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Renvoi le callable  de la route
     * @return callable
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * Renvoi les parametres de la route
     * @return string[]
     */
    public function getParams(): array
    {
        return $this->parameters;
    }
}
