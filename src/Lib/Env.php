<?php

namespace Lib;

class Env
{
    public static function get($key, $default = '')
    {
        if (defined('IS_TEST')) {
            $envFilename = '.env.testing';
        } else {
            $envFilename = '.env';
        }

        $envFile = __DIR__ . '/../' . $envFilename;

        if (!file_exists($envFile)) {
            throw new \Exception($envFilename . ' file doesn\'t exists.');
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
