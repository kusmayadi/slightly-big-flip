<?php
namespace Lib;

class Config
{
    public static function get($key) {
        $keys = explode('.', $key);

        $configFile = __DIR__ . '/../config/' . $keys[0] . '.php';

        if (!file_exists($configFile)) {
            return false;
        } else {
            $config = include($configFile);

            for ($i=1;$i<count($keys);$i++)
            {
                $config = $config[$keys[$i]];
            }

            return $config;
        }
    }
}
