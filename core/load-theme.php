<?php

/*
 * Load_Theme: Responsible for treat the request and call the correspondent theme files.
 *
 * @package Avant
 */
namespace Avant\Core;

use Avant\Url_Mapping;

class Load_Theme {
    private $reservedFiles = array(
        'index', 'header', 'footer', 'functions'
    );

    public function __construct($request)
    {
        $this->loadLanguages();

        $this->loadFunctions();

        if (isset($request['params'][0])) {
            $templateFile = $request['params'][0];

            if ($this->isReserved($templateFile)) {
                $templateFile = $templateFile . '-2';
            }
        } else {
            $templateFile = 'index';
            $GLOBALS['avant']['page'] = 'index';
        }

        if (is_readable(THEME_PATH . $templateFile . '.php')) {
            include THEME_PATH . $templateFile . '.php';
        } else if (is_readable(THEME_PATH . '404.php') && (!DEBUG)){
            $GLOBALS['avant']['page'] = '404';
            include THEME_PATH . '404.php';
        } else {
            // Die with an error message
            die("I can't find the <code>" . $templateFile . ".php</code> file.");
        }
    }

    private function isReserved($fileName)
    {
        if (in_array($fileName, $this->reservedFiles)) {
            return true;
        } else {
            return false;
        }
    }

    private function loadLanguages()
    {
        _textdomain(THEME, ROOT . THEMES_DIR . DS . THEME . DS . 'languages');
    }

    private function loadFunctions()
    {
        $file = THEME_PATH . 'functions.php';

        if (!file_exists($file)) return;

        include $file;
    }

    public function __destruct()
    {
        _textdomain('avant');
    }
}