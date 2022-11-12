<?php

return [
    'mysql' => [
        'host' => $_ENV['DB_HOST'],
        'database' => $_ENV['DB_NAME'],
        'db_user' => $_ENV['DB_USER'],
        'db_password' => $_ENV['DB_PASSWORD']
    ]
];
