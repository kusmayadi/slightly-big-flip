<?php

namespace Lib;

class Env
{
    public static function get($key, $default = '')
    {
        $envFile = __DIR__ . '/../.env';

        if (!file_exists($envFile)) {
            throw new \Exception('No .env file exists.');
        } else {
            $rawEnvVars = array_filter(explode("\n", file_get_contents($envFile)));
            $envVars = [];

            foreach ($rawEnvVars as $vars)
            {
                $eachVars = explode('=', preg_replace('/[ ]+/', '', $vars));

                $envVars[$eachVars[0]] = $eachVars[1];
            }

            if (array_key_exists($key, $envVars)) {
                return $envVars[$key];
            } else {
                return $default;
            }
        }
    }
}
