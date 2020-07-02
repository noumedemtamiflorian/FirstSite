<?php

namespace App\Admin\Action;

use App\Blog\Table\PostTable;
use App\Framework\Session\FlashService;
use App\Framework\Session\SessionInterface;
use App\Framework\Validator;
use Framework\Actions\RouterAwareAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminAction
{
    private $renderer;
    /**
     * @var \PDO
     */
    private $router;
    /**
     * @var PostTable
     */
    private $postTable;
    /**
     * @var FlashService
     */
    private $flash;

    use RouterAwareAction;

    public function __construct(
        RendererInterface $renderer,
        Router $router,
        PostTable $postTable,
        FlashService $flash
    )
    {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->postTable = $postTable;
        $this->flash = $flash;
    }

    public function __invoke(Request $request)
    {
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
        $items = $this->postTable->findPaginated(10, $params['p'] ?? 1);
        return $this->renderer->render('@admin/index', compact('items'));
    }

    /**
     *
     * editer un article
     *
     * @param Request $request
     * @return ResponseInterface|string
     */
    public function edit(Request $request)
    {
        $item = $this->postTable->find($request->getAttribute('id'));

        if ($request->getMethod() == 'POST') {
            $params = $this->getParams($request);
            $params['updated_at'] = date('Y-m-d H:i:s');
            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                $this->postTable->update($item->id, $params);
                $this->flash->typeOfFlash('modifie', 'L\'article a bien ete modifie');
                return $this->redirect('admin.index');
            }
            $errors = $validator->getErrors();
            $params['id'] = $item->id;
            $item = $params;
        }
        return $this->renderer->render('@admin/edit', compact('item','errors'));
    }

    public function create(Request $request)
    {

        if ($request->getMethod() == 'POST') {
            $params = $this->getParams($request);
            $params['created_at'] = date('Y-m-d H:i:s');
            $params['updated_at'] = date('Y-m-d H:i:s');
            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                $this->postTable->insert($params);
                $this->flash->typeOfFlash('ajouter', 'L\'article a bien ete Ajouter');
                return $this->redirect('admin.index');
            }
            $errors = $validator->getErrors();
            $item = $params;
        }
        return $this->renderer->render('@admin/create',compact('errors'));
    }

    public function delete(Request $request)
    {
        $this->postTable->delete($request->getAttribute('id'));
        $this->flash->typeOfFlash('supprimer', 'L\'article a bien ete supprimer');
        return $this->redirect('admin.index');
    }

    private function getParams(Request $request)
    {
        return array_filter($request->getParsedBody(), function ($key) {
            return in_array($key, ['name', 'content', 'slug']);
        }, ARRAY_FILTER_USE_KEY);
    }

    private function getValidator(Request $request)
    {
        return  (new  Validator($request->getParsedBody()))
            ->required('content','name','slug')
            ->length('content',10)
            ->length('name',2,250)
            ->length('slug',2,50)
            ->slug('slug');
    }

}
