<?php

spl_autoload_register('autoLoader');

function autoLoader($className)
{
    $file = str_replace('\\',DIRECTORY_SEPARATOR, $className);
    require_once $file . '.php';
}
