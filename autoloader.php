<?php
function my_autoloader($className)
{
    $firstLetter = strtoupper($className[0]);
    switch ($firstLetter) {
        case 'E':
            include_once(__DIR__ . '/model/' . $className . '.php');
            break;
        case 'F':
            include_once(__DIR__ . '/foundation/' . $className . '.php');
            break;
        case 'V':
            include_once(__DIR__ . '/view/' . $className . '.php');
            break;
        case 'C':
            include_once(__DIR__ . '/control/' . $className . '.php');
            break;
        case 'U':
            include_once(__DIR__ . '/utility/' . $className . '.php');
            break;
    }
}

spl_autoload_register('my_autoloader');