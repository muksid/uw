<?php

return [
    'oracle_db' => [
        'driver'         => 'oracle',
        'tns'            => '(DESCRIPTION=
                (ADDRESS=(PROTOCOL=tcp)(HOST=192.168.1.6)(PORT=1521))
            (CONNECT_DATA=
                (SID=IABS)
            )
        )',
        'host'           => env('DB_HOST_ORA', '192.168.1.6'),
        'port'           => env('DB_PORT_ORA', '1521'),
        'database'       => env('DB_DATABASE_ORA', 'IABS'),
        'username'       => env('DB_USERNAME_ORA', 'ibs'),
        'password'       => env('DB_PASSWORD_ORA', 'testdb'),
        'charset'        => env('DB_CHARSET', 'AL32UTF8'),
        'prefix'         => env('DB_PREFIX', ''),
        'prefix_schema'  => env('DB_SCHEMA_PREFIX', ''),
        'edition'        => env('DB_EDITION', 'ora$base'),
        'server_version' => env('DB_SERVER_VERSION', '11g'),
    ],
];
