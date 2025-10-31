<?php

namespace Haryadi\Controller;

use Haryadi\View\View;
use Haryadi\Http\Request;
use Haryadi\Http\Response;

abstract class BaseController
{
    protected Request $request;
    protected Response $response;
    protected View $view;

    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->view = new View();
    }

    protected function view(string $template, array $data = []): void
    {
        $this->view->render($template, $data);
    }

    protected function json($data, int $statusCode = 200): void
    {
        $this->response->json($data, $statusCode);
    }

    protected function redirect(string $url): void
    {
        $this->response->redirect($url);
    }
}