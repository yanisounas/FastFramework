<?php
declare(strict_types=1);

namespace FastFramework\Response;

class View extends Response
{

    private string|false $rawContent;

    public function __construct(
        private readonly string $view,
        ?int $statusCode = null,
        ?string $contentType = null,
        ?array $args = null,
        bool $extract = true)
    {
        parent::__construct(statusCode: $statusCode, contentType: $contentType);

        if ($extract && $args)
            extract($args);

        ob_start();

        include_once $this->view;

        $this->rawContent = ob_get_clean();

        $this->render();
    }

    public function render(): void
    {
        if (preg_match_all("#{% ?base ?[\"'](?<filename>\w+(.html|.php)?)?[\"'] ?%}#", $this->rawContent, $matches))
        {
            $this->rawContent = preg_replace("#{% ?base ?[\"'](\w+(.html|.php)?)?[\"'] ?%}#", "", $this->rawContent);
            $fileName = (is_file(sprintf("%s.html", dirname($this->view) . DIRECTORY_SEPARATOR . $matches["filename"][0]))) ? $matches["filename"][0].".html" : $matches["filename"][0].".php";

            ob_start();
            include_once dirname($this->view) . DIRECTORY_SEPARATOR . $fileName;
            $base = ob_get_clean();

            $blocks = self::getBlocks($this->rawContent);

            if (preg_match_all("#{% ?prepare ?\"(\w+)\" ?%}#", $base))
            {
                foreach ($blocks as $blockName => $blockContent)
                    $base = preg_replace("#{% ?prepare ?\"$blockName\" ?%}#", $blockContent, $base);
                $base = preg_replace("#{% ?prepare ?\"(\w+)\" ?%}#", "", $base);
            }
            echo $base;
        }
    }

    private static function getBlocks(string $subject): ?array
    {
        if (preg_match_all("#{% ?block ?\"(\w+)\" ?%}((.|\n)*){% ?endblock ?%}#U", $subject, $matches))
            return array_combine($matches[1], $matches[2]);
        else
            return null;
    }
}