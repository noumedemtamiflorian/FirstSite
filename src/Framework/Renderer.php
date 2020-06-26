<?php

namespace Framework;

use phpDocumentor\Reflection\Types\Mixed_;

class Renderer
{
    const DEFAULT_NAMESPACE = "__MAIN";
    private $paths = [];
    /**
     * variable globalement accesible pour toutes
     * les vues
     * @var array
     */
    private $globals = [];

    /**
     * permet de rajouter un chemin pour changer les vues
     * @param string $namespace
     * @param string|null $path
     */
    public function addPath(string $namespace, ?string $path = null): void
    {
        if (is_null($path)) {
            $this->paths[self::DEFAULT_NAMESPACE] = $path;
        } else {
            $this->paths[$namespace] = $path;
        }
    }

    /**
     * permet de rendre une vue
     * le chemin peut etre preciser avec des namespaces
     * rajoute via le addPath()
     * $this->render('@blog/view');
     * $this->render('view');
     * @param string $view
     * @param array $params
     * @return string
     */
    public function render(string $view, array $params = []): string
    {
        if ($this->hasNamespace($view)) {
            $path = "./../src/" . ucfirst($this->getNamespace($view)) . $this->replaceNamespace($view) . '.php';
        } else {
            $path = './../views' . $this->paths[self::DEFAULT_NAMESPACE] . '/' . $view . '.php';
        }
        ob_start();
        $renderer = $this;
        extract($this->globals);
        extract($params);
        require $path;
        return ob_get_clean();
    }

    /**
     * permet de verifier que la $view renvoye a un namespace
     * hasNamespace(@blog/view) == true
     * hasNamespace(view) == false
     * @param string $view
     * @return bool
     */
    public function hasNamespace(string $view): bool
    {
        return $view[0] == '@';
    }

    /**
     * renvoi le namespace de la view contenant le namespace
     * @param string $view
     * @return string
     */
    public function getNamespace(string $view): string
    {
        return substr($view, 1, strpos($view, '/') - 1);
    }

    /**
     * remplace @namespace par la view
     * @param string $view
     * @return string
     */
    public function replaceNamespace(string $view): string
    {
        $namespace = $this->getNamespace($view);
        return str_replace('@' . $namespace, $this->paths[$namespace], $view);
    }

    /**
     * permet de rajouter des variables globales a toutes les vues
     * @param string $key
     * @param mixed $value
     */
    public function addGlobal(string $key, $value): void
    {
        $this->globals[$key] =$value;
    }
}
