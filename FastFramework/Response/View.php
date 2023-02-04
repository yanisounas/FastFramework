<?php

namespace FastFramework\Response;

use FastFramework\TemplateEngine\Template;

class View extends Response
{
    private Template $template;

    public function __construct(
        private readonly string $view,
        ?int                    $statusCode = null,
        ?string                 $contentType = null,
        private readonly ?array $args = null)
    {
        $this->template = new Template($this->view, $this->args);
        $this->template->process();
        parent::__construct("", statusCode: $statusCode, contentType: $contentType);
    }
}