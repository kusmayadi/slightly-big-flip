<?php

use Lib\Env;

return [
    'host' => Env::get('DB_HOST', 'localhost'),
    'name' => ENV::get('DB_NAME', 'dbname'),
    'user' => ENV::get('DB_USER', 'dbuser'),
    'password' => ENV::get('DB_PASSWORD', 'dbpass')
];
