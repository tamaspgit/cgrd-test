<?php

namespace CGRD\Controllers;

use CGRD\Http\RequestInterface;
use CGRD\Http\ResponseInterface;
use CGRD\Core\Container;
use CGRD\Core\Renderer;
use CGRD\Core\Session;
use CGRD\Database\DatabaseInterface;

class BaseController {

    public function __construct(
        protected Container $container,
        protected RequestInterface $request,
        protected ResponseInterface $response,
        private Renderer $renderer,
        protected DatabaseInterface $db,
        protected Session $session,     
    ) {}

    public function view(string $template, array $params = []): ResponseInterface
    {        
        $flash = $this->extractFlash();
        if ($flash) {
            $params['flash'] = $flash;
        }        
        
        $content = $this->renderer->render($template, $params);

        $this->response->getBody()->write($content);

        return $this->response;
    }

    protected function redirect(string $url): ResponseInterface
    {
        $response = $this->response;
        $response->setStatusCode(302);
        $response->addHeader('Location', $url);

        return $response;
    }

    private function extractFlash(): ?array
    {
        foreach (['success', 'error', 'warning'] as $type) {
            if ($this->session->hasFlash($type)) {
                return [
                    'type' => $type,
                    'message' => $this->session->getFlash($type),
                ];
            }
        }
        return null;
    }

}