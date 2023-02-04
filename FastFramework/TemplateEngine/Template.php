<?php

namespace FastFramework\TemplateEngine;

use Exception;

class Template
{
    private string $viewPath;

    public function __construct(private readonly ?string $template, private readonly ?array $args = null)
    {
        $this->viewPath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . "template" . DIRECTORY_SEPARATOR;
    }

    /**
     * @return void
     * @throws Exception
     */
    private function getContent(): void
    {
        if (!(
            is_file($this->viewPath .  (str_contains($this->template, ".html") ? $this->template : "$this->template.html") ) ||
            is_file($this->viewPath . (str_contains($this->template, ".php") ? $this->template : "$this->template.php") )
        )) throw new Exception("Template $this->template not found");
    }

    /**
     * @return void
     * @throws Exception
     */
    public function process(): void
    {
        $this->getContent();
    }
}