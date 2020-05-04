<?php

use Lib\Env;

return [
    'driver' => Env::get('DB_DRIVER', 'mysql'),
    'host' => Env::get('DB_HOST', 'localhost'),
    'name' => Env::get('DB_NAME', 'dbname'),
    'user' => Env::get('DB_USER', 'dbuser'),
    'password' => Env::get('DB_PASSWORD', 'dbpass')
];
