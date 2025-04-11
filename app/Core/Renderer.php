<?php

namespace CGRD\Core;

use Twig\Environment;

class Renderer {

    private array $defaultParams;

    public function __construct(
        private Environment $twig,
        private readonly array $params,    
    ) 
    {
        $this->defaultParams = [
            'title' => $params['name']
        ];
    }

    public function render(string $template, array $params)
    {
        $template = $this->appendFileName($template);

        return $this->twig->render($template, array_merge($params, $this->defaultParams));
    }

    private function appendFileName(string $template)
    {
        if (!str_ends_with($template, '.html.twig')) {
            $template .= '.html.twig';
        }

        return $template;
    }



}