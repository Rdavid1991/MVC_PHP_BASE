<?php

class Response
{

    public $g_params = array();

    public static function render($file, array $params = [])
    {
        $res = new Response();
        foreach ($params as $key => $value) {
            $_ENV["params"]["{{" . $key . "}}"] = $value;
        }

        if (str_ends_with($file, ".html")) {

            $html = file_get_contents($_ENV["options"]["views"]["layoutsDir"] . "/$file");
            $res->renderHTML($html);
        } elseif (str_ends_with($file, ".php")) {
            $res->renderPHP($_ENV["options"]["views"]["layoutsDir"] . "/$file");
        }
    }

    public static function sendJson($object)
    {
        exit(json_encode((object) $object));
    }

    private function minify_html($htmlMain)
    {
        $search = array(
            // Remove whitespaces after tags
            '/\>[^\S ]+/s',
            // Remove whitespaces before tags
            '/[^\S ]+\</s',
            // Removes comments
            '/<!--(.|\s)*?-->/m'
        );
        $replace = array('>', '<', '');

        return preg_replace($search, $replace, $htmlMain);
    }

    private function get_partials()
    {
        $layouts = array();

        $layoutDir = $_ENV["options"]["views"]["partialsDir"];

        $files = array_diff(scandir($layoutDir), array('.', '..'));

        foreach ($files as $key => $value) {
            if (str_ends_with($value, ".html")) {
                $layouts["{{>" . str_replace(".html", "", $value) . "}}"] = file_get_contents("$layoutDir /$value");
            } elseif (str_ends_with($value, ".php")) {

                ob_start();

                include_once "$layoutDir /$value";
                $html = ob_get_contents();

                ob_end_clean();

                $layouts["{{>" . str_replace(".php", "", $value) . "}}"] = $html;
            }
        }

        return $layouts;
    }

    private function renderHTML(string $html)
    {
        $htmlMain = $this->get_full_page($html);

        $htmlMain = $this->minify_html($htmlMain);
        $htmlMain = $this->clear_html($htmlMain);

        exit($htmlMain);
    }

    /**
     * Obtiene la ejecución de archivo php, y retorna la salida
     * puede ser el html renderizado con todas sus variables e información 
     * 
     * @param string $path Ruta de archivo php
     * @return string
     */
    private function renderPHP(string $path)
    {

        ob_start();

        include_once $path;

        $html = ob_get_contents();
        ob_end_clean();

        $htmlMain = $this->get_full_page($html);

        $htmlMain = $this->minify_html($htmlMain);
        $htmlMain = $this->clear_html($htmlMain);

        exit($htmlMain);
    }

    public static function redirect($url)
    {
        $schema = $_SERVER["REQUEST_SCHEME"];
        $host = $_SERVER["HTTP_HOST"];
        $uri = explode("/", $_SERVER["REQUEST_URI"])[1];

        header("Location:$schema://" . str_replace("//", "/", "$host/$uri/$url"));
        exit();
    }

    private function clear_html($html)
    {
        return preg_replace("/\{\{.*?\}\}/s", '', $html);
    }

    private function get_full_page(string $layout)
    {

        $str_params  = array();
        $partials = $this->get_partials();

        foreach ($_ENV["params"] as $key => $value) {
            if (!is_array($value)) {
                $str_params[$key] = $value;
            }
        };

        return str_replace(
            [
                "{{{_body_}}}",
                ...array_keys($partials),
                ...array_keys($str_params)
            ],
            [
                $layout,
                ...array_values($partials),
                ...array_values($str_params)
            ],
            file_get_contents($_ENV["options"]["views"]["mainDir"])
        );
    }
}
