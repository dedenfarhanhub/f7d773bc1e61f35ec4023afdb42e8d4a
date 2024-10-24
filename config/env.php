<?php

function getEnvVar($key, $default = null)
{
    $env = parse_ini_file(__DIR__ . '/../.env');
    return $env[$key] ?? $default;
}