<?php


namespace App\Contact\Action;

use App\Framework\Session\FlashService;
use App\Framework\Validator;
use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface;

class ContactAction
{
    /**
     * @var RendererInterface
     */
    private RendererInterface $renderer;
    /**
     * @var FlashService
     */
    private FlashService $flashService;

    public function __construct(RendererInterface $renderer, FlashService $flashService)
    {

        $this->renderer = $renderer;
        $this->flashService = $flashService;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $errors = $item = null;
        if ($request->getMethod() === "POST") {
            $item = $request->getParsedBody();
            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                $this->flashService->typeOfFlash("message", "Votre message a  bien ete Envoye");
            }
            $errors = $validator->getErrors();
        }

        return $this->renderer->render("@contact/contact", compact("item", "errors"));
    }

    private function getValidator(ServerRequestInterface $request)
    {
        $validator = new  Validator(array_merge($request->getParsedBody()));
        $validator
            ->email("email")
            ->length("name", 3, 50)
            ->length("message", 2)
            ->notEmpty("email", "message", "name");
        return $validator;
    }
}
