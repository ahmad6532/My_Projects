<?php

return [
    'oracle' => [
        'driver'         => 'oracle',
        'tns'            => env('DB_TNS', ''),
        'host'           => env('DB_HOST_ORACLE', '192.168.243.55'),
        'port'           => env('DB_PORT_ORACLE', '1521'),
        'database'       => env('DB_DATABASE_ORACLE', ''),
        'service_name'   => env('DB_SERVICENAME', 'dev'),
        'username'       => env('DB_USERNAME_ORACLE', 'viper'),
        'password'       => env('DB_PASSWORD_ORACLE', 'viper123'),
        'charset'        => env('DB_CHARSET', 'AL32UTF8'),
        'prefix'         => env('DB_PREFIX', ''),
        'prefix_schema'  => env('DB_SCHEMA_PREFIX', ''),
        'edition'        => env('DB_EDITION', 'ora$base'),
        'server_version' => env('DB_SERVER_VERSION', '11g'),
        'load_balance'   => env('DB_LOAD_BALANCE', 'yes'),
        'max_name_len'   => env('ORA_MAX_NAME_LEN', 30),
        'dynamic'        => [],
    ],
];
