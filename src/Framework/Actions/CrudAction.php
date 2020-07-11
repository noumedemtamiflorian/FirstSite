<?php


namespace App\Framework\Actions;

use App\Framework\Database\Table;
use App\Framework\Session\FlashService;
use App\Framework\Validator;
use Framework\Actions\RouterAwareAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class CrudAction
{
    private $renderer;
    private $router;
    private $flash;
    protected $table;
    protected $message = [
        'modifie' => "L'article a bien ete modifie",
        'ajouter' => "L article a bien ete Ajouter",
        'supprimer' => "L'article a bien ete supprimer"

    ];
    protected $viewPath = "@admin";
    protected $routePrefix = "admin";
    use RouterAwareAction;

    public function __construct(
        RendererInterface $renderer,
        Router $router,
        Table $table,
        FlashService $flash
    ) {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->flash = $flash;
        $this->table = $table;
    }

    public function __invoke(Request $request)
    {
        $this->renderer->addGlobal('viewPath', $this->viewPath);
        $this->renderer->addGlobal('routePrefix', $this->routePrefix);
        if ($request->getMethod() == 'DELETE') {
            return $this->delete($request);
        }
        if (substr((string)$request->getUri(), -3) === 'new') {
            return $this->create($request);
        }
        if ($request->getAttribute('id')) {
            return $this->edit($request);
        }
        return $this->index($request);
    }

    public function index(Request $request): string
    {
        $params = $request->getQueryParams();
        $items = $this->table->findPaginated(10, $params['p'] ?? 1);
        return $this->renderer->render($this->viewPath . '/index', compact('items'));
    }

    /**
     *
     * editer un element
     *
     * @param Request $request
     * @return ResponseInterface|string
     */
    public function edit(Request $request)
    {
        $errors = null;
        $item = $this->table->find($request->getAttribute('id'));
        if ($request->getMethod() == 'POST') {
            $params = $this->getParams($request);
            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                $this->table->update($item->id, $params);
                $this->flash->typeOfFlash('modifie', $this->message['modifie']);
                return $this->redirect($this->routePrefix . '.index');
            }
            $errors = $validator->getErrors();
            $params['id'] = $item->id;
            $item = $params;
        }
        return $this->renderer->render(
            $this->viewPath . '/edit',
            $this->formParams(compact('item', 'errors'))
        );
    }

    public function create(Request $request)
    {
        $errors = null;
        $item = $this->getNewEntity();
        if ($request->getMethod() == 'POST') {
            $params = $this->getParams($request);
            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                $this->table->insert($params);
                $this->flash->typeOfFlash('ajouter', $this->message['ajouter']);
                return $this->redirect($this->routePrefix . '.index');
            }
            $errors = $validator->getErrors();
            $item = $params;
        }
        return $this->renderer->render(
            $this->viewPath . '/create',
            $this->formParams(compact('item', 'errors'))
        );
    }

    public function delete(Request $request)
    {
        $this->table->delete($request->getAttribute('id'));
        $this->flash->typeOfFlash('supprimer', $this->message['supprimer']);
        return $this->redirect($this->routePrefix . '.index');
    }

    protected function getParams(Request $request)
    {
        return array_filter($request->getParsedBody(), function ($key) {
            return in_array($key, []);
        }, ARRAY_FILTER_USE_KEY);
    }

    protected function getValidator(Request $request)
    {
        return new  Validator($request->getParsedBody());
    }

    protected function getNewEntity()
    {
        return [];
    }

    protected function formParams(array $params)
    {
        return $params;
    }

    /**
     * @return Table
     */
    public function getTable(): Table
    {
        return $this->table;
    }

}
