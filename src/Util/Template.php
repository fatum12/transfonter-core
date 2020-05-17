<?php

namespace Fatum12\TransfonterCore\Util;

use Fatum12\TransfonterCore\Exception\FileNotFound;

class Template
{
    public static function render($templateName, $data = [])
    {
        $templatePath = self::getTemplatePath($templateName);
        extract($data);
        ob_start();
        require $templatePath;

        return ob_get_clean();
    }

    protected static function getTemplatePath($templateName)
    {
        $templatePath = \TRANSFONTER_CORE_TEMPLATES . '/' . $templateName . '.php';
        if (!is_file($templatePath)) {
            throw new FileNotFound("Template {$templatePath} not found");
        }

        return $templatePath;
    }
}
