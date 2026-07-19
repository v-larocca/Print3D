<?php

require_once 'Smarty/libs/Smarty.class.php';

use Smarty\Smarty;

class StartSmarty
{
    static function configuration()
    {
        $smarty = new Smarty();
        $smarty->setTemplateDir('Smarty/templates');
        $smarty->setCompileDir('Smarty/templates_c');
        $smarty->setConfigDir('Smarty/configs');
        $smarty->setCacheDir('Smarty/cache');
        return $smarty;
    }
}