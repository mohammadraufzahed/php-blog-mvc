<?php

namespace SimplePHPFramework\kernel;

use Pug\Pug;

class View
{
    /**
     * Render the page view
     * @param string $template
     */
    static public function render(string $template, $params = [])
    {
        $viewDir = dir($_SERVER["DOCUMENT_ROOT"] . "/../views");
        $templatePath = $viewDir->path . "/" . $template;
        if (!file_exists($templatePath)) {
            echo "Template not found";
            exit;
        } else {
            $pugEngine = new Pug([
                "pretty" => true,
                "cache" => $_SERVER["DOCUMENT_ROOT"] . "/../views/cache"
            ]);
            $pugEngine->display($templatePath, $params);
        }
    }
    /**
     * Redirect the user to another page and render the page view
     * @param string $url
     * @return void
     */
    static public function redirect(string $url)
    {
        header("Location: " . $url);
        exit;
    }
}
